<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Network;
use App\Models\Offer;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Create Super Admin
        User::create([
            'tenant_id' => null,
            'name' => 'goAfrica Super Admin',
            'email' => 'levykirui093@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 1. Create a Master Tenant
        $tenant = Tenant::create([
            'name' => 'goAfrica Connect',
            'email' => 'admin@goafrica.local',
            'country' => 'Kenya',
            'default_currency' => 'KES',
        ]);

        // Set global scope context for seeding
        app()->instance('currentTenant', $tenant);

        // 1.5 Create a Region
        $region = \App\Models\Region::create([
            'country_code' => 'KE',
            'name' => 'Kenya',
            'currency' => 'KES',
            'timezone' => 'Africa/Nairobi',
        ]);

        // 2. Create ISP Admin User
        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'email' => 'admin@isp.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Create a Network (Location)
        $network = Network::create([
            'tenant_id' => $tenant->id,
            'region_id' => $region->id,
            'name' => 'Downtown Plaza Free WiFi',
            'slug' => 'downtown-plaza',
            'currency' => 'KES',
        ]);

        // 4. Create Offers
        Offer::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'name' => '1 Hour Pass',
            'duration_minutes' => 60,
            'price_minor' => 2000, // KES 20.00
            'currency' => 'KES',
            'is_active' => true,
        ]);

        Offer::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'name' => '24 Hour Pass',
            'duration_minutes' => 1440,
            'price_minor' => 5000, // KES 50.00
            'currency' => 'KES',
            'is_active' => true,
        ]);

        Offer::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'name' => 'Weekly Pass',
            'duration_minutes' => 10080,
            'price_minor' => 25000, // KES 250.00
            'currency' => 'KES',
            'is_active' => true,
        ]);

        // 5. Create a Mock Device
        Device::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'name' => 'Mock Router A',
            'type' => 'mock',
            'ip_address' => '192.168.88.1',
            'api_port' => 8728,
            'credentials_encrypted' => 'mock',
            'status' => 'online',
        ]);

        // 6. Create some Vouchers
        Voucher::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'code' => 'DEMO1234',
            'type' => 'time',
            'value' => 120, // 2 hours
            'max_uses' => 100,
        ]);
        
        Voucher::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'code' => 'VIPPASS',
            'type' => 'time',
            'value' => 1440, // 24 hours
            'max_uses' => 1,
        ]);
    }
}
