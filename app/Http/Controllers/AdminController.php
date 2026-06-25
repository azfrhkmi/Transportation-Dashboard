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

    public function createAdmin()
    {
        return view('superadmin.add_admin');
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
