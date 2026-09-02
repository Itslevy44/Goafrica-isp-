<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Network;
use App\Models\SystemEvent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function destroy(Voucher $voucher)
    {
        $tenant = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();

        if ($voucher->network_id !== $network->id) {
            abort(403);
        }

        if ($voucher->uses_count > 0) {
            return back()->withErrors(['error' => 'Cannot delete a voucher that has been used.']);
        }

        $code = $voucher->code;
        $voucher->delete();

        return back()->with('success', "Voucher {$code} deleted.");
    }

    public function index()
    {
        $tenant = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->first();
        if (!$network) {
            return redirect()->route('dashboard.settings.index')->withErrors(['error' => 'Please set up your network settings first.']);
        }

        $vouchers = Voucher::where('network_id', $network->id)->latest()->get();

        // Search / filter
        $search = request('search');
        $filterStatus = request('filter_status');

        $query = Voucher::where('network_id', $network->id);

        if ($search) {
            $query->where('code', 'LIKE', "%{$search}%");
        }

        if ($filterStatus === 'unused') {
            $query->where('uses_count', 0);
        } elseif ($filterStatus === 'used') {
            $query->whereColumn('uses_count', '>=', 'max_uses');
        } elseif ($filterStatus === 'partial') {
            $query->where('uses_count', '>', 0)->whereColumn('uses_count', '<', 'max_uses');
        }

        $vouchers = $query->latest()->paginate(50)->withQueryString();

        return view('dashboard.vouchers.index', compact('vouchers', 'network'));
    }

    public function store(Request $request)
    {
        $tenant = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();

        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:100',
            'value' => 'required|integer|min:1',
            'type' => 'required|in:time,money',
            'max_uses' => 'required|integer|min:1'
        ]);

        for ($i = 0; $i < $validated['count']; $i++) {
            Voucher::create([
                'network_id' => $network->id,
                'code' => strtoupper(Str::random(8)),
                'type' => $validated['type'],
                'value' => $validated['value'],
                'max_uses' => $validated['max_uses'],
                'created_by' => auth()->id(),
            ]);
        }

        SystemEvent::create([
            'tenant_id' => app('currentTenant')->id ?? null,
            'user_id' => Auth::id(),
            'action' => 'Generated Vouchers',
            'description' => "Generated {$validated['count']} new vouchers of type {$validated['type']}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', $validated['count'] . ' vouchers generated successfully.');
    }

    public function print(Request $request)
    {
        $tenant = app('currentTenant');
        $network = Network::where('tenant_id', $tenant->id)->firstOrFail();
        
        // Print the latest 50 unused vouchers (or could be filtered by request)
        $vouchers = Voucher::where('network_id', $network->id)
            ->where('uses_count', 0)
            ->latest()
            ->limit(50)
            ->get();
            
        SystemEvent::create([
            'tenant_id' => app('currentTenant')->id ?? null,
            'user_id' => Auth::id(),
            'action' => 'Printed Vouchers',
            'description' => "Exported {$vouchers->count()} vouchers for printing",
            'ip_address' => $request->ip(),
        ]);

        return view('dashboard.vouchers.print', compact('vouchers', 'network'));
    }
}
