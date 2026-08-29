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
        $network = Network::first();
        if (!$network) {
            return redirect()->route('dashboard.settings.index')->withErrors(['error' => 'Please set up your network settings first.']);
        }

        $offers = Offer::where('network_id', $network->id)->get();
        return view('dashboard.offers.index', compact('offers', 'network'));
    }

    public function store(Request $request)
    {
        $network = Network::firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Offer::create([
            'network_id' => $network->id,
            'name' => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'price_minor' => $validated['price'] * 100,
            'currency' => $network->currency,
            'is_active' => true,
        ]);

        return back()->with('success', 'Internet plan created successfully.');
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $offer->update([
            'name' => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'price_minor' => $validated['price'] * 100,
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
