<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Network;
use App\Models\SystemEvent;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function index()
    {
        $network = Network::first();
        if (!$network) {
            return redirect()->route('dashboard.settings.index')->withErrors(['error' => 'Please set up your network settings first.']);
        }

        $devices = Device::where('network_id', $network->id)->get();
        return view('dashboard.devices.index', compact('devices', 'network'));
    }

    public function store(Request $request)
    {
        $network = Network::firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'api_port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = json_encode([
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);

        $device = Device::create([
            'network_id' => $network->id,
            'name' => $validated['name'],
            'ip_address' => $validated['ip_address'],
            'api_port' => $validated['api_port'],
            'credentials_encrypted' => Crypt::encryptString($credentials),
            'type' => 'mikrotik',
            'status' => 'unknown',
        ]);

        SystemEvent::create([
            'tenant_id' => app('currentTenant')->id ?? null,
            'user_id' => Auth::id(),
            'action' => 'Added Device',
            'description' => "Added router: {$validated['name']} ({$validated['ip_address']})",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Router added successfully.');
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'api_port' => 'required|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'ip_address' => $validated['ip_address'],
            'api_port' => $validated['api_port'],
        ];

        if (!empty($validated['username']) && !empty($validated['password'])) {
            $credentials = json_encode([
                'username' => $validated['username'],
                'password' => $validated['password'],
            ]);
            $updateData['credentials_encrypted'] = Crypt::encryptString($credentials);
        }

        $device->update($updateData);

        SystemEvent::create([
            'tenant_id' => app('currentTenant')->id ?? null,
            'user_id' => Auth::id(),
            'action' => 'Updated Device',
            'description' => "Updated router: {$validated['name']} ({$validated['ip_address']})",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Router updated successfully.');
    }

    public function destroy(Device $device)
    {
        SystemEvent::create([
            'tenant_id' => app('currentTenant')->id ?? null,
            'user_id' => Auth::id(),
            'action' => 'Deleted Device',
            'description' => "Deleted router: {$device->name}",
            'ip_address' => request()->ip(),
        ]);

        $device->delete();
        return back()->with('success', 'Router deleted.');
    }
}
