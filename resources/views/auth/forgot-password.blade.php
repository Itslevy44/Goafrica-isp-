<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - GoAfrica Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8 m-4">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Forgot Password</h2>
            <p class="text-sm text-slate-500 mt-2">Enter your email and we'll send you a link to reset your password.</p>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm mb-6 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-slate-700 placeholder-slate-400" placeholder="admin@example.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg mt-2">
                Send Password Reset Link
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>
