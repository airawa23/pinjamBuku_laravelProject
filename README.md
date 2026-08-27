# 📚 PinjamBuku

**PinjamBuku** adalah aplikasi web perpustakaan sederhana yang memungkinkan pengguna untuk menjelajahi katalog buku, mencari buku berdasarkan judul atau penulis, melihat detail buku, serta menambahkan buku ke keranjang peminjaman.

Aplikasi ini dibangun menggunakan **Laravel 12** dengan tampilan modern dan responsif menggunakan **Tailwind CSS**.

## 🖥️ Preview

<p align="center">
  <img width="1892" height="784" alt="PinjamBuku Preview" src="https://github.com/user-attachments/assets/0e5548ae-f4f4-4c4f-9cd0-28fcb473f52a" />
</p>

## ✨ Features

### 🔐 Authentication
- User registration
- User login & logout
- Authentication middleware
- Form validation

### 📖 Book Catalog
- Menampilkan daftar buku
- Menampilkan buku yang sedang trending
- Pencarian berdasarkan judul atau penulis
- Filter buku berdasarkan kategori
- Informasi harga dan rating buku
- Status ketersediaan buku

### 📚 Book Management
- Menambahkan buku baru
- Melihat detail buku
- Mengedit informasi buku
- Menghapus buku
- Upload cover buku
- Validasi data dan file upload

### 🛒 Borrowing Cart
- Menambahkan buku ke keranjang
- Menambah jumlah buku
- Menghapus buku dari keranjang
- Menghitung total harga peminjaman

## 🛠️ Tech Stack

| Technology | Description |
|---|---|
| **Laravel 12** | Backend web framework |
| **PHP 8.2+** | Programming language |
| **MySQL** | Database |
| **Blade** | Template engine |
| **Tailwind CSS** | UI styling |
| **JavaScript** | Client-side interaction |
| **Font Awesome** | Icons |

## 📂 Project Structure

```text
pinjamBuku/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── BookController.php
│   │       ├── LoginController.php
│   │       └── Controller.php
│   └── Models/
│       ├── Book.php
│       └── User.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite
│
├── public/
│   ├── images/
│   └── uploads/
│       └── books/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── home.blade.php
│       ├── detail.blade.php
│       ├── cart.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       ├── login.blade.php
│       └── register.blade.php
│
└── routes/
    └── web.php
```

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/pinjamBuku.git
cd pinjamBuku
```

### 2. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

### 3. Configure Environment

Copy `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Configure Database

Project ini menggunakan **SQLite**.

Pastikan file database tersedia:

```bash
touch database/database.sqlite
```

Kemudian jalankan migration:

```bash
php artisan migrate
```

Jika ingin menggunakan data awal yang tersedia pada seeder:

```bash
php artisan db:seed
```

### 5. Build Frontend

```bash
npm run build
```

### 6. Run Application

Jalankan Laravel development server:

```bash
php artisan serve
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

## 🖼️ Screenshots

### Home / Book Catalog

<p align="center">
  <img width="1527" height="762" alt="booklist" src="https://github.com/user-attachments/assets/a17bbf43-518c-4c9e-9bf5-762108d7ec17"/>
</p>

### Book Detail

<p align="center">
<img width="1527" height="770" alt="book_detail" src="https://github.com/user-attachments/assets/c8941f32-4fae-49d6-bd27-33b84653c11e" />
</p>

### Shopping Cart

<p align="center">
<img width="1535" height="653" alt="Shopping Cart" src="https://github.com/user-attachments/assets/836db13d-a764-428c-9e50-b9f357e32b26" />
</p>


### Login

<p align="center">
<img width="531" height="681" alt="Login" src="https://github.com/user-attachments/assets/c0b40411-883e-4b27-99b1-5bb11af792b5" />
</p>

### Register

<p align="center">
<img width="346" height="686" alt="Register" src="https://github.com/user-attachments/assets/99d98576-3e57-4b6d-8f79-4cec9a3f74fb" />
</p>

## 🔄 Application Flow

```text
Register / Login
       ↓
   Book Catalog
       ↓
Search / Filter Books
       ↓
   Book Detail
       ↓
 Add to Borrowing Cart
       ↓
    Cart Review
```

Untuk pengelolaan buku:

```text
Book Management
       ↓
 Add New Book
       ↓
 Edit Book
       ↓
 Delete Book
       ↓
 Update Book Catalog
```

## 🗃️ Database

Aplikasi menggunakan database SQLite dengan model utama:

### User
Digunakan untuk menyimpan informasi pengguna dan autentikasi.

### Book
Menyimpan informasi buku seperti:

- Title
- Author
- Category
- Price
- Description
- Image
- Rating
- Status

Keranjang peminjaman disimpan menggunakan **Laravel Session**.

## 🔍 Search & Filter

Pengguna dapat mencari buku berdasarkan:

- Judul buku
- Nama penulis

Buku juga dapat difilter berdasarkan kategori seperti:

- Education
- IT & Engineering
- Accounting & Finance
- Self Development
- Environment & Life
- Non-Fiction
- Fiction

## 📸 Book Cover Upload

Admin/user yang memiliki akses pengelolaan buku dapat mengunggah cover buku.

File gambar divalidasi berdasarkan:

- Format JPEG
- Format PNG
- Format JPG
- Maksimal ukuran 2 MB

Cover buku disimpan pada:

```text
public/uploads/books/
```

## 🧪 Testing

Untuk menjalankan test Laravel:

```bash
php artisan test
```

## 👨‍💻 Author

Developed by **Airlangga Bayu Taqwa**

---
