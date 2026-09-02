<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Network;

class OfferController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant') ?? auth()->user()->tenant;
        $network = Network::where('tenant_id', $tenant->id)->first();

        if (!$network) {
            return redirect()->route('dashboard.settings.index')
                ->withErrors(['error' => 'Please set up your network settings first.']);
        }

        $offers = Offer::where('network_id', $network->id)->get();
        return view('dashboard.offers.index', compact('offers', 'network'));
    }

    public function store(Request $request)
    {
        $tenant = app('currentTenant') ?? auth()->user()->tenant;
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'is_multi_device'  => 'nullable|boolean',
            'max_devices'      => 'nullable|integer|min:2|max:50',
        ]);

        $isMulti  = $request->boolean('is_multi_device');
        $maxDevs  = $isMulti ? (int) ($validated['max_devices'] ?? 2) : 1;

        Offer::create([
            'tenant_id'        => $tenant->id,
            'network_id'       => $network->id,
            'name'             => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'price_minor'      => (int) round($validated['price'] * 100),
            'currency'         => $network->currency,
            'is_active'        => true,
            'is_multi_device'  => $isMulti,
            'max_devices'      => $maxDevs,
        ]);

        return back()->with('success', 'Internet plan created successfully.');
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'is_multi_device'  => 'nullable|boolean',
            'max_devices'      => 'nullable|integer|min:2|max:50',
        ]);

        $isMulti = $request->boolean('is_multi_device');
        $maxDevs = $isMulti ? (int) ($validated['max_devices'] ?? 2) : 1;

        $offer->update([
            'name'             => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'price_minor'      => (int) round($validated['price'] * 100),
            'is_multi_device'  => $isMulti,
            'max_devices'      => $maxDevs,
        ]);

        return back()->with('success', 'Internet plan updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return back()->with('success', 'Internet plan deleted.');
    }

    public function toggle(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);
        return back()->with('success', 'Internet plan status updated.');
    }
}
