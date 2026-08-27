<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0d1b2a;
        }
    </style>
</head>

<body class="text-white font-sans antialiased">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <!-- Header & Navigation -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-bold text-white">My Dashboard</h1>
                <p class="text-gray-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <a href="{{ route('home') }}" class="text-blue-400 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to Home
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-[#14213d] rounded-2xl p-6 border border-slate-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Active Rentals</p>
                        <p class="text-3xl font-bold text-blue-400">{{ $stats['active_rentals'] }}</p>
                    </div>
                    <div class="text-4xl text-blue-500 opacity-20">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="bg-[#14213d] rounded-2xl p-6 border border-slate-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Total Borrowed</p>
                        <p class="text-3xl font-bold text-emerald-400">{{ $stats['total_borrowed'] }}</p>
                    </div>
                    <div class="text-4xl text-emerald-500 opacity-20">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-[#14213d] rounded-2xl p-6 border {{ $stats['overdue_books'] > 0 ? 'border-red-500/30' : 'border-slate-800' }} shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Overdue Books</p>
                        <p class="text-3xl font-bold {{ $stats['overdue_books'] > 0 ? 'text-red-400' : 'text-slate-400' }}">{{ $stats['overdue_books'] }}</p>
                    </div>
                    <div class="text-4xl {{ $stats['overdue_books'] > 0 ? 'text-red-500' : 'text-slate-500' }} opacity-20">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Rentals Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-history mr-2 text-blue-400"></i> Currently Borrowing
                </h2>
                <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ $activeRentals->count() }} books
                </span>
            </div>

            @if($activeRentals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeRentals as $rental)
                        @php
                            $daysRemaining = now()->diffInDays($rental->due_date);
                            $isOverdue = $rental->due_date < now();
                            $statusColor = $isOverdue ? 'red' : ($daysRemaining <= 3 ? 'amber' : 'emerald');
                        @endphp
                        <div class="bg-[#14213d] rounded-2xl border border-slate-800 shadow-lg overflow-hidden hover:border-blue-500/50 transition">
                            <div class="h-48 bg-slate-900 overflow-hidden">
                                <img src="{{ $rental->book->image ? asset($rental->book->image) : 'https://via.placeholder.com/300x200' }}"
                                     alt="{{ $rental->book->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-white text-sm mb-1 line-clamp-2">{{ $rental->book->title }}</h3>
                                <p class="text-xs text-gray-400 mb-3">{{ $rental->book->author }}</p>

                                <div class="bg-slate-800 rounded-lg p-3 mb-3 space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-400">Borrowed</span>
                                        <span class="text-xs text-blue-400 font-semibold">
                                            {{ $rental->borrowed_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-400">Due Date</span>
                                        <span class="text-xs {{ $isOverdue ? 'text-red-400' : 'text-emerald-400' }} font-semibold">
                                            {{ $rental->due_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-slate-700">
                                        <span class="text-xs text-gray-400">Days Left</span>
                                        <span class="text-xs font-bold px-2 py-1 rounded {{ $isOverdue ? 'bg-red-500/20 text-red-400' : ($daysRemaining <= 3 ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400') }}">
                                            @if($isOverdue)
                                                Overdue by {{ abs($daysRemaining) }} day(s)
                                            @else
                                                {{ $daysRemaining }} days
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <a href="{{ route('book.detail', $rental->book->id) }}" class="w-full inline-block text-center bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2 rounded-lg transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[#112240] border border-slate-800 rounded-2xl p-12 text-center">
                    <p class="text-gray-400 text-sm mb-4">
                        <i class="fas fa-inbox text-2xl mb-4 block opacity-50"></i>
                        You don't have any active rentals yet.
                    </p>
                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold text-sm transition">
                        <i class="fas fa-search mr-2"></i> Browse Books
                    </a>
                </div>
            @endif
        </div>

        <!-- Personal Recommendations Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-lightbulb mr-2 text-amber-400"></i>
                    {{ $activeRentals->count() > 0 ? 'Based on Your Reading' : 'Trending Books' }}
                </h2>
                <span class="bg-amber-500/20 text-amber-400 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ $recommendations->count() }} suggestions
                </span>
            </div>

            @if($recommendations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($recommendations->take(8) as $book)
                        <div class="bg-[#14213d] rounded-2xl border border-slate-800 shadow-lg overflow-hidden hover:border-amber-500/50 hover:shadow-lg hover:shadow-amber-500/20 transition duration-300">
                            <div class="h-56 bg-slate-900 overflow-hidden">
                                <img src="{{ $book->image ? asset($book->image) : 'https://via.placeholder.com/300x200' }}"
                                     alt="{{ $book->title }}" class="w-full h-full object-cover hover:scale-110 transition duration-300">
                            </div>
                            <div class="p-4">
                                <span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded mb-2 font-semibold">
                                    {{ $book->category }}
                                </span>
                                <h3 class="font-bold text-white text-sm mb-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>

                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-star text-amber-400 text-xs"></i>
                                        <span class="text-xs font-semibold text-amber-400">{{ $book->rating }}/5.0</span>
                                    </div>
                                    <span class="text-xs font-bold text-blue-400">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                </div>

                                <a href="{{ route('book.detail', $book->id) }}" class="w-full inline-block text-center bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white text-xs font-bold py-2 rounded-lg transition">
                                    <i class="fas fa-eye mr-1"></i> View Book
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[#112240] border border-slate-800 rounded-2xl p-12 text-center">
                    <p class="text-gray-400 text-sm">No recommendations available yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer Nav -->
    <div class="fixed bottom-6 right-6">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-3 rounded-full font-semibold text-sm transition shadow-lg">
            <i class="fas fa-home"></i> Home
        </a>
    </div>
</body>

</html>
