<?php

namespace App\Exports;

use App\Models\Provider;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProvidersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $providers;

    public function __construct($providers = null)
    {
        $this->providers = $providers;
    }

    public function collection()
    {
        return $this->providers ?? Provider::with(['user', 'appointments'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Provider Name',
            'Email',
            'Specialty',
            'Hourly Rate',
            'Status',
            'Bio',
            'Total Appointments',
            'Created Date',
            'Last Updated'
        ];
    }

    public function map($provider): array
    {
        return [
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
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}