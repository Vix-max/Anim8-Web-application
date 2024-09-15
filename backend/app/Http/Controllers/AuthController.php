<?php

// AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
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
}
