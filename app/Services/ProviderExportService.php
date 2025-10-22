<?php

namespace App\Services;

use App\Models\Provider;
use App\Exports\ProvidersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProviderExportService
{
    /**
     * Export providers to Excel format
     */
    public function exportToExcel(Collection $providers, array $options = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = $options['filename'] ?? 'providers_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProvidersExport($providers), $filename);
    }

    /**
     * Export providers to PDF format
     */
    public function exportToPdf(Collection $providers, array $options = []): \Illuminate\Http\Response
    {
        $filename = $options['filename'] ?? 'providers_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $data = [
            'providers' => $providers,
            'title' => $options['title'] ?? 'Providers Report',
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
            'stats' => $this->calculateStats($providers),
        ];

        $pdf = Pdf::loadView('admin.providers.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download($filename);
    }

    /**
     * Export providers to CSV format
     */
    public function exportToCsv(Collection $providers, array $options = []): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = $options['filename'] ?? 'providers_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($providers) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID', 'Provider Name', 'Email', 'Specialty', 'Hourly Rate', 
                'Status', 'Bio', 'Total Appointments', 'Created Date', 'Last Updated'
            ]);

            // Add data rows
            foreach ($providers as $provider) {
                fputcsv($handle, [
                    $provider->id,
                    $provider->user->name ?? 'N/A',
                    $provider->user->email ?? 'N/A',
                    $provider->specialty,
                    $provider->hourly_rate ? '$' . number_format($provider->hourly_rate, 2) : 'Not set',
                    $provider->is_active ? 'Active' : 'Inactive',
                    $provider->bio ? substr($provider->bio, 0, 100) . '...' : 'No bio',
                    $provider->appointments->count(),
                    $provider->created_at->format('Y-m-d H:i:s'),
                    $provider->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Generate provider summary report
     */
    public function generateSummaryReport(Collection $providers): array
    {
        return [
            'total_providers' => $providers->count(),
            'active_providers' => $providers->where('is_active', true)->count(),
            'specialties' => $providers->groupBy('specialty')->map->count(),
            'average_rate' => $providers->whereNotNull('hourly_rate')->avg('hourly_rate'),
            'total_appointments' => $providers->sum(function ($provider) {
                return $provider->appointments->count();
            }),
            'providers_with_bio' => $providers->whereNotNull('bio')->count(),
        ];
    }

    /**
     * Calculate statistics for export
     */
    private function calculateStats(Collection $providers): array
    {
        return [
            'total_count' => $providers->count(),
            'active_count' => $providers->where('is_active', true)->count(),
            'inactive_count' => $providers->where('is_active', false)->count(),
            'specialties_count' => $providers->pluck('specialty')->unique()->count(),
            'average_rate' => $providers->whereNotNull('hourly_rate')->avg('hourly_rate'),
            'total_appointments' => $providers->sum(function ($provider) {
                return $provider->appointments->count();
            }),
        ];
    }

    /**
     * Get export format options
     */
    public function getExportFormats(): array
    {
        return [
            'excel' => [
                'label' => 'Excel (.xlsx)',
                'icon' => 'fas fa-file-excel',
                'color' => 'success'
            ],
            'pdf' => [
                'label' => 'PDF (.pdf)',
                'icon' => 'fas fa-file-pdf',
                'color' => 'danger'
            ],
            'csv' => [
                'label' => 'CSV (.csv)',
                'icon' => 'fas fa-file-csv',
                'color' => 'info'
            ]
        ];
    }
}