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
        // The data in Data Sektor Udara Q1 - Q4 2014.xlsx starts around row 9
        // 0: Airport, 1: Dom Sched, 2: Dom NonSched, 3: Dom Tot, 4: empty
        // 5: Int Sched, 6: Int NonSched, 7: Int Tot, 8: empty
        // 9: Tot Sched, 10: Tot NonSched, 11: Grand Tot
        
        $rowIndex = 0;
        foreach ($rows as $row) {
            $rowIndex++;
            
            // Skip headers
            if ($rowIndex < 9) {
                continue;
            }

            // If airport is empty or JUMLAH (Total), stop or skip
            $airportName = $row[0];
            if (empty($airportName) || str_contains(strtoupper($airportName), 'JUMLAH') || str_contains(strtoupper($airportName), 'TOTAL')) {
                continue;
            }

            // Create or update the statistic
            QuarterlyStatistic::updateOrCreate([
                'year' => $this->year,
                'quarter' => $this->quarter,
                'airport_name' => $airportName,
            ], [
                'domestic_scheduled' => (int) $row[1],
                'domestic_non_scheduled' => (int) $row[2],
                'domestic_total' => (int) $row[1] + (int) $row[2],
                'international_scheduled' => (int) $row[5],
                'international_non_scheduled' => (int) $row[6],
                'international_total' => (int) $row[5] + (int) $row[6],
                'total_scheduled' => (int) $row[1] + (int) $row[5],
                'total_non_scheduled' => (int) $row[2] + (int) $row[6],
                'grand_total' => ((int) $row[1] + (int) $row[2]) + ((int) $row[5] + (int) $row[6]),
            ]);
        }
    }
}
