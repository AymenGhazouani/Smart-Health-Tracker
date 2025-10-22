@extends('layouts.app')

@section('title', 'Providers Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Providers Management</h1>
                    <p class="text-muted">Manage healthcare providers with advanced filtering and analytics</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item export-btn" href="#" data-format="excel">
                                <i class="fas fa-file-excel text-success"></i> Excel (.xlsx)
                            </a></li>
                            <li><a class="dropdown-item export-btn" href="#" data-format="pdf">
                                <i class="fas fa-file-pdf text-danger"></i> PDF (.pdf)
                            </a></li>
                            <li><a class="dropdown-item export-btn" href="#" data-format="csv">
                                <i class="fas fa-file-csv text-info"></i> CSV (.csv)
                            </a></li>
                        </ul>
                    </div>
                    <a href="{{ route('providers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Provider
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="total-providers">{{ $stats['total'] ?? 0 }}</h4>
                            <p class="mb-0">Total Providers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="active-providers">{{ $stats['active'] ?? 0 }}</h4>
                            <p class="mb-0">Active Providers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="inactive-providers">{{ $stats['inactive'] ?? 0 }}</h4>
                            <p class="mb-0">Inactive Providers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pause-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="providers-with-appointments">{{ $stats['with_appointments'] ?? 0 }}</h4>
                            <p class="mb-0">With Appointments</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Advanced Filters
                <button class="btn btn-sm btn-outline-secondary float-end" id="clear-filters">
                    Clear All Filters
                </button>
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control filter-input" id="provider-search" 
                           name="search" placeholder="Search providers...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Specialty</label>
                    <select class="form-select filter-select" name="specialty">
                        <option value="">All Specialties</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}">{{ $specialty }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select filter-select" name="status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min Rate ($)</label>
                    <input type="number" class="form-control filter-input" name="min_rate" 
                           placeholder="0" min="0" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max Rate ($)</label>
                    <input type="number" class="form-control filter-input" name="max_rate" 
                           placeholder="1000" min="0" step="0.01">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Per Page</label>
                    <select class="form-select" id="per-page-select">
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label">Has Appointments</label>
                    <select class="form-select filter-select" name="has_appointments">
                        <option value="">All</option>
                        <option value="yes">With Appointments</option>
                        <option value="no">Without Appointments</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Created From</label>
                    <input type="date" class="form-control filter-input date-filter" name="created_from">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Created To</label>
                    <input type="date" class="form-control filter-input date-filter" name="created_to">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select filter-select" name="sort_by">
                        <option value="">Default</option>
                        <option value="name">Name</option>
                        <option value="specialty">Specialty</option>
                        <option value="hourly_rate">Hourly Rate</option>
                        <option value="created_at">Registration Date</option>
                        <option value="appointments_count">Appointments Count</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Summary -->
    <div id="filter-summary" class="alert alert-info" style="display: none;"></div>

    <!-- Bulk Actions -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all-providers">
                        <label class="form-check-label" for="select-all-providers">
                            Select All
                        </label>
                    </div>
                    <select class="form-select" id="bulk-action-select" style="width: auto;">
                        <option value="">Choose Action</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="btn btn-outline-primary" id="bulk-action-btn" disabled>
                        Bulk Actions
                    </button>
                </div>
                <div id="loading-indicator" class="spinner-border spinner-border-sm" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Providers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="providers-table">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all-providers">
                            </th>
                            <th>Provider</th>
                            <th>Specialty</th>
                            <th>Hourly Rate</th>
                            <th>Status</th>
                            <th>Appointments</th>
                            <th>Joined</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic content loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div id="pagination-container" class="d-flex justify-content-center mt-4">
                <!-- Dynamic pagination loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Analytics Modal -->
<div class="modal fade" id="analyticsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Provider Analytics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="analytics-content">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('js/providers-advanced.js') }}"></script>
@endpush