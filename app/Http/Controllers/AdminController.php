<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuarterlyStatisticImport;
use App\Imports\LandLicenseImport;
use App\Imports\RailPassengerImport;
use App\Imports\MaritimeStatisticImport;
use App\Models\QuarterlyStatistic;
use App\Models\LandLicense;
use App\Models\RailPassenger;
use App\Models\MaritimeStatistic;

class AdminController extends Controller
{
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
            'year' => 'required|integer',
            'quarter' => 'required|integer',
        ]);

        Excel::import(new QuarterlyStatisticImport($request->year, $request->quarter), $request->file('excel_file'));

        return back()->with('success', 'Excel data imported successfully!');
    }

    public function deleteData(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
        ]);

        QuarterlyStatistic::where('year', $request->year)->delete();

        return back()->with('success', "All data for year {$request->year} deleted successfully!");
    }

    // Land Transport
    public function uploadLandExcel(Request $request)
    {
        $request->validate(['excel_file' => 'required|mimes:xlsx,xls,csv', 'year' => 'required|integer']);
        Excel::import(new LandLicenseImport($request->year), $request->file('excel_file'));
        return back()->with('success', 'Land Licenses data imported successfully!');
    }

    public function deleteLandData(Request $request)
    {
        $request->validate(['year' => 'required|integer']);
        LandLicense::where('year', $request->year)->delete();
        return back()->with('success', "Land Licenses data for year {$request->year} deleted successfully!");
    }

    // Rail Transport
    public function uploadRailExcel(Request $request)
    {
        $request->validate(['excel_file' => 'required|mimes:xlsx,xls,csv', 'year' => 'required|integer']);
        Excel::import(new RailPassengerImport($request->year), $request->file('excel_file'));
        return back()->with('success', 'Rail Passenger data imported successfully!');
    }

    public function deleteRailData(Request $request)
    {
        $request->validate(['year' => 'required|integer']);
        RailPassenger::where('year', $request->year)->delete();
        return back()->with('success', "Rail Passenger data for year {$request->year} deleted successfully!");
    }

    // Maritime Transport
    public function uploadMaritimeExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv', 
            'year' => 'required|integer',
            'quarter' => 'required|integer'
        ]);
        Excel::import(new MaritimeStatisticImport($request->year, $request->quarter), $request->file('excel_file'));
        return back()->with('success', 'Maritime data imported successfully!');
    }

    public function deleteMaritimeData(Request $request)
    {
        $request->validate(['year' => 'required|integer', 'quarter' => 'required|integer']);
        MaritimeStatistic::where('year', $request->year)->where('quarter', $request->quarter)->delete();
        return back()->with('success', "Maritime data for Q{$request->quarter} {$request->year} deleted successfully!");
    }

    public function manageAdmins()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('superadmin.manage_admins', compact('users'));
    }

    public function deleteAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself or another superadmin
        if ($user->id === auth()->id() || $user->role === 'superadmin') {
            return back()->withErrors(['error' => 'Cannot delete this user.']);
        }
        
        $user->delete();
        
        return back()->with('success', 'Admin user deleted successfully!');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return back()->with('success', 'Admin user created successfully!');
    }
}
