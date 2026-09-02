<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Offer;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Build the base filtered query (shared between index and export).
     */
    private function buildQuery(Request $request, $tenantId)
    {
        $query = Transaction::with(['offer', 'customer'])
            ->where('tenant_id', $tenantId);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('gateway_ref', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('phone', 'LIKE', "%{$search}%")
                        ->orWhere('mac_address', 'LIKE', "%{$search}%"))
                  ->orWhereHas('offer', fn($oq) => $oq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function export(Request $request): StreamedResponse
    {
        $tenant = app('currentTenant') ?? auth()->user()->tenant;
        $query  = $this->buildQuery($request, $tenant->id)->orderBy('created_at', 'desc');

        $filename = 'transactions_' . now()->format('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['ID', 'Date', 'Time', 'Customer Phone', 'Plan', 'Amount', 'Currency', 'Gateway Ref', 'Status']);

            // Stream rows in chunks to avoid memory issues
            $query->chunk(500, function ($transactions) use ($handle) {
                foreach ($transactions as $txn) {
                    fputcsv($handle, [
                        $txn->id,
                        $txn->created_at->format('Y-m-d'),
                        $txn->created_at->format('H:i:s'),
                        $txn->customer->phone ?? 'N/A',
                        $txn->offer->name ?? 'Voucher',
                        number_format($txn->amount_minor / 100, 2),
                        $txn->currency,
                        $txn->gateway_ref,
                        $txn->status,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
    public function index(Request $request)
    {
        $tenant = app('currentTenant') ?? auth()->user()->tenant;

        // Revenue Metrics
        $totalRevenue = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->sum('amount_minor');

        $thisMonthRevenue = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_minor');

        $query = $this->buildQuery($request, $tenant->id);

        // Filtered revenue sum
        $filteredTotalMinor = (clone $query)->where('status', 'success')->sum('amount_minor');

        // Paginate
        $transactions = $query->orderBy('created_at', 'desc')->paginate(30)->withQueryString();

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
        $usedVouchers  = Voucher::where('tenant_id', $tenant->id)->where('uses_count', '>=', DB::raw('max_uses'))->count();

        return view('dashboard.reports.index', compact(
            'tenant',
            'totalRevenue',
            'thisMonthRevenue',
            'transactions',
            'topOffers',
            'totalVouchers',
            'usedVouchers',
            'filteredTotalMinor'
        ));
    }
}
