<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinjamBuku - Track & Explore Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0d1b2a;
        }
    </style>
</head>

<body id="top" class="text-white font-sans antialiased">

    <nav class="sticky top-0 z-50 bg-[#0a192f] shadow-lg px-4 py-4 sm:px-6 sm:py-5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-xl font-bold italic tracking-wide">PinjamBuku</span>
                <button id="mobile-menu-button" class="md:hidden inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900/80 p-2 text-slate-200 hover:bg-slate-800 transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-sm">
                <a href="#top" class="nav-link text-white font-semibold border-b-2 border-white pb-1">Home</a>
                <a href="#about" class="nav-link text-gray-300 border-b-2 border-transparent pb-1 hover:text-white hover:border-white transition">About</a>
                <a href="#categories" class="nav-link text-gray-300 border-b-2 border-transparent pb-1 hover:text-white hover:border-white transition">Categories</a>
                <a href="#trending" class="nav-link text-gray-300 border-b-2 border-transparent pb-1 hover:text-white hover:border-white transition">Trending</a>
                <a href="#books" class="nav-link text-gray-300 border-b-2 border-transparent pb-1 hover:text-white hover:border-white transition">Books</a>
                <a href="#faq" class="nav-link text-gray-300 border-b-2 border-transparent pb-1 hover:text-white hover:border-white transition">FAQ</a>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-sm">
                @if(Auth::check() && Auth::user()->role === 'renter')
                <a href="{{ route('cart.index') }}" class="relative text-gray-300 hover:text-white">
                    <i class="fas fa-shopping-cart"></i>
                    @if(!empty($cartCount) && $cartCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center h-5 min-w-5 rounded-full bg-red-500 text-[10px] font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </a>
                @endif
                {{-- KONDISI 1: JIKA USER BELUM LOGIN (GUEST) --}}
                @guest
                <a href="{{ route('register') }}" class="bg-white text-[#0a192f] px-4 py-1.5 rounded-md font-semibold hover:bg-gray-200 transition">Sign Up</a>
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-slate-700 to-slate-600 border border-slate-500 px-4 py-1.5 rounded-md font-semibold hover:brightness-110 transition shadow-inner">Sign in</a>
                @endguest

                {{-- KONDISI 2: JIKA USER SUDAH LOGIN (AUTH) --}}
                @auth
                <div class="relative">
                    <button id="user-menu-button" type="button" class="flex items-center space-x-3 bg-[#172a45] px-4 py-1.5 rounded-full border border-blue-500/20 hover:bg-[#1e355a] transition text-sm text-gray-200">
                        <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="font-semibold">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>

                    <div id="user-menu-dropdown" class="absolute right-0 top-14 w-44 bg-[#172a45] border border-blue-500/20 rounded-lg shadow-xl hidden overflow-hidden z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-300 hover:bg-blue-600 hover:text-white transition">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <hr class="border-blue-900/40">
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-red-600/10 hover:text-red-300 transition flex items-center font-medium">
                                <i class="fas fa-sign-out-alt mr-2 text-[11px]"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
    </nav>

    <div id="mobile-menu" class="md:hidden hidden bg-[#0a192f] border-t border-slate-800">
        <div class="space-y-3 px-4 py-4 text-sm">
            <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2">
                @if(!empty($selectedCategory))
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search books..." class="min-w-0 flex-1 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white placeholder:text-slate-500 focus:border-blue-500 focus:outline-none">
                <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-500">Go</button>
            </form>
            <a href="#top" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">Home</a>
            <a href="#about" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">About</a>
            <a href="#categories" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">Categories</a>
            <a href="#trending" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">Trending</a>
            <a href="#books" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">Books</a>
            <a href="#faq" class="block nav-link text-gray-300 rounded-lg px-3 py-2 hover:bg-slate-800 hover:text-white">FAQ</a>
            @if(Auth::check() && Auth::user()->role === 'renter')
            <a href="{{ route('cart.index') }}" class="flex items-center justify-between rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-gray-300 hover:bg-slate-800 hover:text-white">
                <span>Cart</span>
                <i class="fas fa-shopping-cart text-xs"></i>
            </a>
            @endif
            @guest
            <a href="{{ route('register') }}" class="block rounded-lg bg-white text-[#0a192f] px-3 py-2 font-semibold text-center hover:bg-gray-200">Sign Up</a>
            <a href="{{ route('login') }}" class="block rounded-lg bg-slate-700 text-white px-3 py-2 font-semibold text-center hover:bg-slate-600">Sign in</a>
            @endguest
            @auth
            <div class="rounded-2xl border border-slate-800 bg-[#172a45] p-3">
                <p class="text-xs text-slate-200 font-semibold">Signed in as</p>
                <p class="mt-1 text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                <a href="#" class="mt-3 block rounded-lg bg-slate-800 px-3 py-2 text-center text-xs text-slate-200 hover:bg-slate-700">Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-500">Sign Out</button>
                </form>
            </div>
            @endauth
        </div>
    </div>

    @if(session('success'))
    <div id="flash-notice" class="max-w-6xl mx-auto px-6 py-4">
        <div class="rounded-3xl bg-emerald-500/10 border border-emerald-500 text-emerald-100 px-6 py-4 shadow-lg">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-xs font-semibold">OK</span>
                    <p class="text-sm text-emerald-100">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('flash-notice')?.remove();" class="text-emerald-100/80 hover:text-white text-sm">Close</button>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($selectedCategory) || !empty($search))
    <div id="selected-category-banner" class="max-w-6xl mx-auto px-6 mb-6">
        <div class="rounded-3xl bg-slate-900/90 border border-slate-800 px-6 py-4 text-slate-200 shadow-lg">
            @if(!empty($selectedCategory))
            Showing category: <span class="font-semibold text-white">{{ $selectedCategory }}</span>
            @if(!empty($search))
            <span class="mx-2 text-slate-500">|</span>
            @endif
            @endif
            @if(!empty($search))
            Search: <span class="font-semibold text-white">{{ $search }}</span>
            @endif
        </div>
    </div>
    @endif

    <div class="relative h-[500px] bg-cover bg-center flex flex-col justify-center items-center text-center px-4"
        style="background-image: linear-gradient(rgba(10, 25, 47, 0.7), rgba(13, 27, 42, 0.95)), url('{{ asset('images/library.jpg') }}');">

        <h1 class="text-3xl md:text-4xl font-bold tracking-wide leading-relaxed max-w-2xl">
            Track books you've read.<br>
            Save those you want to explore.<br>
            Tell your friends what's good.
        </h1>

        <form action="{{ route('home') }}" method="GET" class="mt-8 w-full max-w-3xl px-4 sm:px-0">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @if(!empty($selectedCategory))
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search books by title or author..." class="min-w-0 flex-1 rounded-full border border-slate-700 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-blue-500 focus:bg-slate-900 focus:outline-none">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500 transition">Search</button>
            </div>
        </form>

        <button class="mt-6 bg-white text-[#0a192f] px-6 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-gray-100 transition tracking-wider">
            <a href="#about" class="text-[#0a192f] hover:text-gray-700">Get Started!</a>
        </button>
    </div>

    <section id="about" class="py-16 bg-slate-900 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center ">
            <span class="text-xs font-semibold tracking-widest text-indigo-400 uppercase">Get to know PinjamBuku</span>
            <h2 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-100">
                no need to carry books around, just keep them in your pocket.
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-400">
                In PinjamBuku, all your reading references are organized in one place. You can get great recommendations, create your own collection list, and even tag books you want to read next. Perfect for students, corporate professionals, or anyone who loves to read.
            </p>
        </div>
    </section>



    <div class="max-w-6xl mx-auto px-6 mt-16 relative z-10 grid grid-cols-2 md:grid-cols-4 gap-6">

        <div class="bg-gradient-to-b from-white via-[#f0f4f8] to-[#bcd2ee] text-[#0a192f] rounded-xl p-5 text-center shadow-2xl border border-white/40 transform hover:-translate-y-1 transition duration-300">
            <p class="text-slate-600 font-bold text-xs uppercase tracking-wider">Total Books</p>
            <p class="text-4xl font-extrabold mt-2 tracking-tight bg-gradient-to-b from-slate-900 to-blue-900 bg-clip-text text-transparent">{{ $stats['total_books'] }}</p>
        </div>

        <div class="bg-gradient-to-b from-white via-[#f0f4f8] to-[#bcd2ee] text-[#0a192f] rounded-xl p-5 text-center shadow-2xl border border-white/40 transform hover:-translate-y-1 transition duration-300">
            <p class="text-slate-600 font-bold text-xs uppercase tracking-wider">Active Users</p>
            <p class="text-4xl font-extrabold mt-2 tracking-tight bg-gradient-to-b from-slate-900 to-blue-900 bg-clip-text text-transparent">{{ $stats['active_users'] }}</p>
        </div>

        <div class="bg-gradient-to-b from-[#1b263b] to-[#0d1b2a] rounded-xl p-5 text-center border border-blue-500/20 shadow-xl flex flex-col justify-center items-center">
            <p class="text-blue-400 font-bold text-xs uppercase tracking-wider">Most Rent</p>
            <p class="text-sm font-semibold mt-3 text-gray-200 leading-snug">{{ $stats['most_rent'] }}</p>
        </div>

        <div class="bg-gradient-to-b from-[#1b263b] to-[#0d1b2a] rounded-xl p-5 text-center border border-blue-500/20 shadow-xl flex flex-col justify-center items-center">
            <p class="text-blue-400 font-bold text-xs uppercase tracking-wider">Trending</p>
            <p class="text-sm font-semibold mt-3 text-gray-200 leading-snug">{{ $stats['trending'] }}</p>
        </div>
    </div>

    <!-- Currently Borrowing Section (hanya untuk authenticated users) -->
    @auth
    @if($activeRentals->count() > 0)
    <section class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold tracking-wide italic flex items-center gap-3">
                <i class="fas fa-history text-blue-400"></i> Currently Borrowing
            </h2>
            <span class="bg-blue-500/20 text-blue-400 text-xs px-3 py-1 rounded-full font-semibold">{{ $activeRentals->count() }} books</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($activeRentals->take(4) as $rental)
            @php
            $daysRemaining = (int)now()->diffInDays($rental->due_date);
            $isOverdue = $rental->due_date < now();
                $statusColor=$isOverdue ? 'red' : ($daysRemaining <=3 ? 'amber' : 'emerald' );
                @endphp
                <div class="bg-[#14213d] rounded-2xl border border-slate-800 shadow-lg overflow-hidden hover:border-blue-500/50 transition">
                <div class="h-40 bg-slate-900 overflow-hidden">
                    <img src="{{ $rental->book->image ? asset($rental->book->image) : 'https://via.placeholder.com/300x200' }}"
                        alt="{{ $rental->book->title }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-white text-sm mb-1 line-clamp-2">{{ $rental->book->title }}</h3>
                    <p class="text-xs text-gray-400 mb-3">{{ $rental->book->author }}</p>

                    <div class="bg-slate-800 rounded-lg p-3 mb-3 space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Due Date</span>
                            <span class="font-semibold {{ $isOverdue ? 'text-red-400' : 'text-emerald-400' }}">
                                {{ $rental->due_date->format('d F Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-700">
                            <span class="text-gray-400">Days Left</span>
                            <span class="font-bold px-2 py-1 rounded {{ $isOverdue ? 'bg-red-500/20 text-red-400' : ($daysRemaining <= 3 ? 'bg-amber-500/20 text-amber-400' : 'bg-orange-500/20 text-orange-400') }}">
                                @if($isOverdue)
                                Overdue
                                @else
                                {{ $daysRemaining }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
        </div>
        @endforeach
        </div>
    </section>
    @endif
    @endauth

    <section id="categories" class="max-w-6xl mx-auto px-6 py-20">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold tracking-wide italic">Category</h2>
            <a href="#" class="text-gray-400 hover:text-white text-xs italic underline decoration-dotted">View All</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-5">
            @php $allActive = empty($selectedCategory); @endphp
            <a href="{{ route('home') }}" class="group block rounded-xl border {{ $allActive ? 'border-blue-500/40 ring-1 ring-blue-500/20' : 'border-slate-800' }} transition-all duration-300 hover:border-blue-500/40 hover:-translate-y-1">
                <div class="bg-gradient-to-b from-[#14213d] to-[#112240] rounded-xl p-6 flex flex-col items-center justify-center text-center h-40 {{ $allActive ? 'ring-1 ring-blue-500/10' : '' }}">
                    <div class="text-3xl text-blue-400 mb-4 {{ $allActive ? '' : 'group-hover:scale-110' }} transition duration-300">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span class="text-xs font-semibold tracking-wide text-gray-300 group-hover:text-white">All</span>
                </div>
            </a>
            @foreach($categories as $category)
            @php $categoryActive = !empty($selectedCategory) && $selectedCategory == $category['name']; @endphp
            @if($categoryActive)
            <a href="{{ route('home', ['category' => $category['name']]) }}" class="group block rounded-xl border border-blue-500/40 ring-1 ring-blue-500/20 transition-all duration-300 hover:border-blue-500/40 hover:-translate-y-1">
                <div class="bg-gradient-to-b from-[#14213d] to-[#112240] rounded-xl p-6 flex flex-col items-center justify-center text-center h-40 ring-1 ring-blue-500/10">
                    <div class="text-3xl text-blue-400 mb-4 transition duration-300">
                        @else
                        <a href="{{ route('home', ['category' => $category['name']]) }}" class="group block rounded-xl border border-slate-800 transition-all duration-300 hover:border-blue-500/40 hover:-translate-y-1">
                            <div class="bg-gradient-to-b from-[#14213d] to-[#112240] rounded-xl p-6 flex flex-col items-center justify-center text-center h-40">
                                <div class="text-3xl text-blue-400 mb-4 group-hover:scale-110 transition duration-300">
                                    @endif
                                    @if($category['icon'] == 'graduation-cap') <i class="fas fa-graduation-cap"></i>
                                    @elseif($category['icon'] == 'code') <i class="fas fa-laptop-code"></i>
                                    @elseif($category['icon'] == 'coins') <i class="fas fa-coins"></i>
                                    @elseif($category['icon'] == 'user') <i class="fas fa-user-astronaut"></i>
                                    @elseif($category['icon'] == 'compass') <i class="fas fa-compass"></i>
                                    @elseif($category['icon'] == 'fiction') <i class="fa fa-magic"></i>
                                    @elseif($category['icon'] == 'non-fiction') <i class="fa fa-rocket"></i>
                                    @else <i class="fas fa-book"></i>
                                    @endif
                                </div>
                                <span class="text-xs font-semibold tracking-wide text-gray-300 group-hover:text-white">{{ $category['name'] }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
    </section>

    <section id="trending" class="max-w-6xl mx-auto px-6 pb-24">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-bold tracking-wide italic">Trending Books</h2>
                <span class="bg-black/40 text-xs px-2.5 py-0.5 rounded-full text-gray-400 font-medium">Top 10</span>
            </div>
            <a href="#" class="text-gray-400 hover:text-white text-xs italic underline decoration-dotted">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($trendingBooks as $book)
            <div class="bg-gradient-to-b from-[#14213d] to-[#112240] rounded-2xl overflow-hidden border border-slate-800 shadow-xl flex flex-col justify-between h-full transform hover:-translate-y-1 transition duration-300 group">

                <div>
                    <div class="h-64 overflow-hidden relative bg-slate-900">
                        @php
                        if (is_array($book)) {
                        $imageUrl = $book['image'];
                        $bookTitle = $book['title'];
                        $bookAuthor = $book['author'];
                        $bookRating = $book['rating'];
                        $bookPrice = $book['price'];
                        } else {
                        $imageUrl = !empty($book->image) ? asset($book->image) : 'https://via.placeholder.com/320x240';
                        $bookTitle = $book->title;
                        $bookAuthor = $book->author;
                        $bookRating = $book->rating;
                        $bookPrice = $book->price;
                        }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $bookTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#14213d]/50 to-transparent"></div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-base leading-snug tracking-wide text-gray-100 group-hover:text-white line-clamp-2">
                            {{ $bookTitle }}
                        </h3>
                        <p class="text-xs text-blue-400 font-medium mt-1.5 italic">
                            {{ $bookAuthor }}
                        </p>

                        <div class="flex items-center space-x-1.5 mt-3 bg-black/20 w-fit px-2 py-0.5 rounded-md border border-slate-800">
                            <i class="fas fa-star text-amber-400 text-xs"></i>
                            <span class="text-xs font-bold text-amber-400">{{ $bookRating }}</span>
                            <span class="text-xs text-gray-400 ml-1">| Rp{{ number_format($bookPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5 space-y-3">
                    <a href="{{ isset($book->id) ? route('book.detail', ['id' => $book->id]) : route('book.detail', ['id' => $loop->index]) }}" class="w-full block text-center bg-transparent border border-blue-500/30 hover:border-blue-500 text-blue-400 hover:text-white text-xs font-semibold py-2 rounded-xl transition duration-300 shadow-sm hover:bg-blue-600/10">
                        View Details <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </section>

    <section id="books" class="max-w-6xl mx-auto px-6 pb-24 pt-12">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-bold tracking-wide italic">Book List</h2>
                <span class="bg-black/40 text-xs px-2.5 py-0.5 rounded-full text-gray-400 font-medium">User</span>
            </div>

            @auth
            @if(Auth::user()->role === 'owner')
            <a href="{{ route('books.create') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-white bg-blue-600/90 hover:bg-blue-500 px-4 py-2 rounded-full transition">
                <i class="fas fa-plus text-[10px]"></i> Add Book
            </a>
            @endif
            @endauth
        </div>


        @if($books->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($books as $book)
            <div class="bg-gradient-to-b from-[#14213d] to-[#112240] rounded-2xl overflow-hidden border border-slate-800 shadow-xl flex flex-col justify-between h-full transform hover:-translate-y-1 transition duration-300 group">

                <div>
                    <div class="h-64 overflow-hidden relative bg-slate-900">
                        <img src="{{ asset($book->image) }}" alt="{{ $book->title }}" class="w-full h-full fit-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#14213d]/50 to-transparent"></div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-base leading-snug tracking-wide text-gray-100 group-hover:text-white line-clamp-2">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-blue-400 font-medium mt-1.5 italic">
                            {{ $book->author }}
                        </p>

                        <div class="flex items-center space-x-1.5 mt-3 bg-black/20 w-fit px-2 py-0.5 rounded-md border border-slate-800">
                            <span class="text-xs font-bold text-white">Rp{{ number_format($book->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5 space-y-3">
                    <a href="{{ route('book.detail', ['id' => $book->id]) }}" class="w-full block text-center bg-transparent border border-blue-500/30 hover:border-blue-500 text-blue-400 hover:text-white text-xs font-semibold py-2 rounded-xl transition duration-300 shadow-sm hover:bg-blue-600/10">
                        View Details <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                    </a>
                    @auth
                    <div>
                        @if(Auth::check() && Auth::user()->id === $book->owner_id && Auth::user()->role === 'owner')
                        <a href="{{ route('books.edit', $book->id) }}" class="w-full block text-center bg-slate-800 border border-slate-700 hover:border-slate-500 text-slate-200 hover:text-white text-xs font-semibold py-2 rounded-xl transition duration-300 shadow-sm hover:bg-slate-900/80">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full block text-center bg-red-600/10 border border-red-500/30 hover:border-red-500 text-red-300 hover:text-white text-xs font-semibold py-2 rounded-xl transition duration-300 shadow-sm hover:bg-red-600/20">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                        @endif
                    </div>
                    @endauth
                </div>

            </div>
            @endforeach
        </div>
        @else
        <div class="bg-[#112240] border border-slate-800 rounded-3xl p-12 text-center text-gray-300">
            There are no new books yet. Add a book to display it on the homepage.
        </div>
        @endif
    </section>

    <section id="faq" class="py-16 bg-slate-900 text-white border-t border-slate-800">
        <div class="max-w-3xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-xs font-semibold tracking-widest text-indigo-400 uppercase">FAQ</span>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-100">Got questions? We've got answers.</h2>
                <p class="mt-2 text-sm text-slate-400">Quick answers to things users ask most before jumping in.</p>
            </div>

            <div class="space-y-4">

                <div class="border border-slate-700/60 rounded-xl bg-slate-800/50 overflow-hidden">
                    <details class="group">
                        <summary class="flex justify-between items-center p-5 font-medium cursor-pointer list-none text-slate-200 hover:text-white transition">
                            <span>How do I add a new book to the platform?</span>
                            <span class="transition group-open:rotate-180 text-indigo-400">
                                <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 text-slate-400 text-sm leading-relaxed border-t border-slate-700/30 pt-3">
                            It’s super simple! Just head over to the Book List section and click the <strong class="text-indigo-300">"+ Add Book"</strong> button. Fill in some quick details, upload a cool cover image, and you’re good to go! Your new book will be live on the homepage for everyone to see and explore.
                        </div>
                    </details>
                </div>

                <div class="border border-slate-700/60 rounded-xl bg-slate-800/50 overflow-hidden">
                    <details class="group">
                        <summary class="flex justify-between items-center p-5 font-medium cursor-pointer list-none text-slate-200 hover:text-white transition">
                            <span>Why do I have to log in first to view the main page?</span>
                            <span class="transition group-open:rotate-180 text-indigo-400">
                                <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 text-slate-400 text-sm leading-relaxed border-t border-slate-700/30 pt-3">
                            Because PinjamBuku is customized just for you. Your book list, reading history, and cart items are tied directly to your account so they stay safe and won't get messed up by anyone else.
                        </div>
                    </details>
                </div>

                <div class="border border-slate-700/60 rounded-xl bg-slate-800/50 overflow-hidden">
                    <details class="group">
                        <summary class="flex justify-between items-center p-5 font-medium cursor-pointer list-none text-slate-200 hover:text-white transition">
                            <span>What is the purpose of the cart on this platform?</span>
                            <span class="transition group-open:rotate-180 text-indigo-400">
                                <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 text-slate-400 text-sm leading-relaxed border-t border-slate-700/30 pt-3">
                            Think of it as a temporary holding area. When you're browsing for books but aren't ready to borrow or read the details yet, you can add them to your cart so you don't have to search for them again later.
                        </div>
                    </details>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('nav .nav-link');

            const sections = [{
                    id: '#top',
                    el: document.querySelector('#top')
                },
                {
                    id: '#about',
                    el: document.querySelector('#about')
                },
                {
                    id: '#categories',
                    el: document.querySelector('#categories')
                },
                {
                    id: '#trending',
                    el: document.querySelector('#trending')
                },
                {
                    id: '#books',
                    el: document.querySelector('#books')
                },
                {
                    id: '#faq',
                    el: document.querySelector('#faq')
                },
            ].filter(s => s.el);

            function setActiveLink(hash) {
                navLinks.forEach(link => {
                    const isActive = link.getAttribute('href') === hash;
                    link.classList.toggle('text-white', isActive);
                    link.classList.toggle('font-semibold', isActive);
                    link.classList.toggle('border-white', isActive);
                    link.classList.toggle('text-gray-300', !isActive);
                    link.classList.toggle('border-transparent', !isActive);
                });
            }

            function getSectionFromScroll() {
                const offset = 140; // account for sticky navbar height
                let current = '#top';
                let best = -Infinity;

                sections.forEach(s => {
                    const rect = s.el.getBoundingClientRect();
                    if (rect.top <= offset && rect.top > best) {
                        best = rect.top;
                        current = s.id;
                    }
                });

                return current;
            }

            function updateActiveLink() {
                const hash = window.location.hash || getSectionFromScroll();
                setActiveLink(hash);
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(() => {
                        const h = link.getAttribute('href');
                        setActiveLink(h);
                    }, 60);
                });
            });

            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', (event) => {
                    event.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!userMenuDropdown.contains(event.target) && !userMenuButton.contains(event.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }

            window.addEventListener('scroll', updateActiveLink);
            window.addEventListener('hashchange', updateActiveLink);

            updateActiveLink();

            const flashNotice = document.getElementById('flash-notice');
            const categoryBanner = document.getElementById('selected-category-banner');

            if (flashNotice) {
                setTimeout(() => flashNotice.remove(), 4500);
            }
            if (categoryBanner) {
                setTimeout(() => categoryBanner.remove(), 4500);
            }
        });

        let selectedDuration = 7;

        function calculatePrice(basePrice, duration) {
            const multiplier = duration / 7;
            return Math.round(basePrice * multiplier);
        }

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>

</body>

</html>
