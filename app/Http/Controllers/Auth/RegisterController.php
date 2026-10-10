<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CourierProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $type = $request->query('type', 'customer');

        return view('auth.register', compact('type'));
    }

    public function register(Request $request)
    {
        $type = $request->input('role', 'customer');

        if ($type === 'courier') {
            return $this->registerCourier($request);
        }

        return $this->registerCustomer($request);
    }

    protected function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Selamat datang di Desy Florist! Akun Anda berhasil dibuat.');
    }

    protected function registerCourier(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'identity_number' => ['required', 'string', 'max:50'],
            'vehicle_type' => ['required', 'string', 'in:motorcycle,car,van'],
            'vehicle_number' => ['required', 'string', 'max:50'],
            'plate_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'courier',
                'status' => 'pending', // Pending approval by Admin
            ]);

            CourierProfile::create([
                'user_id' => $user->id,
                'identity_number' => $validated['identity_number'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_number' => $validated['vehicle_number'],
                'plate_number' => $validated['plate_number'],
                'address' => $validated['address'],
                'status' => 'pending',
            ]);
        });

        return redirect()->route('login')->with(
            'success',
            'Pendaftaran kurir berhasil diajukan! Akun Anda sedang ditinjau oleh tim kami. Anda akan dapat masuk setelah diverifikasi.'
        );
    }
}
