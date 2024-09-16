<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'username' => 'required|string|unique:admins|max:255',
            'employeeID' => 'required|string|unique:admins|max:255',
            'role' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $admin = Admin::create([
            'fullName' => $request->fullName,
            'username' => $request->username,
            'employeeID' => $request->employeeID,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Admin registered successfully!'], 201);
    }

    public function getAdminProfile(Request $request)
    {
        $admin = $request->user();  // Get the authenticated admin
        return response()->json($admin);
    }

    public function updateAdminProfile(Request $request)
    {
        $request->validate([
            'fullName' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:admins,username,' . $request->user()->id,
            'employeeID' => 'sometimes|string|max:255|unique:admins,employeeID,' . $request->user()->id,
            'role' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
            'currentPassword' => 'sometimes|string|min:8',  // Validate current password
        ]);

        $admin = $request->user();

        if ($request->filled('currentPassword')) {
            if (!Hash::check($request->currentPassword, $admin->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 400);
            }
        }

        $admin->update([
            'fullName' => $request->input('fullName', $admin->fullName),
            'username' => $request->input('username', $admin->username),
            'employeeID' => $request->input('employeeID', $admin->employeeID),
            'role' => $request->input('role', $admin->role),
            'password' => $request->filled('password') ? Hash::make($request->password) : $admin->password,
        ]);

        return response()->json(['message' => 'Profile updated successfully!']);
    }
}
