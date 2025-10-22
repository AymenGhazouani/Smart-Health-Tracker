/**
 * Advanced Providers Management JavaScript
 */

class ProvidersManager {
    constructor() {
        this.init();
        this.selectedProviders = new Set();
        this.currentFilters = {};
    }

    init() {
        this.bindEvents();
        this.initializeFilters();
        this.loadProviders();
    }

    bindEvents() {
        // Search functionality
        $('#provider-search').on('input', this.debounce(this.handleSearch.bind(this), 300));
        
        // Filter events
        $('.filter-select').on('change', this.handleFilterChange.bind(this));
        $('.filter-input').on('input', this.debounce(this.handleFilterChange.bind(this), 300));
        
        // Bulk actions
        $('#bulk-action-btn').on('click', this.handleBulkAction.bind(this));
        $('.provider-checkbox').on('change', this.handleProviderSelection.bind(this));
        $('#select-all-providers').on('change', this.handleSelectAll.bind(this));
        
        // Export buttons
        $('.export-btn').on('click', this.handleExport.bind(this));
        
        // Status toggle
        $('.status-toggle').on('click', this.handleStatusToggle.bind(this));
        
        // Clear filters
        $('#clear-filters').on('click', this.clearFilters.bind(this));
        
        // Pagination
        $(document).on('click', '.pagination a', this.handlePagination.bind(this));
    }

    initializeFilters() {
        // Initialize date pickers
        $('.date-filter').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        // Initialize select2 for better dropdowns
        if ($.fn.select2) {
            $('.filter-select').select2({
                placeholder: 'Select...',
                allowClear: true
            });
        }
    }

    async loadProviders(page = 1) {
        try {
            this.showLoading();
            
            const params = new URLSearchParams({
                page: page,
                per_page: $('#per-page-select').val() || 15,
                ...this.currentFilters
            });

            const response = await fetch(`/api/v1/providers?${params}`);
            const data = await response.json();

            if (data.success) {
                this.renderProviders(data.data);
                this.updateStats(data.stats);
                this.updateFilterSummary(data.filter_summary);
            }
        } catch (error) {
            console.error('Error loading providers:', error);
            this.showError('Failed to load providers');
        } finally {
            this.hideLoading();
        }
    }

