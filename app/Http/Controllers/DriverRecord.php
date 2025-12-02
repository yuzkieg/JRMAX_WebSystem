<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverRecord extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.driverrecord', compact('drivers'));
    }
    
    public function store(Request $request)
    {
        // Accept either 'license_no' (UI) or 'license_num' (older forms)
        $license = $request->input('license_no', $request->input('license_num'));
        $request->merge(['license_num' => $license]);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:drivers,email',
            'license_num' => 'required|string|max:13|unique:drivers,license_num',
            'dateadded' => 'nullable|date',
        ]);

        $dateadded = $request->input('dateadded', now());
        
        Driver::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'license_num' => $license,
            'dateadded' => $dateadded,
        ]);

        return redirect()->back()->with('success', 'Driver added successfully.');
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        
        // Accept either 'license_no' (UI) or 'license_num' (older forms)
        $license = $request->input('license_no', $request->input('license_num'));
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:drivers,email,' . $id,
            'license_num' => 'required|string|max:13|unique:drivers,license_num,' . $id,
            'dateadded' => 'nullable|date',
        ]);

        $driver->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'license_num' => $license,
            'dateadded' => $request->input('dateadded', $driver->dateadded),
        ]);

        return redirect()->back()->with('success', 'Driver updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        // Validate password
        $request->validate([
            'admin_password' => 'required|string',
        ], [
            'admin_password.required' => 'Password is required to confirm deletion.',
        ]);

        // Check if password is correct
        if (!Hash::check($request->input('admin_password'), auth()->user()->password)) {
            return response()->json(['message' => 'Incorrect password. Please try again.'], 422);
        }

        $driver = Driver::findOrFail($id);
        $driver->delete();
        
        return response()->json(['message' => 'Driver deleted successfully.']);
    }
}