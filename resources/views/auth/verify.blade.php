<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - GoAfrica Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8 m-4">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Verify Your Email Address</h2>
            <p class="text-sm text-slate-500 mt-2">Before proceeding, please check your email for a verification link.</p>
        </div>

        @if (session('message'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm mb-6 font-medium">
                {{ session('message') }}
            </div>
        @endif

        <p class="text-slate-600 text-sm mb-6 text-center">
            If you did not receive the email, click the button below to request another one.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg">
                Resend Verification Email
            </button>
        </form>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-slate-500 hover:text-slate-800 text-sm font-medium transition-colors">
                Log Out
            </button>
        </form>
    </div>
</body>
</html>
