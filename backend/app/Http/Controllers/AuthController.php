<?php

// AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Admin;  // Assuming you have an Admin model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginCustomer(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Find customer by email
        $customer = Customer::where('email', $request->email)->first();

        // Check if customer exists and password is correct
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Generate token for authenticated user
        $token = $customer->createToken('auth_token')->plainTextToken;

        // Return response with the token
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token
        ]);
    }

    public function loginAdmin(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Find admin by username
        $admin = Admin::where('username', $request->username)->first();

        // Check if admin exists and password is correct
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid username or password.'
            ], 401);
        }

        // Generate token for authenticated user
        $token = $admin->createToken('auth_token')->plainTextToken;

        // Return response with the token and role
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'role' => $admin->role // Assuming 'role' is a column in your Admin model (e.g., 'admin', 'staff')
        ]);
    }
}
