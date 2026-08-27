<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0d1b2a;
        }
    </style>
</head>

<body class="text-white font-sans antialiased">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <a href="{{ route('home') }}" class="text-blue-400 hover:underline text-sm mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Home
        </a>

        @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 p-4 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-[#14213d] rounded-3xl p-8 border border-slate-800 shadow-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Cart</h1>
                    <p class="text-gray-400 text-sm mt-1">books you have added to your cart</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-xs uppercase tracking-[0.3em]">Total</p>
                    <p class="text-2xl font-bold text-blue-300">Rp {{ number_format($cartTotal, 0, ',', '.') }}</p>
                </div>
            </div>

            @if(count($cartItems) > 0)
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-[#0f172a] rounded-3xl p-5 border border-slate-800 flex flex-col md:flex-row gap-4 items-center">
                    <div class="w-full md:w-44 h-40 rounded-3xl overflow-hidden bg-slate-900">
                        <img src="{{ $item['image'] ? asset($item['image']) : 'https://via.placeholder.com/320x240' }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold">{{ $item['title'] }}</h2>
                        <p class="text-sm text-blue-400 italic mt-1">amount: {{ $item['qty'] }}</p>
                        <p class="text-sm text-gray-400 mt-2">Base Price: Rp {{ number_format($item['base_price'] ?? $item['price'], 0, ',', '.') }} (7 hari)</p>
                        <p class="text-sm text-blue-300 font-semibold mt-2">Final Price: <span class="item-final-price-{{ $item['id'] }}">Rp {{ number_format($item['final_price'] ?? $item['price'], 0, ',', '.') }}</span></p>
                        <p class="text-sm text-gray-400 mt-1">Subtotal: Rp {{ number_format(($item['final_price'] ?? $item['price']) * $item['qty'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <select name="rental_duration_{{ $item['id'] }}" class="rental-duration-select bg-slate-700 text-white border border-slate-600 rounded-lg px-3 py-2 text-sm" data-book-id="{{ $item['id'] }}" data-base-price="{{ $item['base_price'] ?? $item['price'] }}" data-qty="{{ $item['qty'] }}">
                            <option value="7" {{ ($item['rental_duration'] ?? 7) == 7 ? 'selected' : '' }}>7 Hari</option>
                            <option value="14" {{ ($item['rental_duration'] ?? 7) == 14 ? 'selected' : '' }}>14 Hari</option>
                            <option value="21" {{ ($item['rental_duration'] ?? 7) == 21 ? 'selected' : '' }}>21 Hari</option>
                        </select>
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600/10 text-red-400 hover:text-white hover:bg-red-600 border border-red-500/20 hover:border-red-600 rounded-xl px-4 py-2 text-xs font-semibold transition duration-200">
                                <i class="fas fa-trash text-[11px]"></i> delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-gray-300 text-sm">
                    Total Books: {{ array_sum(array_map(fn($item) => $item['qty'], $cartItems)) }}
                </div>
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 px-6 py-3 rounded-xl text-white font-bold text-sm shadow-lg shadow-blue-500/20 active:scale-98 transition duration-200">
                        Checkout (Rp {{ number_format($cartTotal, 0, ',', '.') }})
                    </button>
                </form>
            </div>
            @else
            <div class="bg-[#112240] border border-slate-800 rounded-3xl p-12 text-center text-gray-300">
                your cart is empty <i class="fas fa-shopping-cart ml-2"></i>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Handle perubahan durasi peminjaman
        document.querySelectorAll('.rental-duration-select').forEach(select => {
            select.addEventListener('change', function() {
                const bookId = this.dataset.bookId;
                const duration = this.value;
                const basePrice = parseInt(this.dataset.basePrice);
                const qty = parseInt(this.dataset.qty);

                // AJAX call untuk update duration
                fetch(`/cart/update-duration/${bookId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ duration: duration })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update harga di view
                        const finalPriceElement = document.querySelector(`.item-final-price-${bookId}`);
                        if (finalPriceElement) {
                            finalPriceElement.textContent = data.formatted_price;
                        }

                        // Reload page untuk update total (atau bisa hitung manual)
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>
