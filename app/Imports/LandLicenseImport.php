<?php

namespace App\Imports;

use App\Models\LandLicense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LandLicenseImport implements ToCollection
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection(Collection $rows)
    {
        $currentCategory = 'UNKNOWN';

        foreach ($rows as $index => $row) {
            // Data starts around row 6 (index 5)
            if ($index < 5) continue;

            $colB = $row[1] ?? null;
            $colC = $row[2] ?? null;
            $colD = $row[3] ?? null;
            $colE = $row[4] ?? null;
            $colF = $row[5] ?? null;

            if (empty($colB)) continue;

            $upperColB = strtoupper($colB);
            if (str_contains($upperColB, 'JUMLAH') || str_contains($upperColB, 'SUMBER') || str_contains($upperColB, 'TIDAK TERMASUK')) {
                continue; // Skip subtotals, grand totals, and footers
            }

            // If columns C and D are empty, this is likely a category header (e.g. BAS, LORI)
            if (empty($colC) && empty($colD) && empty($colE)) {
                $currentCategory = trim($colB);
                continue;
            }

            // Otherwise, it's a data row
            LandLicense::create([
                'year'         => $this->year,
                'category'     => $currentCategory,
                'license_type' => trim($colB),
                'q1'           => $this->parseNumber($colC),
                'q2'           => $this->parseNumber($colD),
                'q3'           => $this->parseNumber($colE),
                'q4'           => $this->parseNumber($colF),
            ]);
        }
    }

    private function parseNumber($val)
    {
        if (empty($val) || $val === '-') return 0;
        return (int) str_replace(',', '', $val);
    }
}
