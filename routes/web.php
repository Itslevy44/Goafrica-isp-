<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Webhooks\WebhookController;

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'), ['Content-Type' => 'application/xml']);
});

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /dashboard/\nDisallow: /super/\nDisallow: /connect/\nDisallow: /webhooks/\n\nSitemap: https://goafrica.site/sitemap.xml\n";
    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/google42d4598fe1c93dcd.html', function () {
    return response('google-site-verification: google42d4598fe1c93dcd.html', 200)
        ->header('Content-Type', 'text/html');
});

// Public support contact form
Route::post('/contact', [App\Http\Controllers\SupportController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,1');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');

// Email Verification Routes
// NOTE: verification.notice requires auth (user sees "please verify" page)
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->middleware(['auth'])->name('verification.notice');
// Public pending page shown after blocked login attempt (user is NOT authenticated)
Route::get('/email/pending', [App\Http\Controllers\Auth\VerificationController::class, 'pending'])->name('verification.pending');
// NOTE: verification.verify does NOT require auth so the link works from any device (phone, etc.)
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
// Public resend for unauthenticated pending page (user submits email to resend)
Route::post('/email/resend-pending', [App\Http\Controllers\Auth\VerificationController::class, 'resendPending'])->middleware(['throttle:3,1'])->name('verification.resend.pending');

// Super Admin Dashboard
Route::group(['prefix' => 'super', 'middleware' => ['auth', 'super_admin']], function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('super.index');
    Route::post('/tenants/{tenant}/extend', [SuperAdminController::class, 'extendSubscription'])->name('super.tenants.extend');
    Route::post('/tenants/{tenant}/suspend', [SuperAdminController::class, 'suspendTenant'])->name('super.tenants.suspend');
    Route::post('/tenants/{tenant}/activate', [SuperAdminController::class, 'activateTenant'])->name('super.tenants.activate');
    Route::post('/tenants/{tenant}/impersonate', [SuperAdminController::class, 'impersonate'])->name('super.tenants.impersonate');
    Route::delete('/tenants/{tenant}', [SuperAdminController::class, 'deleteTenant'])->name('super.tenants.delete');
    Route::get('/bulk-email', [SuperAdminController::class, 'bulkEmailForm'])->name('super.bulk-email');
    Route::post('/bulk-email', [SuperAdminController::class, 'sendBulkEmail'])->name('super.bulk-email.send');
    // Support Tickets
    Route::get('/tickets', [App\Http\Controllers\SuperAdmin\SupportTicketController::class, 'index'])->name('super.tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\SuperAdmin\SupportTicketController::class, 'show'])->name('super.tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\SuperAdmin\SupportTicketController::class, 'reply'])->name('super.tickets.reply');
    Route::delete('/tickets/{ticket}', [App\Http\Controllers\SuperAdmin\SupportTicketController::class, 'destroy'])->name('super.tickets.destroy');
});

// Captive Portal Routes
Route::group(['prefix' => 'connect'], function () {
    Route::get('/{network_slug}', [PortalController::class, 'index'])->name('portal.index');
    Route::post('/{network_slug}/purchase', [PortalController::class, 'purchase'])->name('portal.purchase');
    Route::post('/{network_slug}/redeem', [PortalController::class, 'redeemVoucher'])->name('portal.redeem');
});

