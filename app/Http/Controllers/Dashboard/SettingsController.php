<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Network;

use App\Models\Region;

class SettingsController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');
        $network = $tenant ? Network::where('tenant_id', $tenant->id)->first() : null;
        $regions = Region::all();
        return view('dashboard.settings.index', compact('network', 'regions', 'tenant'));
    }

    public function update(Request $request)
    {
        $tenant = app('currentTenant');
        $network = $tenant ? Network::where('tenant_id', $tenant->id)->first() : null;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:networks,slug,' . ($network ? $network->id : 'NULL'),
            'region_id' => 'required|exists:regions,id',
            'currency' => 'required|string|size:3',
        ]);

        if ($network) {
            $network->update($validated);
        } else {
            $validated['tenant_id'] = $tenant?->id;
            Network::create($validated);
        }

        // Update Daraja API Keys for the Tenant
        if ($tenant) {
            $darajaValidated = $request->validate([
                'mpesa_environment' => 'required|in:sandbox,production',
                'mpesa_shortcode' => 'required|string|max:255',
            ]);
            
            $tenant->update($darajaValidated);
            return back()->with('success', 'Network and Payment gateway settings updated successfully.');
        }

        return back()->with('success', 'Network settings updated successfully.');
    }
}
