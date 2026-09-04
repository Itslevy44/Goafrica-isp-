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
        $script = <<<'SCRIPT'
# ================================================================
# goAfrica Connect — Full MikroTik Setup Script
# Version: 2.0
# ================================================================
# NETWORK TOPOLOGY THIS SCRIPT ASSUMES:
#
#   [Internet / Home Router]
#          |
#     [ether1 - WAN]
#     [MikroTik]       <-- This device (runs hotspot + billing)
#     [ether2 - LAN]
#          |
#       [Switch]
#      /   |   \
#    [AP1][AP2][AP3]   <-- Access points (set as bridge, NOT router)
#
# HOW TO USE:
#   Option 1: Paste directly into Winbox > New Terminal
#   Option 2: Upload this file to MikroTik Files, then run:
#             /import file-name=goafrica-setup.rsc
# ================================================================

# ---- STEP 1: WAN Interface Setup (ether1 gets IP from home router) ----
/ip dhcp-client add interface=ether1 disabled=no comment="WAN - from home router"

# ---- STEP 2: LAN Bridge (ether2 + ether3 + ether4 + ether5 go to switch) ----
/interface bridge add name=bridge-lan comment="LAN Bridge"
/interface bridge port add bridge=bridge-lan interface=ether2
/interface bridge port add bridge=bridge-lan interface=ether3
/interface bridge port add bridge=bridge-lan interface=ether4
/interface bridge port add bridge=bridge-lan interface=ether5

# Set LAN bridge IP (customers will be on 192.168.88.0/24)
/ip address add address=192.168.88.1/24 interface=bridge-lan comment="LAN Gateway"

# ---- STEP 3: NAT (masquerade outbound traffic from LAN to WAN) ----
/ip firewall nat add chain=srcnat out-interface=ether1 action=masquerade comment="NAT - LAN to WAN"

# ---- STEP 4: DHCP Server (assigns IPs to hotspot customers) ----
/ip pool add name=hotspot-pool ranges=192.168.88.10-192.168.88.254
/ip dhcp-server add name=hotspot-dhcp interface=bridge-lan address-pool=hotspot-pool disabled=no
/ip dhcp-server network add address=192.168.88.0/24 gateway=192.168.88.1 dns-server=8.8.8.8,8.8.4.4

# ---- STEP 5: DNS ----
/ip dns set servers=8.8.8.8,8.8.4.4 allow-remote-requests=yes

# ---- STEP 6: Enable API Service (for goAfrica billing integration) ----
/ip service enable api
/ip service set api port=8728

# ---- STEP 7: Create Billing API User ----
# IMPORTANT: Change "GoAfrica2024!" to a strong password of your choice!
/user add name=billing_api password=GoAfrica2024! group=full comment="goAfrica Connect billing API"

# ---- STEP 8: Hotspot Setup ----
# This creates a basic hotspot on the bridge-lan interface
/ip hotspot setup
# NOTE: When the setup wizard asks:
#   - Hotspot Interface: bridge-lan
#   - DNS Name: leave blank or type wifi.local
#   - Certificate: none
#   Just press Next/Enter through each step

# ---- STEP 9: Walled Garden (allow M-Pesa + goAfrica BEFORE payment) ----
/ip hotspot walled-garden add dst-host=*.safaricom.co.ke action=allow comment="MPesa STK Push"
/ip hotspot walled-garden add dst-host=goafrica.site action=allow comment="goAfrica portal"
/ip hotspot walled-garden add dst-host=*.goafrica.site action=allow comment="goAfrica assets"
/ip hotspot walled-garden add dst-host=fonts.googleapis.com action=allow comment="Portal fonts"
/ip hotspot walled-garden add dst-host=fonts.gstatic.com action=allow comment="Portal fonts"
/ip hotspot walled-garden add dst-host=cdn.tailwindcss.com action=allow comment="Portal styles"

# ---- STEP 10: MikroTik Cloud DDNS ----
# This gives your router a FREE public hostname even if your IP changes.
# After running this, use the dns-name value in your goAfrica dashboard.
/ip cloud set ddns-enabled=yes

# ---- STEP 11: Firewall Basics ----
/ip firewall filter add chain=input protocol=tcp dst-port=8728 action=accept comment="Allow goAfrica API"
/ip firewall filter add chain=input connection-state=established,related action=accept comment="Allow established"
/ip firewall filter add chain=forward connection-state=established,related action=accept comment="Forward established"
/ip firewall filter add chain=forward in-interface=bridge-lan out-interface=ether1 action=accept comment="LAN to WAN"

# ---- DONE! Print your Cloud DDNS hostname ----
:delay 3s
/ip cloud print

:put ""
:put "================================================================"
:put " goAfrica Connect — Setup Complete!"
:put "================================================================"
:put ""
:put " Your MikroTik Cloud hostname is shown above."
:put " Look for: dns-name = XXXX.sn.mynetname.net"
:put ""
:put " Use that hostname (not an IP) when adding your router"
:put " in the goAfrica dashboard > Routers & Devices > Add Router"
:put ""
:put " API Port:    8728"
:put " API User:    billing_api"
:put " Password:    GoAfrica2024!  <-- CHANGE THIS NOW!"
:put ""
:put " To change password: System > Users > billing_api > set password"
:put "================================================================"
SCRIPT;

        return response($script, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => 'attachment; filename="goafrica-full-setup.rsc"',
        ]);
    }
}
