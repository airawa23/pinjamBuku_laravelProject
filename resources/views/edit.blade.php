<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - PinjamBuku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #0d1b2a; }</style>
</head>
<body class="text-white font-sans antialiased">
    <div class="max-w-2xl mx-auto px-6 py-16">
        <a href="{{ route('home') }}" class="text-blue-400 hover:underline text-sm mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 p-4 rounded-xl mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/40 text-red-200 p-4 rounded-xl mb-6 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#14213d] rounded-2xl p-8 border border-slate-800 shadow-2xl">
            <h1 class="text-2xl font-bold mb-6 text-blue-400"><i class="fas fa-edit mr-2"></i> Edit Buku</h1>

            <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase">Judul Buku</label>
                    <input type="text" name="title" required value="{{ old('title', $book->title) }}" class="w-full bg-black/20 border border-slate-800 rounded-xl px-4 py-3 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase">Penulis / Author</label>
                        <input type="text" name="author" required value="{{ old('author', $book->author) }}" class="w-full bg-black/20 border border-slate-800 rounded-xl px-4 py-3 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase">Kategori</label>
                        <select name="category" required class="w-full bg-[#14213d] border border-slate-800 rounded-xl px-4 py-3 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition">
                            <option value="Education" {{ old('category', $book->category) == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="IT & Engineering" {{ old('category', $book->category) == 'IT & Engineering' ? 'selected' : '' }}>IT & Engineering</option>
                            <option value="Accounting & Finance" {{ old('category', $book->category) == 'Accounting & Finance' ? 'selected' : '' }}>Accounting & Finance</option>
                            <option value="Self Development" {{ old('category', $book->category) == 'Self Development' ? 'selected' : '' }}>Self Development</option>
                            <option value="Environment & Life" {{ old('category', $book->category) == 'Environment & Life' ? 'selected' : '' }}>Environment & Life</option>
                            <option value="Non-Fiksi" {{ old('category', $book->category) == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiction</option>
                            <option value="Fiksi" {{ old('category', $book->category) == 'Fiksi' ? 'selected' : '' }}>Fiction</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase">Harga Pinjam (Rp)</label>
                        <input type="number" name="price" required value="{{ old('price', $book->price) }}" class="w-full bg-black/20 border border-slate-800 rounded-xl px-4 py-3 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase">Upload Cover Buku</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-black/20 border border-slate-800 rounded-xl px-4 py-2.5 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah cover.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase">Deskripsi Buku</label>
                    <textarea name="description" rows="4" required class="w-full bg-black/20 border border-slate-800 rounded-xl px-4 py-3 mt-1.5 text-sm focus:outline-none focus:border-blue-500 transition">{{ old('description', $book->description) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:brightness-110 text-white py-3 rounded-xl font-bold text-sm transition shadow-lg mt-4">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</body>
</html>
