<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rental;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; //logic delete book
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Statistik statis sementara
        $stats = [
            'total_books' => 250,
            'active_users' => 65,
            'most_rent' => 'Information & Technology',
            'trending' => 'Environment and Life'
        ];

        $selectedCategory = $request->query('category');
        $search = $request->query('search');

        $categories = [
            ['name' => 'Education', 'icon' => 'graduation-cap'],
            ['name' => 'IT & Engineering', 'icon' => 'code'],
            ['name' => 'Accounting & Finance', 'icon' => 'coins'],
            ['name' => 'Self Development', 'icon' => 'user'],
            ['name' => 'Architecture', 'icon' => 'compass'],
            ['name' => 'Accounting & Finance', 'icon' => 'coins'],
            ['name' => 'Non-Fiction', 'icon' => 'non-fiction'],
            ['name' => 'Fiction', 'icon' => 'fiction'],
        ];

        // Data dummy untuk Trending Books
        $trendingBooks = Book::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        if ($trendingBooks->isEmpty()) {
            $trendingBooks = collect([
                [
                    'title' => 'The Create of Environment',
                    'author' => 'Dr. Eleanor Vance',
                    'rating' => 4.8,
                    'price' => 25000,
                    'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=687',
                ],
                [
                    'title' => 'The Digital Fortress',
                    'author' => 'Dan Brown',
                    'price' => 20000,
                    'rating' => 4.7,
                    'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=698',
                ],
                [
                    'title' => 'Brief Answers to the Big Questions',
                    'author' => 'Stephen Hawking',
                    'price' => 30000,
                    'rating' => 4.9,
                    'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=687',
                ],
                [
                    'title' => 'The Design of Everyday Things',
                    'author' => 'Don Norman',
                    'price' => 15000,
                    'rating' => 4.6,
                    'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=687',
                ],
            ]);
        }

        $books = Book::when($selectedCategory, function ($query) use ($selectedCategory) {
            return $query->where('category', $selectedCategory);
        })->when($search, function ($query) use ($search) {
            return $query->where(function ($subquery) use ($search) {
                $subquery->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        })->get();

        $cartCount = count(session('cart', []));

        // Ambil active rentals jika user sudah login
        $activeRentals = collect();
        if (Auth::check()) {
            $activeRentals = Rental::where('user_id', Auth::id())
                ->where('status', 'borrowed')
                ->with('book')
                ->orderBy('due_date', 'asc')
                ->get();
        }

        return view('home', compact('stats', 'categories', 'trendingBooks', 'books', 'cartCount', 'selectedCategory', 'search', 'activeRentals'));
    }

    public function detail($id)
    {
        $book = Book::find($id);

        if (!$book) {
            $books = [
                [
                    'title' => 'The Create of Environment',
                    'author' => 'Dr. Eleanor Vance',
                    'rating' => 4.8,
                    'price' => 25000,
                    'category' => 'Environment & Life',
                    'status' => 'Available',
                    'description' => 'Buku ini membahas tentang bagaimana lingkungan terbentuk dan bagaimana manusia berinteraksi dengan alam sekitar untuk menciptakan masa depan yang berkelanjutan.',
                    'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=687'
                ],
                [
                    'title' => 'The Digital Fortress',
                    'author' => 'Dan Brown',
                    'rating' => 4.7,
                    'price' => 20000,
                    'category' => 'IT & Engineering',
                    'status' => 'unavailable',
                    'description' => 'Buku ini adalah novel thriller yang mengisahkan tentang seorang ahli kriptografi yang harus memecahkan kode rahasia untuk menyelamatkan dunia dari ancaman digital.',
                    'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=698'
                ],
            ];

            $book = $books[$id] ?? $books[0];
        }

        return view('detail', compact('book'));
    }

    // Fungsi menampilkan form tambah buku
    public function create()
    {
        return view('create');
    }

    // Fungsi memproses upload dan simpan data ke DB
    public function store(Request $request)
    {
        // 1. Validasi Inputan
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal file 2MB
        ]);

        // 2. Proses Upload Gambar
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            // Buat nama file unik: misal 171653821.jpg
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            // Simpan file ke dalam folder: public/uploads/books
            $imageFile->move(public_path('uploads/books'), $imageName);
            // Path url yang akan disimpan ke database
            $imagePath = 'uploads/books/' . $imageName;
        }

        // 3. Simpan ke Database
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath, // Menyimpan path gambar
            'rating' => 5.0, // Default rating untuk buku baru
            'status' => 'Available', // Default status buku baru
            'owner_id' => Auth::id() // Simpan ID pemilik buku
        ]);

        return redirect()->back()->with('success', 'Buku baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);

        // Verifikasi bahwa user adalah pemilik buku
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki izin untuk mengubah buku ini.');
        }

        return view('edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        // Verifikasi bahwa user adalah pemilik buku
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki izin untuk mengubah buku ini.');
        }

        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (File::exists(public_path($book->image))) {
                File::delete(public_path($book->image));
            }

            $imageFile = $request->file('image');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('uploads/books'), $imageName);
            $book->image = 'uploads/books/' . $imageName;
        }

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        $book->save();

        return redirect()->route('home')->with('success', 'Buku berhasil diperbarui!');
    }

    // Helper function untuk hitung harga berdasarkan durasi
    private function calculateRentalPrice($basePrice, $duration)
    {
        $duration = (int)$duration;

        // Multiplier berdasarkan durasi: 7 hari = 1x, 14 hari = 2x, 21 hari = 3x
        $multiplier = $duration / 7;

        return $basePrice * $multiplier;
    }

    public function cart()
    {
        $cartItems = session('cart', []);
        $cartTotal = 0;

        foreach ($cartItems as &$item) {
            // Hitung harga dinamis berdasarkan durasi
            $rentalDuration = $item['rental_duration'] ?? 7;
            $basePrice = $item['base_price'] ?? $item['price'];
            $finalPrice = $this->calculateRentalPrice($basePrice, $rentalDuration);

            // Update harga yang ditampilkan
            $item['final_price'] = $finalPrice;
            $cartTotal += $finalPrice * ($item['qty'] ?? 1);
        }

        return view('cart', compact('cartItems', 'cartTotal'));
    }

    public function addToCart(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $cart = session('cart', []);
        $duration = $request->input('duration', 7);

        if (!isset($cart[$id])) {
            $cart[$id] = [
                'id' => $book->id,
                'title' => $book->title,
                'base_price' => $book->price, // Harga dasar (7 hari)
                'price' => $book->price,
                'qty' => 1,
                'image' => $book->image,
                'rental_duration' => $duration, // Durasi dari request
            ];
        } else {
            $cart[$id]['qty'] += 1;
            $cart[$id]['rental_duration'] = $duration;
        }

        session(['cart' => $cart]);

        // Return JSON response untuk AJAX
        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan ke keranjang!',
                'duration' => $duration
            ]);
        }

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Buku berhasil dihapus dari keranjang');
    }

    public function destroy($id)
    {
        // Cari data buku berdasarkan ID
        $book = Book::findOrFail($id);

        // Verifikasi bahwa user adalah pemilik buku
        if ($book->owner_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki izin untuk menghapus buku ini.');
        }

        // 2. Hapus file gambar asli yang ada di folder public/uploads/books/
        if (File::exists(public_path($book->image))) {
            File::delete(public_path($book->image));
        }

        // 3. Hapus data dari database
        $book->delete();

        // 4. Redirect ke halaman utama dengan pesan sukses
        return redirect()->route('home')->with('success', 'Buku berhasil dihapus dari sistem!');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        foreach ($cartItems as $bookId => $item) {
            $rentalDuration = $item['rental_duration'] ?? 7; // Durasi dari session cart

            // Buat record peminjaman
            Rental::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
                'rental_duration' => $rentalDuration,
                'borrowed_at' => now(),
                'due_date' => now()->addDays($rentalDuration),
                'status' => 'borrowed',
            ]);

            // Update status buku jadi tidak tersedia
            Book::find($bookId)->update(['status' => 'Unavailable']);
        }

        // Kosongkan cart
        session(['cart' => []]);

        return redirect()->route('home')->with('success', 'Peminjaman berhasil! Buku akan dikirim segera.');
    }

    public function updateCartDuration(Request $request, $id)
    {
        // Ambil data cart dari session
        $cart = session()->get('cart', []);

        // Cek apa buku dengan ID tersebut ada di dalam cart
        if (isset($cart[$id])) {

            // Ambil durasi dari request JavaScript (harus integer)
            $duration = (int) $request->duration;

            // Update durasi di array cart
            $cart[$id]['rental_duration'] = $duration;

            // Hitung ulang final_price menggunakan rumus pembagian 7
            $basePrice = $cart[$id]['base_price'] ?? $cart[$id]['price'];
            $multiplier = $duration / 7;
            $cart[$id]['final_price'] = $basePrice * $multiplier;

            // 6. Simpan kembali cart yang sudah diupdate ke session
            session()->put('cart', $cart);

            // 7. Kembalikan respons sukses ke JavaScript
            return response()->json([
                'success' => true,
                'formatted_price' => 'Rp ' . number_format($cart[$id]['final_price'], 0, ',', '.')
            ]);
        }

        // Jika buku tidak ditemukan di cart
        return response()->json([
            'success' => false,
            'message' => 'Buku tidak ditemukan di keranjang'
        ], 404);
    }

    public function dashboard()
    {
        $user = Auth::user();

        // 1. Ambil buku yang sedang dipinjam (status borrowed)
        $activeRentals = Rental::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->with('book')
            ->orderBy('due_date', 'asc')
            ->get();

        // 2. Ambil kategori buku yang pernah dipinjam user
        $borrowedCategories = Rental::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->with('book')
            ->get()
            ->pluck('book.category')
            ->unique()
            ->toArray();

        // 3. Rekomendasi berdasarkan kategori yang dipinjam
        $recommendations = collect();
        if (!empty($borrowedCategories)) {
            $recommendations = Book::whereIn('category', $borrowedCategories)
                ->whereNotIn('id', function($query) use ($user) {
                    $query->select('book_id')
                        ->from('rentals')
                        ->where('user_id', $user->id);
                })
                ->orderBy('rating', 'desc')
                ->take(8)
                ->get();
        }

        // Jika tidak ada rekomendasi berdasarkan kategori, tampilkan trending books
        if ($recommendations->isEmpty()) {
            $recommendations = Book::orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        }

        // 4. Hitung statistik personal
        $stats = [
            'active_rentals' => $activeRentals->count(),
            'total_borrowed' => Rental::where('user_id', $user->id)->count(),
            'overdue_books' => $activeRentals->where('due_date', '<', now())->count(),
        ];

        return view('dashboard', compact('activeRentals', 'recommendations', 'stats'));
    }
}
