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

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');

// Super Admin Dashboard
Route::group(['prefix' => 'super', 'middleware' => ['auth', 'super_admin']], function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('super.index');
    Route::post('/tenants/{tenant}/extend', [SuperAdminController::class, 'extendSubscription'])->name('super.tenants.extend');
    Route::post('/tenants/{tenant}/suspend', [SuperAdminController::class, 'suspendTenant'])->name('super.tenants.suspend');
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
        
        // Reports
        Route::get('/reports', [App\Http\Controllers\Dashboard\ReportController::class, 'index'])->name('dashboard.reports.index');
        
        // Customers
        Route::get('/customers', [App\Http\Controllers\Dashboard\CustomerController::class, 'index'])->name('dashboard.customers.index');
        Route::post('/customers/{customer}/toggle-ban', [App\Http\Controllers\Dashboard\CustomerController::class, 'toggleBan'])->name('dashboard.customers.toggleBan');
        
        // Settings
        Route::get('/settings', [App\Http\Controllers\Dashboard\SettingsController::class, 'index'])->name('dashboard.settings.index');
        Route::post('/settings', [App\Http\Controllers\Dashboard\SettingsController::class, 'update'])->name('dashboard.settings.update');
        
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
        
        // Vouchers
        Route::get('/vouchers', [App\Http\Controllers\Dashboard\VoucherController::class, 'index'])->name('dashboard.vouchers.index');
        Route::post('/vouchers', [App\Http\Controllers\Dashboard\VoucherController::class, 'store'])->name('dashboard.vouchers.store');
        Route::get('/vouchers/print', [App\Http\Controllers\Dashboard\VoucherController::class, 'print'])->name('dashboard.vouchers.print');
    });
});

// Webhooks
Route::group(['prefix' => 'webhooks'], function () {
    Route::post('/mpesa', [WebhookController::class, 'mpesa'])->name('webhooks.mpesa');
    Route::post('/mpesa/subscription', [WebhookController::class, 'subscription'])->name('webhooks.subscription');
    Route::post('/mock', [WebhookController::class, 'mock'])->name('webhooks.mock');
    Route::get('/mock', [WebhookController::class, 'mock']); // allow GET for easy manual testing
});
