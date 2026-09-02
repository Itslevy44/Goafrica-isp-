<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $tenant   = app('currentTenant');
        $networks = $tenant ? Network::where('tenant_id', $tenant->id)->with('region')->get() : collect();
        $regions  = Region::all();
        $staff    = $tenant ? User::where('tenant_id', $tenant->id)
                                  ->where('id', '!=', Auth::id())
                                  ->with('network')
                                  ->orderBy('created_at', 'desc')
                                  ->get()
                           : collect();

        return view('dashboard.settings.index', compact('networks', 'regions', 'tenant', 'staff'));
    }

    /**
     * Update the logged-in admin's profile (name, email, password).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password'      => 'nullable|string',
            'password'              => 'nullable|string|min:8|confirmed',
        ]);

        // If changing password, verify current
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Invite / create a new staff member.
     */
    public function storeStaff(Request $request)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'network_id' => 'nullable|exists:networks,id',
            'role'       => 'required|in:admin,staff',
        ]);

        // Ensure network belongs to this tenant if provided
        if (!empty($validated['network_id'])) {
            $network = Network::where('id', $validated['network_id'])
                              ->where('tenant_id', $tenant->id)
                              ->firstOrFail();
        }

        $tempPassword = \Illuminate\Support\Str::random(12);

        User::create([
            'tenant_id'  => $tenant->id,
            'network_id' => $validated['network_id'] ?? null,
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($tempPassword),
            'role'       => $validated['role'],
        ]);

        return back()->with('success', "Staff account created for {$validated['email']}. Temporary password: {$tempPassword}");
    }

    /**
     * Update a staff member's role and network assignment.
     */
    public function updateStaff(Request $request, User $user)
    {
        $tenant = app('currentTenant');

        if ($user->tenant_id !== $tenant->id) abort(403);

        $validated = $request->validate([
            'role'       => 'required|in:admin,staff',
            'network_id' => 'nullable|exists:networks,id',
        ]);

        $user->update($validated);

        return back()->with('success', "Staff member {$user->name} updated.");
    }

    /**
     * Remove a staff member.
     */
    public function destroyStaff(User $user)
    {
        $tenant = app('currentTenant');

        if ($user->tenant_id !== $tenant->id) abort(403);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot remove your own account.']);
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "{$name} has been removed.");
    }
}