// ISP Admin Dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/subscribe', [App\Http\Controllers\Dashboard\SubscriptionController::class, 'index'])->name('dashboard.subscribe.index');
        Route::post('/subscribe/pay', [App\Http\Controllers\Dashboard\SubscriptionController::class, 'pay'])->name('dashboard.subscribe.pay');
    });

    Route::middleware(['auth', 'check.subscription'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/cmd', [DashboardController::class, 'cmd'])->name('dashboard.cmd');
        Route::post('/cmd', [DashboardController::class, 'runCmd'])->name('dashboard.runCmd');
        Route::get('/docs', [App\Http\Controllers\Dashboard\DocumentationController::class, 'index'])->name('dashboard.docs');
        
        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Dashboard\NotificationController::class, 'index'])->name('dashboard.notifications.index');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Dashboard\NotificationController::class, 'markRead'])->name('dashboard.notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\Dashboard\NotificationController::class, 'readAll'])->name('dashboard.notifications.readAll');

        // Reports
        Route::get('/reports', [App\Http\Controllers\Dashboard\ReportController::class, 'index'])->name('dashboard.reports.index');
        Route::get('/reports/export', [App\Http\Controllers\Dashboard\ReportController::class, 'export'])->name('dashboard.reports.export');

        // Receipts (Invoice / PDF download)
        Route::get('/receipts/{transaction}', [App\Http\Controllers\Dashboard\ReceiptController::class, 'show'])->name('dashboard.receipts.show');

        // Customers
        Route::get('/customers', [App\Http\Controllers\Dashboard\CustomerController::class, 'index'])->name('dashboard.customers.index');
        Route::get('/customers/{customer}', [App\Http\Controllers\Dashboard\CustomerController::class, 'show'])->name('dashboard.customers.show');
        Route::post('/customers/{customer}/toggle-ban', [App\Http\Controllers\Dashboard\CustomerController::class, 'toggleBan'])->name('dashboard.customers.toggleBan');

        // Active Sessions
        Route::get('/sessions', [App\Http\Controllers\Dashboard\SessionController::class, 'index'])->name('dashboard.sessions.index');
        Route::post('/sessions/{session}/kick', [App\Http\Controllers\Dashboard\SessionController::class, 'kick'])->name('dashboard.sessions.kick');

        // Wallet & Payouts
        Route::get('/wallet', [App\Http\Controllers\Dashboard\WalletController::class, 'index'])->name('dashboard.wallet.index');
        Route::post('/wallet/payout', [App\Http\Controllers\Dashboard\WalletController::class, 'requestPayout'])->name('dashboard.wallet.payout');
        Route::post('/wallet/payout-account', [App\Http\Controllers\Dashboard\WalletController::class, 'savePayoutAccount'])->name('dashboard.wallet.payout-account');

        // Settings
        Route::get('/settings', [App\Http\Controllers\Dashboard\SettingsController::class, 'index'])->name('dashboard.settings.index');
        Route::post('/settings/profile', [App\Http\Controllers\Dashboard\SettingsController::class, 'updateProfile'])->name('dashboard.settings.profile');
        Route::post('/settings/staff', [App\Http\Controllers\Dashboard\SettingsController::class, 'storeStaff'])->name('dashboard.settings.staff.store');
        Route::delete('/settings/staff/{user}', [App\Http\Controllers\Dashboard\SettingsController::class, 'destroyStaff'])->name('dashboard.settings.staff.destroy');
        Route::put('/settings/staff/{user}', [App\Http\Controllers\Dashboard\SettingsController::class, 'updateStaff'])->name('dashboard.settings.staff.update');

        // Devices
        Route::get('/devices', [App\Http\Controllers\Dashboard\DeviceController::class, 'index'])->name('dashboard.devices.index');
        Route::post('/devices', [App\Http\Controllers\Dashboard\DeviceController::class, 'store'])->name('dashboard.devices.store');
        Route::put('/devices/{device}', [App\Http\Controllers\Dashboard\DeviceController::class, 'update'])->name('dashboard.devices.update');
        Route::delete('/devices/{device}', [App\Http\Controllers\Dashboard\DeviceController::class, 'destroy'])->name('dashboard.devices.destroy');

        // Offers
        Route::get('/offers', [App\Http\Controllers\Dashboard\OfferController::class, 'index'])->name('dashboard.offers.index');
        Route::post('/offers', [App\Http\Controllers\Dashboard\OfferController::class, 'store'])->name('dashboard.offers.store');
        Route::put('/offers/{offer}', [App\Http\Controllers\Dashboard\OfferController::class, 'update'])->name('dashboard.offers.update');
        Route::delete('/offers/{offer}', [App\Http\Controllers\Dashboard\OfferController::class, 'destroy'])->name('dashboard.offers.destroy');
        Route::post('/offers/{offer}/toggle', [App\Http\Controllers\Dashboard\OfferController::class, 'toggle'])->name('dashboard.offers.toggle');

        // Networks (Multiple networks per tenant)
        Route::get('/networks', [App\Http\Controllers\Dashboard\NetworkController::class, 'index'])->name('dashboard.networks.index');
        Route::post('/networks', [App\Http\Controllers\Dashboard\NetworkController::class, 'store'])->name('dashboard.networks.store');
        Route::put('/networks/{network}', [App\Http\Controllers\Dashboard\NetworkController::class, 'update'])->name('dashboard.networks.update');
        Route::delete('/networks/{network}', [App\Http\Controllers\Dashboard\NetworkController::class, 'destroy'])->name('dashboard.networks.destroy');

        // Vouchers
        Route::get('/vouchers', [App\Http\Controllers\Dashboard\VoucherController::class, 'index'])->name('dashboard.vouchers.index');
        Route::post('/vouchers', [App\Http\Controllers\Dashboard\VoucherController::class, 'store'])->name('dashboard.vouchers.store');
        Route::get('/vouchers/print', [App\Http\Controllers\Dashboard\VoucherController::class, 'print'])->name('dashboard.vouchers.print');
        Route::delete('/vouchers/{voucher}', [App\Http\Controllers\Dashboard\VoucherController::class, 'destroy'])->name('dashboard.vouchers.destroy');
    });
});

// Webhooks
Route::group(['prefix' => 'webhooks'], function () {
    Route::post('/mpesa', [WebhookController::class, 'mpesa'])->name('webhooks.mpesa');
    Route::post('/mpesa/subscription', [WebhookController::class, 'subscription'])->name('webhooks.subscription');
    Route::post('/mock', [WebhookController::class, 'mock'])->name('webhooks.mock');
    Route::get('/mock', [WebhookController::class, 'mock']); // allow GET for easy manual testing
});
