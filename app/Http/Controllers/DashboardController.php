<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarterlyStatistic;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuarterlyStatisticExport;

class DashboardController extends Controller
{
    public function downloadReport($year)
    {
        return Excel::download(new QuarterlyStatisticExport($year), "Aviation_Report_{$year}.xlsx");
    }

    public function index(Request $request)
    {
        // Get all available years
        $availableYears = QuarterlyStatistic::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        
        // Default to the requested year, or the most recent year, or the current year if database is empty
        $targetYear = $request->query('year', !empty($availableYears) ? $availableYears[0] : date('Y'));

        // 1. Calculate Aggregate Stats
        $totalFlights = QuarterlyStatistic::where('year', $targetYear)->sum('grand_total');
        $domesticFlights = QuarterlyStatistic::where('year', $targetYear)->sum('domestic_total');
        $internationalFlights = QuarterlyStatistic::where('year', $targetYear)->sum('international_total');
        $airportsCount = QuarterlyStatistic::where('year', $targetYear)->distinct('airport_name')->count();

        // 2. Trend Data (Total Flights by Year-Quarter)
        $trends = QuarterlyStatistic::where('year', $targetYear)
            ->selectRaw('year, quarter, airport_name, sum(grand_total) as total')
            ->groupBy('year', 'quarter', 'airport_name')
            ->orderBy('year', 'asc')
            ->orderBy('quarter', 'asc')
            ->get();
            
        // Process trends to have an aggregate and per-airport dataset
        $quarters = $trends->pluck('quarter')->unique()->sort()->values();
        $trendLabels = $quarters->map(function($q) use ($targetYear) { return $targetYear . ' Q' . $q; })->toArray();
        
        $aggregateTrendData = [];
        $airportTrendData = [];
        
        foreach ($quarters as $q) {
            $qTrends = $trends->where('quarter', $q);
            $aggregateTrendData[] = $qTrends->sum('total');
            
            foreach ($qTrends as $t) {
                if (!isset($airportTrendData[$t->airport_name])) {
                    $airportTrendData[$t->airport_name] = array_fill(0, count($quarters), 0);
                }
                // Find index of quarter
                $qIndex = $quarters->search($q);
                $airportTrendData[$t->airport_name][$qIndex] = $t->total;
            }
        }
        
        $trendData = $aggregateTrendData;

        // 3. Distribution Data (Top 5 Airports vs Others)
        $airportDistribution = QuarterlyStatistic::where('year', $targetYear)
            ->selectRaw('airport_name, sum(grand_total) as total')
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

        // 4. Fetch all airports for the filter dropdown
        $allAirports = QuarterlyStatistic::where('year', $targetYear)
            ->select('airport_name')
            ->distinct()
            ->orderBy('airport_name', 'asc')
            ->pluck('airport_name')
            ->toArray();

        return view('dashboard.index', compact(
            'totalFlights', 'domesticFlights', 'internationalFlights', 'airportsCount',
            'trendLabels', 'trendData', 'doughnutLabels', 'doughnutData',
            'availableYears', 'targetYear', 'allAirports', 'airportTrendData'
        ));
    }

    public function quarterly(Request $request, $year = null)
    {
        if ($year) {
            $stats = QuarterlyStatistic::where('year', $year)->get();
            return view('dashboard.quarterly_year', compact('year', 'stats'));
        }

        $years = QuarterlyStatistic::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        
        $summary = QuarterlyStatistic::whereIn('year', $years)
            ->selectRaw('year, sum(grand_total) as total')
            ->groupBy('year')
            ->get()
            ->keyBy('year');
            
        return view('dashboard.quarterly', compact('years', 'summary'));
    }
}
