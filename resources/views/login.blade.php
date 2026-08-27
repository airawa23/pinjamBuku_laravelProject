<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-slate-800 text-white flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-slate-900/90 border border-slate-700 shadow-2xl rounded-3xl p-8 backdrop-blur-xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-500 text-white shadow-lg mb-4">
                <i class="fas fa-book-open text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Welcome Back</h1>
            <p class="text-sm text-slate-400 mt-2">Sign in to continue to PinjamBuku.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-500/30 bg-red-500/10 p-4 mb-6 text-sm text-red-100">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                <input id="email" name="email" type="email" required placeholder="you@example.com" value="{{ old('email') }}"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/30" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                <input id="password" name="password" type="password" required placeholder="Enter your password"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/30" />
            </div>

            <div class="flex items-center justify-between text-sm text-slate-400">
                <label class="inline-flex items-center gap-2">
                    <input name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-sky-500 focus:ring-sky-500">
                    Remember me
                </label>
                <a href="#" class="text-sky-400 hover:text-sky-200">Forgot password?</a>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-indigo-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-sky-500/20 transition hover:brightness-105">
                Sign In
            </button>
        </form>

        <div class="border-t border-slate-700 pt-6 mt-8 text-center text-sm text-slate-500">
            <p>Don’t have an account? <a href="{{ route('register') }}" class="text-sky-400 hover:text-sky-200">Create one</a></p>
            <p class="mt-3">
                <a href="{{ route('home') }}" class="text-slate-300 hover:text-white">Back to Home</a>
            </p>
        </div>
    </div>
</body>
</html>
