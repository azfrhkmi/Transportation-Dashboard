<?php

namespace App\Exports;

use App\Models\QuarterlyStatistic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuarterlyStatisticExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return QuarterlyStatistic::where('year', $this->year)
            ->select(
                'quarter',
                'airport_name',
                'domestic_scheduled',
                'domestic_non_scheduled',
                'domestic_total',
                'international_scheduled',
                'international_non_scheduled',
                'international_total',
                'total_scheduled',
                'total_non_scheduled',
                'grand_total'
            )
            ->orderBy('quarter')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Quarter',
            'Airport',
            'Domestic (Scheduled)',
            'Domestic (Non-Scheduled)',
            'Domestic Total',
            'International (Scheduled)',
            'International (Non-Scheduled)',
            'International Total',
            'Total (Scheduled)',
            'Total (Non-Scheduled)',
            'Grand Total',
        ];
    }
}
