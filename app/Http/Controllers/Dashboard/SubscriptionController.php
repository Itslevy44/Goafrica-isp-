<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');
        return view('dashboard.subscribe.index', compact('tenant'));
    }

    public function pay(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $tenant = app('currentTenant');
        
        try {
            // SaaS Master Credentials from .env
            $consumerKey = config('services.mpesa.consumer_key');
            $consumerSecret = config('services.mpesa.consumer_secret');
            $passkey = config('services.mpesa.passkey');
            $shortCode = config('services.mpesa.shortcode', '174379');
            $env = config('services.mpesa.env', 'sandbox');
            
            $url = $env === 'production'
                ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
                : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

            $response = Http::withoutVerifying()->timeout(60)->withBasicAuth($consumerKey, $consumerSecret)->get($url);
            
            if (!$response->successful()) {
                Log::error('Mpesa Token Error', ['res' => $response->body()]);
                return back()->withErrors(['error' => 'Could not connect to M-Pesa.']);
            }
            
            $token = $response->json()['access_token'];

            $timestamp = now()->format('YmdHis');
            $password = base64_encode($shortCode . $passkey . $timestamp);
            
            $pushUrl = $env === 'production'
                ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
                : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

            // Format Phone
            $phone = preg_replace('/[^0-9]/', '', $request->phone);
            if (str_starts_with($phone, '0')) $phone = '254' . substr($phone, 1);
            if (str_starts_with($phone, '+')) $phone = substr($phone, 1);

            $payload = [
                'BusinessShortCode' => $shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => 3000,
                'PartyA' => $phone,
                'PartyB' => $shortCode,
                'PhoneNumber' => $phone,
                'CallBackURL' => url('/webhooks/mpesa/subscription?tenant_id=' . $tenant->id),
                'AccountReference' => 'ISP SaaS ' . $tenant->id,
                'TransactionDesc' => 'Monthly ISP Subscription'
            ];

            $pushResponse = Http::withoutVerifying()->timeout(60)->withToken($token)->post($pushUrl, $payload);

            if ($pushResponse->successful()) {
                return back()->with('success', 'STK Push sent to your phone! Please enter your PIN to complete the Ksh 3,000 subscription.');
            }

            Log::error('Subscription STK Push Failed', ['response' => $pushResponse->body()]);
            return back()->withErrors(['error' => 'Failed to initiate payment. Please try again.']);

        } catch (\Exception $e) {
            Log::error('Subscription Error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'An error occurred during payment processing.']);
        }
    }
}
