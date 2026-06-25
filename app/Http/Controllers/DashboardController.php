<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarterlyStatistic;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Aggregate Stats
        $totalFlights = QuarterlyStatistic::sum('grand_total');
        $domesticFlights = QuarterlyStatistic::sum('domestic_total');
        $internationalFlights = QuarterlyStatistic::sum('international_total');
        $airportsCount = QuarterlyStatistic::distinct('airport_name')->count();

        // 2. Trend Data (Total Flights by Year-Quarter)
        // Order by year and quarter
        $trends = QuarterlyStatistic::selectRaw('year, quarter, sum(grand_total) as total')
            ->groupBy('year', 'quarter')
            ->orderBy('year', 'asc')
            ->orderBy('quarter', 'asc')
            ->get();
            
        $trendLabels = $trends->map(function($t) { return $t->year . ' Q' . $t->quarter; })->toArray();
        $trendData = $trends->map(function($t) { return $t->total; })->toArray();

        // 3. Distribution Data (Top 5 Airports vs Others)
        $airportDistribution = QuarterlyStatistic::selectRaw('airport_name, sum(grand_total) as total')
            ->groupBy('airport_name')
            ->orderBy('total', 'desc')
            ->get();
            
        $topAirports = $airportDistribution->take(5);
        $otherAirports = $airportDistribution->skip(5);
        
        $doughnutLabels = $topAirports->pluck('airport_name')->toArray();
        $doughnutData = $topAirports->pluck('total')->toArray();
        
        if ($otherAirports->sum('total') > 0) {
            $doughnutLabels[] = 'Others';
            $doughnutData[] = $otherAirports->sum('total');
        }

        return view('dashboard.index', compact(
            'totalFlights', 'domesticFlights', 'internationalFlights', 'airportsCount',
            'trendLabels', 'trendData', 'doughnutLabels', 'doughnutData'
        ));
    }

    public function quarterly(Request $request, $year = null)
    {
        if ($year) {
            $stats = QuarterlyStatistic::where('year', $year)->get();
            return view('dashboard.quarterly_year', compact('year', 'stats'));
        }

        $years = range(2014, 2026);
        $summary = QuarterlyStatistic::selectRaw('year, sum(grand_total) as total')
            ->groupBy('year')
            ->get()
            ->keyBy('year');
            
        return view('dashboard.quarterly', compact('years', 'summary'));
    }
}
