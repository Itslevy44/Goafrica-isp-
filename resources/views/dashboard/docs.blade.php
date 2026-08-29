@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-blue-900 tracking-tight mb-3">Beginner's Integration Guide</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">Follow these simple, step-by-step instructions to connect your MikroTik router to the billing system using Winbox.</p>
    </div>

    <div class="space-y-8">
        
        <!-- Step 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">1</div>
                    <h2 class="text-2xl font-bold text-slate-800">Log into Winbox</h2>
                </div>
                <div class="text-slate-600 leading-relaxed mb-6">
                    <p>Open the <strong>Winbox</strong> application on your computer. Connect to your MikroTik router using its MAC address or IP address. Make sure you log in as a user with full administrator rights (usually the <code>admin</code> user).</p>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">2</div>
                    <h2 class="text-2xl font-bold text-slate-800">Enable the API Service</h2>
                </div>
                <div class="text-slate-600 leading-relaxed mb-6">
                    <p>The billing system needs to talk to your router to automatically connect and disconnect users when they pay.</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-slate-700">
                        <li>Click on <strong>IP</strong> in the left menu.</li>
                        <li>Select <strong>Services</strong>.</li>
                        <li>Find the service named <code>api</code> in the list.</li>
                        <li>Click on it, and click the blue checkmark (<span class="text-blue-500 font-bold">✓</span>) to enable it.</li>
                        <li>Ensure the Port is set to <strong>8728</strong>.</li>
                    </ul>
                </div>
                
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mt-4">
                    <p class="text-sm font-semibold text-slate-700 mb-2">Terminal Alternative:</p>
                    <code class="block bg-slate-800 text-green-400 p-3 rounded font-mono text-sm">/ip service enable api<br>/ip service set api port=8728</code>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">3</div>
                    <h2 class="text-2xl font-bold text-slate-800">Create a System User</h2>
                </div>
                <div class="text-slate-600 leading-relaxed mb-6">
                    <p>Create a dedicated username and password that the billing system will use to log in. Do not use your personal admin password!</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-slate-700">
                        <li>Click on <strong>System</strong> in the left menu, then <strong>Users</strong>.</li>
                        <li>Click the red <strong>Plus (+)</strong> button to add a new user.</li>
                        <li>Name: <code>billing_api</code> (or whatever you prefer).</li>
                        <li>Group: Select <code>full</code> or <code>write</code>.</li>
                        <li>Password: Type a secure password and confirm it.</li>
                        <li>Click <strong>Apply</strong> and <strong>OK</strong>.</li>
                    </ul>
                </div>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-4">
                    <p class="text-blue-800 font-medium text-sm">Keep this username and password handy. You will need to enter them into the dashboard in Step 6!</p>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">4</div>
                    <h2 class="text-2xl font-bold text-slate-800">Setup the Hotspot</h2>
                </div>
                <div class="text-slate-600 leading-relaxed mb-6">
                    <p>If you haven't already, you need to create the hotspot that your customers will connect to.</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-slate-700">
                        <li>Click on <strong>IP</strong> > <strong>Hotspot</strong>.</li>
                        <li>Go to the <strong>Servers</strong> tab.</li>
                        <li>Click on <strong>Hotspot Setup</strong>.</li>
                        <li>Follow the wizard. Select the interface your customers connect to (like <code>bridge</code>).</li>
                        <li>When asked for a "DNS Name", type something like <code>wifi.local</code>.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">5</div>
                    <h2 class="text-2xl font-bold text-slate-800">Allow M-Pesa Payments (Walled Garden)</h2>
                </div>
                <div class="text-slate-600 leading-relaxed mb-6">
                    <p>Customers need to be able to reach M-Pesa and our billing system <em>before</em> they have paid for internet. This is called a "Walled Garden".</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-slate-700">
                        <li>In Winbox, go to <strong>IP</strong> > <strong>Hotspot</strong>.</li>
                        <li>Click on the <strong>Walled Garden</strong> tab.</li>
                        <li>Click the <strong>Plus (+)</strong> button.</li>
                        <li>In the <strong>Dst. Host</strong> field, type <code>*.safaricom.co.ke</code></li>
                        <li>Click <strong>Apply</strong> and <strong>OK</strong>.</li>
                        <li>Click <strong>Plus (+)</strong> again.</li>
                        <li>In the <strong>Dst. Host</strong> field, type the IP address of this dashboard server (e.g. <code>127.0.0.1</code>).</li>
                        <li>Click <strong>Apply</strong> and <strong>OK</strong>.</li>
                    </ul>
                </div>
                
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mt-4">
                    <p class="text-sm font-semibold text-slate-700 mb-2">Terminal Alternative:</p>
                    <code class="block bg-slate-800 text-green-400 p-3 rounded font-mono text-sm">/ip hotspot walled-garden<br>add dst-host=*.safaricom.co.ke action=allow<br>add dst-host=127.0.0.1 action=allow</code>
                </div>
            </div>
        </div>
        
        <!-- Step 6 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow border-t-4 border-t-emerald-500">
            <div class="p-6 sm:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4 shrink-0 shadow-inner">6</div>
                    <h2 class="text-2xl font-bold text-slate-800">Add Router to Dashboard</h2>
                </div>
                <div class="text-slate-600 leading-relaxed">
                    <p>You are done with Winbox! Now, link the router to the system.</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-slate-700 mb-6">
                        <li>Go to the <a href="{{ route('dashboard.devices.index') }}" class="text-blue-600 hover:underline font-medium">Routers & Devices</a> page on this sidebar.</li>
                        <li>Click the <strong>Add Router</strong> button.</li>
                        <li>Enter your router's Public IP address.</li>
                        <li>Enter the API username (<code>billing_api</code>) and password you created in Step 3.</li>
                        <li>Click Save!</li>
                    </ul>
                    
                    <div class="flex justify-center mt-8">
                        <a href="{{ route('dashboard.devices.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-colors text-lg flex items-center">
                            Go to Routers Page
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
