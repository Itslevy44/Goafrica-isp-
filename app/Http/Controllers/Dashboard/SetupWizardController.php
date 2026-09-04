<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Network;
use App\Models\Offer;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SetupWizardController extends Controller
{
    public function index()
    {
        $tenant  = app('currentTenant');
        $regions = Region::all();

        // Figure out which steps are done
        $network  = Network::where('tenant_id', $tenant->id)->first();
        $device   = $network ? Device::where('network_id', $network->id)->first() : null;
        $hasOffer = $network ? Offer::where('network_id', $network->id)->exists() : false;

        $currentStep = 1;
        if ($network) $currentStep = 2;
        if ($network && $device) $currentStep = 3;
        if ($network && $device && $hasOffer) $currentStep = 4;

        return view('dashboard.setup.index', compact('tenant', 'regions', 'network', 'device', 'hasOffer', 'currentStep'));
    }

    /**
     * Step 1 — Save network identity.
     */
    public function saveNetwork(Request $request)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:100|unique:networks,slug|regex:/^[a-z0-9\-]+$/',
            'region_id' => 'required|exists:regions,id',
            'currency'  => 'required|string|size:3',
        ]);

        $validated['tenant_id'] = $tenant->id;
        Network::create($validated);

        return redirect()->route('dashboard.setup.index', ['step' => 2])
            ->with('success', '✓ Network created! Now add your MikroTik router.');
    }

    /**
     * Step 2 — Save router.
     */
    public function saveRouter(Request $request)
    {
        $tenant  = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'ip_address' => 'required|string|max:100',
            'api_port'   => 'required|integer|min:1|max:65535',
            'username'   => 'required|string|max:255',
            'password'   => 'required|string',
        ]);

        $device = Device::create([
            'tenant_id'             => $tenant->id,
            'network_id'            => $network->id,
            'name'                  => $validated['name'],
            'ip_address'            => $validated['ip_address'],
            'api_port'              => $validated['api_port'],
            'credentials_encrypted' => Crypt::encryptString(json_encode([
                'username' => $validated['username'],
                'password' => $validated['password'],
            ])),
            'type'   => 'mikrotik',
            'status' => 'unknown',
        ]);

        // Try a quick connection test
        $connected = false;
        $testMessage = '';
        try {
            $billingService = app(\App\Services\Billing\BillingService::class);
            $billingService->resolveDeviceDriver($device);
            $device->update(['status' => 'active', 'last_seen_at' => now()]);
            $connected = true;
            $testMessage = '✓ Router connected successfully!';
        } catch (\Exception $e) {
            $device->update(['status' => 'offline']);
            $testMessage = '⚠ Router saved but connection failed: ' . $e->getMessage() . '. You can retry from the Devices page.';
        }

        return redirect()->route('dashboard.setup.index', ['step' => 3])
            ->with($connected ? 'success' : 'warning', $testMessage);
    }

    /**
     * Step 3 — Save default internet plans.
     */
    public function saveOffers(Request $request)
    {
        $tenant  = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();

        // Create the predefined starter plans
        $starterPlans = [
            ['name' => '1 Hour',  'duration_minutes' => 60,    'price_minor' => 5000],
            ['name' => 'Daily',   'duration_minutes' => 1440,  'price_minor' => 10000],
            ['name' => 'Weekly',  'duration_minutes' => 10080, 'price_minor' => 50000],
            ['name' => 'Monthly', 'duration_minutes' => 43200, 'price_minor' => 150000],
        ];

        // Allow custom plans if submitted
        if ($request->filled('plans')) {
            foreach ($request->input('plans') as $plan) {
                if (empty($plan['name']) || empty($plan['price'])) continue;
                Offer::create([
                    'tenant_id'        => $tenant->id,
                    'network_id'       => $network->id,
                    'name'             => $plan['name'],
                    'duration_minutes' => (int)($plan['duration_minutes'] ?? 60),
                    'price_minor'      => (int)(floatval($plan['price']) * 100),
                    'currency'         => $network->currency,
                    'is_active'        => true,
                ]);
            }
        } else {
            // Use starter plans
            foreach ($starterPlans as $plan) {
                Offer::create([
                    'tenant_id'        => $tenant->id,
                    'network_id'       => $network->id,
                    'name'             => $plan['name'],
                    'duration_minutes' => $plan['duration_minutes'],
                    'price_minor'      => $plan['price_minor'],
                    'currency'         => $network->currency,
                    'is_active'        => true,
                ]);
            }
        }

        return redirect()->route('dashboard.setup.index', ['step' => 4])
            ->with('success', '✓ Internet plans created! Your network is ready.');
    }

    /**
     * Step 4 — Mark setup as complete and go to dashboard.
     */
    public function complete(Request $request)
    {
        return redirect()->route('dashboard.index')
            ->with('success', '🎉 Setup complete! Your hotspot network is live and ready to accept payments.');
    }
}
