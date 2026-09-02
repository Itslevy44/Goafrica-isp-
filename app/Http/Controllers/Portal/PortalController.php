<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\Offer;
use App\Services\Billing\BillingService;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request, $network_slug)
    {
        $network = Network::where('slug', $network_slug)->firstOrFail();
        $offers = Offer::where('network_id', $network->id)
            ->where('is_active', true)
            ->orderBy('price_minor', 'asc')
            ->get();
        
        $mac = $request->query('mac');
        $ip = $request->query('ip');

        return view('portal.index', compact('network', 'offers', 'mac', 'ip'));
    }

    public function purchase(Request $request, $network_slug, BillingService $billingService)
    {
        $network = Network::where('slug', $network_slug)->firstOrFail();
        
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'phone' => 'required|string',
            'mac' => 'required|string',
            'ip' => 'nullable|string',
        ]);

        $offer = Offer::findOrFail($request->offer_id);
        $gatewayName = env('USE_MOCK_PAYMENT', false) ? 'mock' : 'mpesa';

        $result = $billingService->initiatePurchase(
            $network, 
            $offer, 
            $request->phone, 
            $gatewayName, 
            $request->mac, 
            $request->ip
        );

        if ($result['success']) {
            return back()->with('success', 'Payment initiated. Please check your phone and enter your PIN to complete the purchase.');
        }

        return back()->withErrors(['payment_error' => $result['message']])->withInput();
    }

    public function redeemVoucher(Request $request, $network_slug, BillingService $billingService)
    {
        $network = Network::where('slug', $network_slug)->firstOrFail();

        $request->validate([
            'voucher_code' => 'required|string',
            'phone' => 'required|string',
            'mac' => 'required|string',
            'ip' => 'nullable|string',
        ]);

        try {
            $result = $billingService->redeemVoucher(
                $network,
                $request->phone,
                $request->voucher_code,
                $request->mac,
                $request->ip
            );
            return back()->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->withErrors(['voucher_error' => $e->getMessage()])->withInput();
        }
    }
}
