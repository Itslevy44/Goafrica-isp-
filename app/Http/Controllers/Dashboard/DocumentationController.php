<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');
        return view('dashboard.docs', compact('tenant'));
    }

    public function downloadScript()
    {
        $serverIp = config('app.url');
        $script = <<<SCRIPT
# ================================================================
# goAfrica Connect — MikroTik Auto Setup Script
# Generated: {$serverIp}
# ================================================================
# HOW TO USE:
# Option 1: Paste into Winbox > New Terminal
# Option 2: Upload this file to MikroTik Files, then run:
#           /import file-name=goafrica-setup.rsc
# ================================================================

# 1. Enable API service on port 8728
/ip service enable api
/ip service set api port=8728

# 2. Create billing API user (CHANGE THE PASSWORD!)
/user add name=billing_api password=GoAfrica2024! group=full comment="goAfrica Connect billing API"

# 3. Add Walled Garden entries (allow payment sites before login)
/ip hotspot walled-garden add dst-host=*.safaricom.co.ke action=allow comment="MPesa STK Push"
/ip hotspot walled-garden add dst-host=goafrica.site action=allow comment="goAfrica payment portal"
/ip hotspot walled-garden add dst-host=*.goafrica.site action=allow comment="goAfrica portal assets"
/ip hotspot walled-garden add dst-host=fonts.googleapis.com action=allow comment="Portal fonts"
/ip hotspot walled-garden add dst-host=fonts.gstatic.com action=allow comment="Portal fonts"

# 4. (Optional) Enable MikroTik Cloud DDNS for dynamic IP support
# /ip cloud set ddns-enabled=yes
# After enabling, run: /ip cloud print
# Use the "dns-name" value as your router IP in the dashboard

# 5. Print confirmation
:log info "goAfrica Connect setup script completed successfully."
:put ""
:put "============================================"
:put " goAfrica Connect Setup Complete!"
:put "============================================"
:put " API Service:  enabled on port 8728"
:put " API User:     billing_api"
:put " Password:     GoAfrica2024! (CHANGE THIS!)"
:put "============================================"
:put " NEXT STEPS:"
:put " 1. Change the billing_api password:"
:put "    System > Users > billing_api > set password"
:put " 2. Add this router in the goAfrica dashboard:"
:put "    Routers & Devices > Add Router"
:put " 3. Enter this router's public IP, port 8728,"
:put "    username billing_api and your new password"
:put "============================================"
SCRIPT;

        return response($script, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => 'attachment; filename="goafrica-setup.rsc"',
        ]);
    }
}
