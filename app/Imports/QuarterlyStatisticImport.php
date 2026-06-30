<?php

namespace App\Imports;

use App\Models\QuarterlyStatistic;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuarterlyStatisticImport implements ToCollection
{
    protected $year;
    protected $quarter;

    public function __construct($year, $quarter)
    {
        $this->year = $year;
        $this->quarter = $quarter;
    }

    public function collection(Collection $rows)
    {
        // The data in the new Fourth Quarter 2025 Excel format starts at row 5
        // Columns:
        // 0: Airport
        // 1: Dom Sched, 2: Dom NonSched, 3: Dom Total
        // 4: Int Sched, 5: Int NonSched, 6: Int Total
        // 7: Total Sched, 8: Total NonSched, 9: Grand Total
        
        $rowIndex = 0;
        foreach ($rows as $row) {
            $rowIndex++;
            
            // Skip headers (Rows 1 to 4)
            if ($rowIndex < 5) {
                continue;
            }

            // Clean airport name and check if it's a valid data row
            $airportName = trim($row[0]);
            $upperName = strtoupper($airportName);

            // Skip empty rows, totals, regions, and footers
            if (empty($airportName) || 
                str_contains($upperName, 'JUMLAH') || 
                str_contains($upperName, 'TOTAL') ||
                str_contains($upperName, 'SEMENANJUNG') ||
                str_contains($upperName, 'SABAH') ||
                str_contains($upperName, 'SARAWAK') ||
                str_contains($upperName, 'SUMBER') ||
                str_contains($upperName, 'LAPANGAN TERBANG')) {
                continue;
            }

            // Helper to clean numeric values from excel
            $cleanVal = function($val) {
                if (empty($val) || $val === '-' || $val === ' ') return 0;
                // Remove commas and convert to int
                return (int) round((float) str_replace(',', '', $val));
            };

            // Create or update the statistic
            QuarterlyStatistic::updateOrCreate([
                'year' => $this->year,
                'quarter' => $this->quarter,
                'airport_name' => $airportName,
            ], [
                'domestic_scheduled' => $cleanVal($row[1]),
                'domestic_non_scheduled' => $cleanVal($row[2]),
                'domestic_total' => $cleanVal($row[3]),
                'international_scheduled' => $cleanVal($row[4]),
                'international_non_scheduled' => $cleanVal($row[5]),
                'international_total' => $cleanVal($row[6]),
                'total_scheduled' => $cleanVal($row[7]),
                'total_non_scheduled' => $cleanVal($row[8]),
                'grand_total' => $cleanVal($row[9]),
            ]);
        }
    }
}