    renderProviders(providersData) {
        const tbody = $('#providers-table tbody');
        tbody.empty();

        if (providersData.data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <p>No providers found matching your criteria</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        providersData.data.forEach(provider => {
            const row = this.createProviderRow(provider);
            tbody.append(row);
        });

        this.updatePagination(providersData);
        this.updateSelectionState();
    }

    createProviderRow(provider) {
        const statusBadge = provider.is_active 
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';

        const hourlyRate = provider.hourly_rate 
            ? `$${parseFloat(provider.hourly_rate).toFixed(2)}`
            : 'Not set';

        return `
            <tr data-provider-id="${provider.id}">
                <td>
                    <input type="checkbox" class="provider-checkbox" value="${provider.id}">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <img src="${provider.profile_image || '/images/default-avatar.png'}" 
                                 class="rounded-circle" width="32" height="32" alt="Avatar">
                        </div>
                        <div>
                            <div class="fw-bold">${provider.user?.name || 'N/A'}</div>
                            <small class="text-muted">${provider.user?.email || 'N/A'}</small>
                        </div>
                    </div>
                </td>
                <td>${provider.specialty}</td>
                <td>${hourlyRate}</td>
                <td>${statusBadge}</td>
                <td>${provider.appointments?.length || 0}</td>
                <td>${new Date(provider.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="viewProvider(${provider.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="editProvider(${provider.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-${provider.is_active ? 'warning' : 'success'} status-toggle" 
                                data-provider-id="${provider.id}">
                            <i class="fas fa-${provider.is_active ? 'pause' : 'play'}"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="deleteProvider(${provider.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    handleSearch(event) {
        this.currentFilters.search = event.target.value;
        this.loadProviders();
    }

    handleFilterChange(event) {
        const filterName = event.target.name;
        const filterValue = event.target.value;

        if (filterValue) {
            this.currentFilters[filterName] = filterValue;
        } else {
            delete this.currentFilters[filterName];
        }

        this.loadProviders();
    }

    handleProviderSelection(event) {
        const providerId = event.target.value;
        
        if (event.target.checked) {
            this.selectedProviders.add(providerId);
        } else {
            this.selectedProviders.delete(providerId);
        }

        this.updateBulkActionButton();
    }

    handleSelectAll(event) {
        const checkboxes = $('.provider-checkbox');
        
        checkboxes.prop('checked', event.target.checked);
        
        if (event.target.checked) {
            checkboxes.each((_, checkbox) => {
                this.selectedProviders.add(checkbox.value);
            });
        } else {
            this.selectedProviders.clear();
        }

        this.updateBulkActionButton();
    }

    async handleBulkAction() {
        const action = $('#bulk-action-select').val();
        
        if (!action || this.selectedProviders.size === 0) {
            this.showError('Please select an action and at least one provider');
            return;
        }

        if (!confirm(`Are you sure you want to ${action} ${this.selectedProviders.size} provider(s)?`)) {
            return;
        }

        try {
            const response = await fetch('/providers/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    action: action,
                    provider_ids: Array.from(this.selectedProviders)
                })
            });

            const data = await response.json();

            if (data.message) {
                this.showSuccess(data.message);
                this.selectedProviders.clear();
                this.loadProviders();
            }
        } catch (error) {
            console.error('Bulk action error:', error);
            this.showError('Failed to perform bulk action');
        }
    }

    async handleStatusToggle(event) {
        const providerId = event.currentTarget.dataset.providerId;
        
        try {
            const response = await fetch(`/providers/${providerId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const data = await response.json();

            if (data.message) {
                this.showSuccess(data.message);
                this.loadProviders();
            }
        } catch (error) {
            console.error('Status toggle error:', error);
            this.showError('Failed to toggle provider status');
        }
    }

    async handleExport(event) {
        const format = event.currentTarget.dataset.format;
        
        try {
            const params = new URLSearchParams(this.currentFilters);
            window.open(`/providers/export/${format}?${params}`, '_blank');
        } catch (error) {
            console.error('Export error:', error);
            this.showError('Failed to export data');
        }
    }

    handlePagination(event) {
        event.preventDefault();
        const url = new URL(event.target.href);
        const page = url.searchParams.get('page');
        this.loadProviders(page);
    }

    clearFilters() {
        this.currentFilters = {};
        $('.filter-select').val('').trigger('change');
        $('.filter-input').val('');
        $('#provider-search').val('');
        this.loadProviders();
    }

    updateBulkActionButton() {
        const count = this.selectedProviders.size;
        const button = $('#bulk-action-btn');
        
        if (count > 0) {
            button.prop('disabled', false).text(`Actions (${count})`);
        } else {
            button.prop('disabled', true).text('Bulk Actions');
        }
    }

    updateSelectionState() {
        const totalCheckboxes = $('.provider-checkbox').length;
        const checkedCheckboxes = $('.provider-checkbox:checked').length;
        
        $('#select-all-providers').prop('indeterminate', 
            checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
    }

    updateStats(stats) {
        if (stats) {
            $('#total-providers').text(stats.total || 0);
            $('#active-providers').text(stats.active || 0);
            $('#inactive-providers').text(stats.inactive || 0);
            $('#providers-with-appointments').text(stats.with_appointments || 0);
        }
    }

    updateFilterSummary(filters) {
        const container = $('#filter-summary');
        
        if (filters && filters.length > 0) {
            const badges = filters.map(filter => 
                `<span class="badge bg-info me-1">${filter}</span>`
            ).join('');
            
            container.html(`
                <div class="d-flex align-items-center">
                    <span class="me-2">Active filters:</span>
                    ${badges}
                    <button class="btn btn-sm btn-outline-secondary ms-2" id="clear-filters">
                        Clear all
                    </button>
                </div>
            `).show();
        } else {
            container.hide();
        }
    }

    updatePagination(data) {
        // Update pagination links
        const pagination = $('#pagination-container');
        // Implementation depends on your pagination structure
    }

    // Utility methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    showLoading() {
        $('#loading-indicator').show();
    }

    hideLoading() {
        $('#loading-indicator').hide();
    }

    showSuccess(message) {
        this.showToast(message, 'success');
    }

    showError(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'info') {
        // Implementation depends on your toast/notification system
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
}

// Global functions for row actions
function viewProvider(id) {
    window.location.href = `/providers/${id}`;
}

function editProvider(id) {
    window.location.href = `/providers/${id}/edit`;
}

function deleteProvider(id) {
    if (confirm('Are you sure you want to delete this provider?')) {
        // Implementation for delete
        fetch(`/providers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then(() => {
            window.providersManager.loadProviders();
        });
    }
}

// Initialize when document is ready
$(document).ready(function() {
    window.providersManager = new ProvidersManager();
});