<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'isp_name' => 'required|string|max:255',
            'country' => 'required|string',
            'admin_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($request) {
            $region = Region::firstOrCreate(
                ['country_code' => 'KE'],
                ['name' => 'Kenya', 'currency' => 'KES', 'timezone' => 'Africa/Nairobi']
            );

            $tenant = Tenant::create([
                'name' => $request->isp_name,
                'email' => $request->email,
                'country' => $request->country,
                'default_currency' => $region->currency,
                'subscription_ends_at' => now()->addDays(3),
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            return $user;
        });

        // Trigger verification email
        event(new Registered($user));

        Auth::login($user);

        // Bind the current tenant since we just logged them in
        app()->instance('currentTenant', Tenant::find($user->tenant_id));

        // Send new users to the setup wizard
        return redirect()->route('dashboard.setup.index')
            ->with('success', 'Welcome! Let\'s set up your hotspot network in 4 quick steps.');
    }
}
