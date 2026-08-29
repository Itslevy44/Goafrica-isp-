@extends('layouts.app')

@section('content')
<div class="portal-container glass-panel" style="max-width: 600px;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <img src="/images/logo.png" alt="Logo" style="height: 60px; border-radius: 12px; margin-bottom: 1rem;">
        <h2>Register your ISP</h2>
        <p>Join the goAfrica Connect network and start monetizing your WiFi.</p>
    </div>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="isp_name">ISP / Business Name</label>
                <input type="text" id="isp_name" name="isp_name" class="form-control" required value="{{ old('isp_name') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="country">Country</label>
                <select id="country" name="country" class="form-control" required>
                    <option value="Kenya">Kenya</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Uganda">Uganda</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="admin_name">Admin Full Name</label>
            <input type="text" id="admin_name" name="admin_name" class="form-control" required value="{{ old('admin_name') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Admin Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create ISP Account</button>
        
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('login') }}" style="color: var(--text-secondary);">Already have an account? Login here.</a>
        </div>
    </form>
</div>
@endsection
