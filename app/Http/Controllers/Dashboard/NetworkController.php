<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\Region;
use App\Models\SystemEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    public function index()
    {
        $tenant   = app('currentTenant');
        $networks = Network::where('tenant_id', $tenant->id)->with('region')->get();
        $regions  = Region::all();

        return view('dashboard.networks.index', compact('networks', 'regions', 'tenant'));
    }

    public function store(Request $request)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:100|unique:networks,slug|regex:/^[a-z0-9\-]+$/',
            'region_id' => 'required|exists:regions,id',
            'currency'  => 'required|string|size:3',
        ]);

        $validated['tenant_id'] = $tenant->id;
        $network = Network::create($validated);

        SystemEvent::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => Auth::id(),
            'action'      => 'Created Network',
            'description' => "Created network: {$network->name} (/{$network->slug})",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Network \"{$network->name}\" created successfully.");
    }

    public function update(Request $request, Network $network)
    {
        $tenant = app('currentTenant');
        if ($network->tenant_id !== $tenant->id) abort(403);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:100|unique:networks,slug,' . $network->id . '|regex:/^[a-z0-9\-]+$/',
            'region_id' => 'required|exists:regions,id',
            'currency'  => 'required|string|size:3',
        ]);

        $network->update($validated);

        SystemEvent::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => Auth::id(),
            'action'      => 'Updated Network',
            'description' => "Updated network: {$network->name}",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Network updated successfully.");
    }

    public function destroy(Network $network)
    {
        $tenant = app('currentTenant');
        if ($network->tenant_id !== $tenant->id) abort(403);

        if ($network->devices()->exists() || $network->offers()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete a network that has devices or offers. Remove them first.']);
        }

        $name = $network->name;
        $network->delete();

        SystemEvent::create([
            'tenant_id'   => $tenant->id,
            'user_id'     => Auth::id(),
            'action'      => 'Deleted Network',
            'description' => "Deleted network: {$name}",
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', "Network \"{$name}\" deleted.");
    }
}
