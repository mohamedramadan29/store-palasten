<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Get orders and addresses data to pass to view
        $orders = \App\Models\front\Order::where('customer_id', $user->id)->latest()->get();
        $addresses = \App\Models\Address::where('user_id', $user->id)->get();
        return view('customer.dashboard', compact('user', 'orders', 'addresses'));
    }

    public function profile()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        return view('customer.profile', compact('user', 'addresses'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = \App\Models\front\Order::where('customer_id', $user->id)->latest()->paginate(10);
        return view('customer.orders', compact('user', 'orders'));
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        return view('customer.addresses', compact('user', 'addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully']);
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'type' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If setting as default, unset other default addresses
        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create([
            'type' => $request->type,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json(['success' => true, 'message' => 'Address added successfully']);
    }

    public function showPasswordForm()
    {
        $user = Auth::user();
        return view('customer.password', compact('user'));
    }
}
