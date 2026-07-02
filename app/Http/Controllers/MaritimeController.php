<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaritimeController extends Controller
{
    public function report(Request $request)
    {
        $years = \App\Models\MaritimeStatistic::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $targetYear = $request->query('year', !empty($years) ? $years[0] : date('Y'));

        // Fetch all stats for the year
        $stats = \App\Models\MaritimeStatistic::where('year', $targetYear)->get();

        // Calculate totals across all quarters
        $totalInt = $stats->sum('int_total');
        $totalDom = $stats->sum('dom_total');
        $totalShips = $stats->sum('grand_total');

        // Top 5 Ports by Volume
        $portVolumes = $stats->groupBy('port_name')->map(function ($items) {
            return $items->sum('grand_total');
        })->sortDesc()->take(5);

        return view('dashboard.maritime.report', compact('years', 'targetYear', 'totalInt', 'totalDom', 'totalShips', 'portVolumes'));
    }

    public function index(Request $request)
    {
        $years = \App\Models\MaritimeStatistic::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $targetYear = $request->query('year', !empty($years) ? $years[0] : date('Y'));
        
        $quarters = \App\Models\MaritimeStatistic::where('year', $targetYear)->select('quarter')->distinct()->orderBy('quarter', 'asc')->pluck('quarter')->toArray();
        $targetQuarter = $request->query('quarter', !empty($quarters) ? $quarters[0] : 1);
        
        $statistics = \App\Models\MaritimeStatistic::where('year', $targetYear)->where('quarter', $targetQuarter)->get();
        
        return view('dashboard.maritime.index', compact('years', 'targetYear', 'quarters', 'targetQuarter', 'statistics'));
    }
}
