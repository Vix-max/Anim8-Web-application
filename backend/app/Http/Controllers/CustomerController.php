<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'username' => 'required|string|unique:customers|max:255',
            'email' => 'required|email|unique:customers|max:255',
            'phoneNumber' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customer::create([
            'fullName' => $request->fullName,
            'username' => $request->username,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Customer registered successfully!'], 201);
    }

    // In CustomerController.php

    public function getCustomerProfile(Request $request)
    {
        $customer = $request->user();  // Get the authenticated customer
        return response()->json($customer);
    }

    public function updateCustomerProfile(Request $request)
    {
        $request->validate([
            'fullName' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:customers,username,' . $request->user()->id,
            'email' => 'sometimes|email|max:255|unique:customers,email,' . $request->user()->id,
            'phoneNumber' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:500',
            'password' => 'sometimes|string|min:8',
            'currentPassword' => 'sometimes|string|min:8',  // Validate current password
        ]);

        // Get the logged-in customer
        $customer = $request->user();

        // Check if current password is provided and valid
        if ($request->filled('currentPassword')) {
            if (!Hash::check($request->currentPassword, $customer->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 400);
            }
        }

        // Update the customer details
        $customer->update([
            'fullName' => $request->input('fullName', $customer->fullName),
            'username' => $request->input('username', $customer->username),
            'email' => $request->input('email', $customer->email),
            'phoneNumber' => $request->input('phoneNumber', $customer->phoneNumber),
            'address' => $request->input('address', $customer->address),
            'password' => $request->filled('password') ? Hash::make($request->password) : $customer->password,
        ]);

        return response()->json(['message' => 'Profile updated successfully!']);
    }


}
