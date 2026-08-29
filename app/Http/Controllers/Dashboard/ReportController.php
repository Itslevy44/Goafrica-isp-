<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Offer;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = app('currentTenant');

        // Revenue Metrics
        $totalRevenue = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->sum('amount_minor');

        $thisMonthRevenue = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_minor');

        // Transactions Pagination
        $transactions = Transaction::with(['offer', 'customer'])
            ->where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Top Selling Packages
        $topOffers = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->whereNotNull('offer_id')
            ->select('offer_id', DB::raw('count(*) as total_sales'), DB::raw('sum(amount_minor) as total_revenue'))
            ->groupBy('offer_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->with('offer')
            ->get();

        // Voucher Stats
        $totalVouchers = Voucher::where('tenant_id', $tenant->id)->count();
        $usedVouchers = Voucher::where('tenant_id', $tenant->id)->where('uses_count', '>=', DB::raw('max_uses'))->count();

        return view('dashboard.reports.index', compact(
            'tenant', 
            'totalRevenue', 
            'thisMonthRevenue', 
            'transactions', 
            'topOffers',
            'totalVouchers',
            'usedVouchers'
        ));
    }
}
