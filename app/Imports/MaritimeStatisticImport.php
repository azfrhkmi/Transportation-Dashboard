<?php

namespace App\Imports;

use App\Models\MaritimeStatistic;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MaritimeStatisticImport implements ToCollection
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
        foreach ($rows as $index => $row) {
            // Data starts around row 10 (index 9)
            if ($index < 9) continue;

            $portName = $row[0] ?? null;

            if (empty($portName)) continue;

            $upperPort = strtoupper($portName);
            if (str_contains($upperPort, 'JUMLAH') || str_contains($upperPort, 'SUMBER') || str_contains($upperPort, 'TOTAL')) {
                continue; // Skip subtotals, grand totals, and footers
            }

            $int_mother = $this->parseNumber($row[1] ?? 0);
            $int_feeder = $this->parseNumber($row[2] ?? 0);
            $int_cargo  = $this->parseNumber($row[3] ?? 0);
            $int_tanker = $this->parseNumber($row[4] ?? 0);
            $int_bulk   = $this->parseNumber($row[5] ?? 0);
            $int_others = $this->parseNumber($row[6] ?? 0);
            $int_total  = $int_mother + $int_feeder + $int_cargo + $int_tanker + $int_bulk + $int_others;

            $dom_mother = $this->parseNumber($row[8] ?? 0);
            $dom_feeder = $this->parseNumber($row[9] ?? 0);
            $dom_cargo  = $this->parseNumber($row[10] ?? 0);
            $dom_tanker = $this->parseNumber($row[11] ?? 0);
            $dom_bulk   = $this->parseNumber($row[12] ?? 0);
            $dom_others = $this->parseNumber($row[13] ?? 0);
            $dom_total  = $dom_mother + $dom_feeder + $dom_cargo + $dom_tanker + $dom_bulk + $dom_others;

            $others = $this->parseNumber($row[15] ?? 0);
            $grand_total = $int_total + $dom_total + $others;

            // Create data row
            MaritimeStatistic::create([
                'year'       => $this->year,
                'quarter'    => $this->quarter,
                'port_name'  => trim($portName),
                'int_mother' => $int_mother,
                'int_feeder' => $int_feeder,
                'int_cargo'  => $int_cargo,
                'int_tanker' => $int_tanker,
                'int_bulk'   => $int_bulk,
                'int_others' => $int_others,
                'int_total'  => $int_total,
                'dom_mother' => $dom_mother,
                'dom_feeder' => $dom_feeder,
                'dom_cargo'  => $dom_cargo,
                'dom_tanker' => $dom_tanker,
                'dom_bulk'   => $dom_bulk,
                'dom_others' => $dom_others,
                'dom_total'  => $dom_total,
                'others'     => $others,
                'grand_total'=> $grand_total,
            ]);
        }
    }

    private function parseNumber($val)
    {
        if (empty($val) || $val === '-') return 0;
        return (int) str_replace(',', '', $val);
    }
}
