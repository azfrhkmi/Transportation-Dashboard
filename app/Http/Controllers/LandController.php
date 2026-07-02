<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandController extends Controller
{
    public function index(Request $request)
    {
        $yearsLicenses = \App\Models\LandLicense::select('year')->distinct()->pluck('year')->toArray();
        $yearsRail = \App\Models\RailPassenger::select('year')->distinct()->pluck('year')->toArray();
        $years = array_unique(array_merge($yearsLicenses, $yearsRail));
        rsort($years);
        
        $targetYear = $request->query('year', !empty($years) ? $years[0] : date('Y'));
        
        // Summarize Licenses
        $licenses = \App\Models\LandLicense::where('year', $targetYear)->get();
        $totalLicenses = $licenses->sum('q1') + $licenses->sum('q2') + $licenses->sum('q3') + $licenses->sum('q4');
        
        $licenseChartData = [
            $licenses->sum('q1'),
            $licenses->sum('q2'),
            $licenses->sum('q3'),
            $licenses->sum('q4')
        ];

        // License Categories Doughnut
        $licenseCategories = $licenses->groupBy('category')->map(function ($items) {
            return $items->sum('q1') + $items->sum('q2') + $items->sum('q3') + $items->sum('q4');
        })->toArray();

        // Summarize Rail
        $passengers = \App\Models\RailPassenger::where('year', $targetYear)->get();
        $totalPassengers = $passengers->sum('q1') + $passengers->sum('q2') + $passengers->sum('q3') + $passengers->sum('q4');
        
        $railChartData = [
            $passengers->sum('q1'),
            $passengers->sum('q2'),
            $passengers->sum('q3'),
            $passengers->sum('q4')
        ];
        
        return view('dashboard.land.index', compact('years', 'targetYear', 'totalLicenses', 'totalPassengers', 'licenseChartData', 'railChartData', 'licenseCategories'));
    }

    public function licenses(Request $request)
    {
        $years = \App\Models\LandLicense::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $targetYear = $request->query('year', !empty($years) ? $years[0] : date('Y'));
        
        $licenses = \App\Models\LandLicense::where('year', $targetYear)->get()->groupBy('category');
        
        return view('dashboard.land.licenses', compact('years', 'targetYear', 'licenses'));
    }

    public function rail(Request $request)
    {
        $years = \App\Models\RailPassenger::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $targetYear = $request->query('year', !empty($years) ? $years[0] : date('Y'));
        
        $passengers = \App\Models\RailPassenger::where('year', $targetYear)->get();
        
        return view('dashboard.land.rail', compact('years', 'targetYear', 'passengers'));
    }
}
