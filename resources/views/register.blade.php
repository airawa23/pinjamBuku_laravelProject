<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-slate-800 text-white flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-slate-900/90 border border-slate-700 shadow-2xl rounded-3xl p-8 backdrop-blur-xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 text-white shadow-lg mb-4">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Create your account</h1>
            <p class="text-sm text-slate-400 mt-2">Register now and start tracking books.</p>
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

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300">Full Name</label>
                <input id="name" name="name" type="text" required placeholder="Your name" value="{{ old('name') }}"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                <input id="email" name="email" type="email" required placeholder="you@example.com" value="{{ old('email') }}"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                <input id="password" name="password" type="password" required placeholder="Create a password"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Repeat your password"
                    class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" />
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-300">Choose Role</label>
                <div class="mt-2 space-y-3">
                    <label class="flex items-center p-3 border border-slate-700 rounded-2xl bg-slate-950/70 cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="role" value="renter" required {{ old('role') == 'renter' ? 'checked' : '' }} class="w-4 h-4">
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-white">Renter</p>
                            <p class="text-xs text-slate-400">Only can browse and rent books</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-slate-700 rounded-2xl bg-slate-950/70 cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="role" value="owner" required {{ old('role') == 'owner' ? 'checked' : '' }} class="w-4 h-4">
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-white">Owner</p>
                            <p class="text-xs text-slate-400">Can upload and manage books</p>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-500/20 transition hover:brightness-105">
                Register
            </button>
        </form>

        <div class="border-t border-slate-700 pt-6 mt-8 text-center text-sm text-slate-500">
            <p>Already have an account? <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-200">Sign in</a></p>
            <p class="mt-3">
                <a href="{{ route('home') }}" class="text-slate-300 hover:text-white">Back to Home</a>
            </p>
        </div>
    </div>
</body>
</html>
