<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // List all admins for superadmin dashboard
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('superadmin.dashboard', compact('admins'));
    }

    // Store new admin - SIMPLIFIED VERSION
    public function store(Request $request)
    {
        // Remove password confirmation since form doesn't have it
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // Changed from min:8 to min:6 and removed 'confirmed'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            return redirect()->route('superadmin.dashboard')
                             ->with('success', 'Admin added successfully.');
                             
        } catch (\Exception $e) {
            return redirect()->route('superadmin.dashboard')
                             ->with('error', 'Error creating admin: ' . $e->getMessage());
        }
    }

    // Update admin - SIMPLIFIED VERSION
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        // Remove password confirmation since form doesn't have it
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:6', // Changed from min:8 to min:6 and removed 'confirmed'
        ]);

        try {
            $admin->name = $request->name;
            $admin->email = $request->email;

            // Only update password if filled
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            $admin->save();

            return redirect()->route('superadmin.dashboard')
                             ->with('success', 'Admin updated successfully.');
                             
        } catch (\Exception $e) {
            return redirect()->route('superadmin.dashboard')
                             ->with('error', 'Error updating admin: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Verify superadmin password exists
            if (!$request->has('superadmin_password') || empty($request->superadmin_password)) {
                return redirect()->route('superadmin.dashboard')
                                ->with('error', 'Password is required to confirm deletion.');
            }

            // Verify superadmin password is correct
            if (!Hash::check($request->superadmin_password, auth()->user()->password)) {
                return redirect()->route('superadmin.dashboard')
                                ->with('error', 'Invalid password. Deletion cancelled.');
            }

            $admin = User::findOrFail($id);
            
            // Prevent deleting yourself
            if ($admin->id === auth()->id()) {
                return redirect()->route('superadmin.dashboard')
                                ->with('error', 'You cannot delete your own account.');
            }

            // Prevent deleting the last admin
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('superadmin.dashboard')
                                ->with('error', 'Cannot delete the last admin account.');
            }

            $adminName = $admin->name; // Store name for success message
            $admin->delete();

            return redirect()->route('superadmin.dashboard')
                             ->with('success', "Admin '{$adminName}' deleted successfully.");
                             
        } catch (\Exception $e) {
            \Log::error('Admin deletion error: ' . $e->getMessage());
            return redirect()->route('superadmin.dashboard')
                             ->with('error', 'Error deleting admin: ' . $e->getMessage());
        }
    }
}