<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class EmployeeRecord extends Controller
{
    public function index()
    {
        $employeesrecord = Employee::all();
        $drivers = Driver::all(); 
        return view('admin.adminhr', compact('employeesrecord', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:employee_records,email',
            'position' => 'required|string|max:100',
        ]);

        Employee::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'position' => $request->input('position'),
        ]);

        return redirect()->back()->with('success', 'Employee added successfully.');
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:employee_records,email,' . $id,
            'position' => 'required|string|max:100',
        ]);

        $employee->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'position' => $request->input('position'),
        ]);

        return redirect()->back()->with('success', 'Employee updated successfully.');
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

        $employeesrecord = Employee::findOrFail($id);
        $employeesrecord->delete();
        
        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}