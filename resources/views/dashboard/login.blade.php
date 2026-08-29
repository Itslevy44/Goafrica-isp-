@extends('layouts.app')

@section('content')
<div class="portal-container glass-panel">
    <div style="text-align: center; margin-bottom: 2rem;">
        <img src="/images/logo.png" alt="Logo" style="height: 60px; border-radius: 12px; margin-bottom: 1rem;">
        <h2>ISP Admin Login</h2>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
