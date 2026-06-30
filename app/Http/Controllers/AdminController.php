<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuarterlyStatisticImport;

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

        \App\Models\QuarterlyStatistic::where('year', $request->year)->delete();

        return back()->with('success', "All data for year {$request->year} deleted successfully!");
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
