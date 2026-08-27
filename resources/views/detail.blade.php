<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book Detail - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0d1b2a;
        }
    </style>
</head>

<body class="text-white font-sans antialiased">
    @php
        $bookData = is_array($book) ? (object) $book : $book;
        $cover = data_get($bookData, 'image');
        if ($cover && !preg_match('/^https?:\/\//', $cover)) {
            $cover = asset($cover);
        }
    @endphp
    <div class="max-w-4xl mx-auto px-6 py-16">
        <a href="{{ route('home') }}" class="text-blue-400 hover:underline text-sm mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Home
        </a>
        <div class="bg-[#14213d] rounded-2xl p-8 border border-slate-800 shadow-2xl flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/3 h-96 rounded-xl overflow-hidden">
                <img src="{{ $cover ?: 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
            </div>
            <div class="w-full md:w-2/3 flex flex-col justify-between">
                <div>
                    <span class="bg-blue-500/20 text-blue-400 text-xs px-3 py-1 rounded-full border border-blue-500/30 font-semibold">{{ $bookData->category }}</span>
                    <h1 class="text-3xl font-bold mt-3">{{ $bookData->title }}</h1>
                    <p class="text-blue-400 italic mt-1">By {{ $bookData->author }}</p>
                    <div class="flex items-center space-x-2 mt-4 bg-black/20 w-fit px-3 py-1 rounded-md border border-slate-800">
                        <i class="fas fa-star text-amber-400"></i>
                        <span class="font-bold text-amber-400 text-sm">{{ $bookData->rating }} / 5.0</span>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs text-gray-400">Rent Price</p>
                        <p class="text-2xl font-extrabold text-blue-400 mt-0.5">
                            Rp {{ number_format($bookData->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-gray-300 mt-6 leading-relaxed text-sm">{{ $bookData->description }}</p>
                </div>
                <div class="mt-8 flex items-center justify-between border-t border-slate-800 pt-6">
                    <div>
                        <p class="text-xs text-gray-400">Book Status</p>
                        <p class="text-emerald-400 font-semibold text-sm mt-0.5"><i class="fas fa-check-circle mr-1"></i> {{ $bookData->status }}</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        @auth
                        @if(Auth::user()->role === 'owner')
                        @if(isset($bookData->id))
                        <form action="{{ route('books.destroy', $bookData->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book from the system?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-600/20 hover:bg-rose-600 text-rose-400 hover:text-white px-4 py-2.5 rounded-xl font-bold text-sm transition border border-rose-500/30">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                        @endif
                        @elseif(Auth::user()->role === 'renter')
                        <button type="button" onclick="openDurationModal({{ $bookData->id }}, '{{ $bookData->title }}', {{ $bookData->price }})" class="bg-gradient-to-r from-blue-600 to-blue-500 hover:brightness-110 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition shadow-lg">
                            <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                        </button>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-600 to-blue-500 hover:brightness-110 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login to Rent
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Durasi Peminjaman -->
    <div id="durationModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-[#14213d] rounded-2xl p-8 border border-slate-800 shadow-2xl w-96">
            <h2 class="text-2xl font-bold text-white mb-2">Select Borrowing Duration</h2>
            <p class="text-gray-400 text-sm mb-6">How long would you like to borrow this book?</p>

            <input type="hidden" id="modalBookId" value="">
            <input type="hidden" id="modalBookTitle" value="">
            <input type="hidden" id="modalBookPrice" value="">

            <div class="space-y-3 mb-6">
                <label class="flex items-center p-4 border border-slate-600 rounded-lg cursor-pointer hover:bg-slate-800/50 transition">
                    <input type="radio" name="duration" value="7" checked class="w-4 h-4 text-blue-500 cursor-pointer">
                    <span class="ml-3 text-white font-semibold">7 Days</span>
                    <span class="ml-auto text-blue-400 text-sm">Rp <span id="price7">0</span></span>
                </label>
                <label class="flex items-center p-4 border border-slate-600 rounded-lg cursor-pointer hover:bg-slate-800/50 transition">
                    <input type="radio" name="duration" value="14" class="w-4 h-4 text-blue-500 cursor-pointer">
                    <span class="ml-3 text-white font-semibold">14 Days</span>
                    <span class="ml-auto text-blue-400 text-sm">Rp <span id="price14">0</span></span>
                </label>
                <label class="flex items-center p-4 border border-slate-600 rounded-lg cursor-pointer hover:bg-slate-800/50 transition">
                    <input type="radio" name="duration" value="21" class="w-4 h-4 text-blue-500 cursor-pointer">
                    <span class="ml-3 text-white font-semibold">21 Days</span>
                    <span class="ml-auto text-blue-400 text-sm">Rp <span id="price21">0</span></span>
                </label>
            </div>

            <div class="flex gap-3">
                <button onclick="closeDurationModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                    cancel
                </button>
                <button onclick="confirmAddToCart()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-500 hover:brightness-110 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg">
                    <i class="fas fa-check mr-2"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedDuration = 7;

        function calculatePrice(basePrice, duration) {
            const multiplier = duration / 7;
            return Math.round(basePrice * multiplier);
        }

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function openDurationModal(bookId, bookTitle, basePrice) {
            document.getElementById('modalBookId').value = bookId;
            document.getElementById('modalBookTitle').value = bookTitle;
            document.getElementById('modalBookPrice').value = basePrice;

            // Update harga untuk setiap durasi
            document.getElementById('price7').textContent = formatCurrency(calculatePrice(basePrice, 7));
            document.getElementById('price14').textContent = formatCurrency(calculatePrice(basePrice, 14));
            document.getElementById('price21').textContent = formatCurrency(calculatePrice(basePrice, 21));

            // Show modal
            document.getElementById('durationModal').classList.remove('hidden');

            // Update selected duration ketika radio berubah
            document.querySelectorAll('input[name="duration"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedDuration = parseInt(this.value);
                });
            });
        }

        function closeDurationModal() {
            document.getElementById('durationModal').classList.add('hidden');
        }

        function confirmAddToCart() {
            const bookId = document.getElementById('modalBookId').value;
            const bookTitle = document.getElementById('modalBookTitle').value;
            const basePrice = parseInt(document.getElementById('modalBookPrice').value);
            const duration = selectedDuration;
            const finalPrice = calculatePrice(basePrice, duration);

            // AJAX untuk add to cart dengan durasi
            const cartAddUrl = `/cart/add/${bookId}`;
            fetch(cartAddUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ duration: duration })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeDurationModal();
                    showConfirmationPopup(bookTitle, duration, finalPrice);
                } else {
                    alert('can not add to cart: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        }

        function showConfirmationPopup(bookTitle, duration, price) {
            // Buat overlay untuk popup
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
            overlay.id = 'confirmationOverlay';

            const popup = document.createElement('div');
            popup.className = 'bg-[#14213d] rounded-2xl p-8 border border-slate-800 shadow-2xl w-96';
            popup.innerHTML = `
                <div class="text-center">
                    <div class="text-5xl mb-4">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Successful added to cart!</h2>
                    <div class="bg-slate-800 rounded-lg p-4 mb-6 text-left">
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Book</p>
                        <p class="text-white font-semibold">${bookTitle}</p>
                        <div class="flex justify-between mt-3 pt-3 border-t border-slate-700">
                            <span class="text-gray-400">Duration</span>
                            <span class="text-blue-400 font-semibold">${duration} Days</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-gray-400">Price</span>
                            <span class="text-blue-400 font-semibold">Rp ${formatCurrency(price)}</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="closeConfirmationAndStay()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                            back to home
                        </button>
                        <button onclick="goToCart()" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-500 hover:brightness-110 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-shopping-cart mr-1"></i> go to cart
                        </button>
                    </div>
                </div>
            `;

            overlay.appendChild(popup);
            document.body.appendChild(overlay);
        }

        function closeConfirmationAndStay() {
            window.location.href = '{{ route("home") }}';
        }

        function goToCart() {
            window.location.href = '{{ route("cart.index") }}';
        }

        // Close modal ketika klik di luar
        document.getElementById('durationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDurationModal();
            }
        });
    </script>
</body>

</html>
