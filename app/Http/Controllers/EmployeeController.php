<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * List all employees (booking_officer + fleet_assistant)
     */
    public function usermanagement()
    {
        $employees = User::whereIn('role', ['booking_officer', 'fleet_assistant'])
                        ->orderBy('name')
                        ->get();

        return view('admin.users', compact('employees'));
    }

    /**
     * Store a new employee
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255|min:2',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role'     => 'required|in:booking_officer,fleet_assistant',
            ], [
                'name.required' => 'The employee name is required.',
                'name.min' => 'The name must be at least 2 characters.',
                'email.required' => 'The email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The passwords do not match.',
                'role.required' => 'Please select a role for the employee.',
                'role.in' => 'Please select a valid role.',
            ]);

            User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'role'     => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('admin.users')
                            ->with('success', 'Employee added successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput()
                            ->with('error', 'Please fix the errors below.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'An error occurred while adding the employee. Please try again.');
        }
    }

    /**
     * Update employee
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = User::findOrFail($id);

            $validationRules = [
                'name'     => 'required|string|max:255|min:2',
                'email'    => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($id)
                ],
                'password' => 'nullable|min:8|confirmed',
                'role'     => 'required|in:booking_officer,fleet_assistant',
            ];

            $customMessages = [
                'name.required' => 'The employee name is required.',
                'name.min' => 'The name must be at least 2 characters.',
                'email.required' => 'The email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered by another user.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The passwords do not match.',
                'role.required' => 'Please select a role for the employee.',
                'role.in' => 'Please select a valid role.',
            ];

            $validated = $request->validate($validationRules, $customMessages);

            $employee->name  = $validated['name'];
            $employee->email = $validated['email'];
            $employee->role  = $validated['role'];

            if ($request->filled('password')) {
                $employee->password = Hash::make($validated['password']);
            }

            $employee->save();

            return redirect()->route('admin.users')
                            ->with('success', 'Employee updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput()
                            ->with('error', 'Please fix the errors below.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'An error occurred while updating the employee. Please try again.');
        }
    }

    /**
     * Delete employee (requires admin password confirmation)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $request->validate([
                'admin_password' => 'required',
            ], [
                'admin_password.required' => 'Please enter your password to confirm deletion.',
            ]);

            $employee = User::findOrFail($id);

            // Verify admin password
            if (!Hash::check($request->admin_password, auth()->user()->password)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid password. Deletion cancelled.'
                    ], 422);
                }
                
                return redirect()->route('admin.users')
                                ->with('error', 'Invalid password. Deletion cancelled.');
            }

            // Prevent deleting yourself
            if ($employee->id === auth()->id()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot delete your own account.'
                    ], 422);
                }
                
                return redirect()->route('admin.users')
                                ->with('error', 'You cannot delete your own account.');
            }

            $employeeName = $employee->name;
            $employee->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Employee '{$employeeName}' deleted successfully."
                ]);
            }

            return redirect()->route('admin.users')
                            ->with('success', "Employee '{$employeeName}' deleted successfully.");

        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                            ->withErrors($e->validator)
                            ->with('error', 'Please fix the validation errors.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            return redirect()->route('admin.users')
                            ->with('error', 'Employee not found.');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the employee.'
                ], 500);
            }

            return redirect()->route('admin.users')
                            ->with('error', 'An error occurred while deleting the employee. Please try again.');
        }
    }

    /**
     * Get employee data for editing (optional - for AJAX requests)
     */
    public function edit($id)
    {
        try {
            $employee = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'role' => $employee->role,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }
    }
}