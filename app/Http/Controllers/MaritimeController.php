<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaritimeController extends Controller
{
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
