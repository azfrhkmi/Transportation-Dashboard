<?php

namespace App\Imports;

use App\Models\RailPassenger;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RailPassengerImport implements ToCollection
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Data starts around row 5 (index 4)
            if ($index < 4) continue;

            $service = $row[0] ?? null;
            $q1 = $row[1] ?? null;
            $q2 = $row[2] ?? null;
            $q3 = $row[3] ?? null;
            $q4 = $row[4] ?? null;

            if (empty($service)) continue;

            $upperService = strtoupper($service);
            if (str_contains($upperService, 'JUMLAH') || str_contains($upperService, 'SUMBER') || str_contains($upperService, 'TOTAL')) {
                continue; // Skip subtotals, grand totals, and footers
            }

            // Create data row
            RailPassenger::create([
                'year'         => $this->year,
                'service_type' => trim($service),
                'q1'           => $this->parseNumber($q1),
                'q2'           => $this->parseNumber($q2),
                'q3'           => $this->parseNumber($q3),
                'q4'           => $this->parseNumber($q4),
            ]);
        }
    }

    private function parseNumber($val)
    {
        if (empty($val) || $val === '-') return 0;
        return (int) str_replace(',', '', $val);
    }
}
