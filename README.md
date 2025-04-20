# CampRent - Sistem Penyewaan Alat Kemah

## Deskripsi
CampRent adalah aplikasi web berbasis PHP yang dirancang untuk mengelola penyewaan peralatan kemah. Aplikasi ini menyediakan fitur lengkap untuk administrasi peralatan, kategori, dan transaksi penyewaan dengan antarmuka yang user-friendly dan sistem keamanan menggunakan PDO dan prepared statement.

## Desain Program

### Struktur Folder
```
camping-rental/
├── class/                # Berisi class/model untuk manipulasi data
│   ├── Peralatan.php     # Class untuk mengelola data peralatan
│   ├── Kategori.php      # Class untuk mengelola data kategori
│   └── Penyewaan.php     # Class untuk mengelola transaksi penyewaan
├── config/               # Konfigurasi aplikasi
│   └── database.php      # Konfigurasi koneksi database
├── database/             # Script database
│   └── setup.sql         # Script untuk membuat struktur database
├── view/                 # Tampilan UI
│   ├── header.php        # Header untuk semua halaman
│   ├── navbar.php        # Navigasi untuk semua halaman
│   ├── footer.php        # Footer untuk semua halaman
│   ├── home.php          # Halaman beranda
│   ├── search.php        # Halaman hasil pencarian
│   ├── peralatan/        # Halaman pengelolaan peralatan
│   │   ├── index.php     # Daftar peralatan
│   │   ├── add.php       # Form tambah peralatan
│   │   ├── edit.php      # Form edit peralatan
│   │   └── delete.php    # Proses hapus peralatan
│   ├── kategori/         # Halaman pengelolaan kategori
│   │   ├── index.php     # Daftar kategori
│   │   ├── add.php       # Form tambah kategori
│   │   ├── edit.php      # Form edit kategori
│   │   └── delete.php    # Proses hapus kategori
│   └── penyewaan/        # Halaman pengelolaan penyewaan
│       ├── index.php     # Daftar penyewaan
│       ├── add.php       # Form tambah penyewaan
│       ├── edit.php      # Form edit penyewaan
│       └── delete.php    # Proses hapus penyewaan
├── index.php             # Entry point aplikasi
└── style.css             # CSS untuk styling aplikasi
```

### Struktur Database
Aplikasi ini menggunakan 5 tabel yang saling berhubungan:

1. **Tabel `kategori`**
   - `id` (Primary Key) - ID kategori
   - `nama` - Nama kategori
   - `deskripsi` - Deskripsi kategori
   - `created_at` - Waktu pembuatan
   - `updated_at` - Waktu terakhir diupdate

2. **Tabel `peralatan`**
   - `id` (Primary Key) - ID peralatan
   - `nama` - Nama peralatan
   - `kategori_id` (Foreign Key) - ID kategori
   - `stok` - Jumlah stok tersedia
   - `harga_sewa` - Harga sewa per hari
   - `deskripsi` - Deskripsi peralatan
   - `status` - Status (tersedia/dipinjam/pemeliharaan)
   - `created_at` - Waktu pembuatan
   - `updated_at` - Waktu terakhir diupdate

3. **Tabel `penyewa`**
   - `id` (Primary Key) - ID penyewa
   - `nama` - Nama penyewa
   - `email` - Email penyewa
   - `telepon` - Nomor telepon
   - `alamat` - Alamat penyewa
   - `ktp` - Nomor KTP
   - `created_at` - Waktu pembuatan
   - `updated_at` - Waktu terakhir diupdate

4. **Tabel `penyewaan`**
   - `id` (Primary Key) - ID penyewaan
   - `penyewa_id` (Foreign Key) - ID penyewa
   - `tanggal_mulai` - Tanggal mulai sewa
   - `tanggal_selesai` - Tanggal selesai sewa
   - `total_harga` - Total harga penyewaan
   - `status` - Status (booking/berjalan/selesai/batal)
   - `catatan` - Catatan tambahan
   - `created_at` - Waktu pembuatan
   - `updated_at` - Waktu terakhir diupdate

5. **Tabel `detail_penyewaan`**
   - `id` (Primary Key) - ID detail penyewaan
   - `penyewaan_id` (Foreign Key) - ID penyewaan
   - `peralatan_id` (Foreign Key) - ID peralatan
   - `jumlah` - Jumlah peralatan yang disewa
   - `harga_sewa` - Harga sewa per unit
   - `subtotal` - Subtotal harga
   - `status` - Status (disewa/dikembalikan/rusak)
   - `created_at` - Waktu pembuatan
   - `updated_at` - Waktu terakhir diupdate

### Relasi Antar Tabel
1. **Kategori → Peralatan** (One-to-Many)
   - Satu kategori dapat memiliki banyak peralatan
   - Foreign key: `peralatan.kategori_id → kategori.id`

2. **Penyewa → Penyewaan** (One-to-Many)
   - Satu penyewa dapat melakukan banyak transaksi penyewaan
   - Foreign key: `penyewaan.penyewa_id → penyewa.id`

3. **Penyewaan → Detail Penyewaan** (One-to-Many)
   - Satu transaksi penyewaan dapat memiliki banyak item peralatan
   - Foreign key: `detail_penyewaan.penyewaan_id → penyewaan.id`

4. **Peralatan → Detail Penyewaan** (One-to-Many)
   - Satu jenis peralatan dapat disewa dalam banyak transaksi
   - Foreign key: `detail_penyewaan.peralatan_id → peralatan.id`

## Alur Program

### Alur Aplikasi Secara Umum
1. User mengakses aplikasi melalui `index.php`
2. `index.php` memproses parameter URL untuk menentukan halaman yang diminta
3. File konfigurasi dan class yang dibutuhkan dimuat
4. Konten halaman yang sesuai ditampilkan (header, navbar, konten utama, footer)

### Alur CRUD Peralatan
1. **Create (Tambah)**
   - User mengakses halaman tambah peralatan
   - User mengisi form dengan data peralatan baru
   - Data divalidasi dan disimpan ke database
   - User diarahkan ke halaman daftar peralatan

2. **Read (Tampil)**
   - User mengakses halaman daftar peralatan
   - Aplikasi mengambil data dari database
   - Data ditampilkan dalam bentuk tabel

3. **Update (Edit)**
   - User mengklik tombol edit pada peralatan tertentu
   - Form edit ditampilkan dengan data peralatan yang dipilih
   - User mengubah data dan mengirimkannya
   - Data divalidasi dan disimpan ke database
   - User diarahkan ke halaman daftar peralatan

4. **Delete (Hapus)**
   - User mengklik tombol hapus pada peralatan tertentu
   - Konfirmasi penghapusan ditampilkan
   - Jika user mengkonfirmasi, data dihapus dari database
   - User diarahkan ke halaman daftar peralatan

### Alur Pencarian
1. User memasukkan kata kunci di form pencarian
2. Aplikasi mencari peralatan yang sesuai dengan kata kunci
3. Hasil pencarian ditampilkan dalam bentuk tabel

### Alur Penyewaan
1. User mengakses halaman tambah penyewaan
2. User memilih peralatan yang akan disewa dan mengisi jumlahnya
3. User mengisi data penyewa dan tanggal penyewaan
4. Sistem menghitung total harga
5. Data penyewaan disimpan ke database
6. Stok peralatan diperbarui secara otomatis

## Dokumentasi Penggunaan

### Instalasi
1. Clone repositori ke web server atau download file ZIP
2. Buat database MySQL dengan nama `camping_rental`
3. Import file `database/setup.sql` untuk membuat struktur tabel dan data awal
4. Sesuaikan konfigurasi database di `config/database.php` (host, username, password)
5. Akses aplikasi melalui browser dengan URL sesuai lokasi instalasi

### Tampilan Halaman Utama
![Halaman Utama](screenshots/home.png)

Halaman utama menampilkan informasi singkat tentang layanan penyewaan peralatan kemah, peralatan terbaru, kategori, dan cara penyewaan.

### Mengelola Peralatan
1. **Melihat Daftar Peralatan**
   - Klik menu "Peralatan" pada navigasi
   - Daftar peralatan akan ditampilkan dalam bentuk tabel
   - Anda dapat memfilter peralatan berdasarkan kategori

2. **Menambah Peralatan Baru**
   - Pada halaman daftar peralatan, klik tombol "Tambah Peralatan"
   - Isi form dengan data peralatan baru
   - Klik "Simpan" untuk menyimpan data

3. **Mengedit Peralatan**
   - Pada daftar peralatan, klik tombol "Edit" pada baris peralatan yang ingin diedit
   - Ubah data sesuai kebutuhan
   - Klik "Simpan" untuk menyimpan perubahan

4. **Menghapus Peralatan**
   - Pada daftar peralatan, klik tombol "Hapus" pada baris peralatan yang ingin dihapus
   - Konfirmasi penghapusan
   - Data akan dihapus dari database

### Mengelola Kategori
1. **Melihat Daftar Kategori**
   - Klik menu "Kategori" pada navigasi
   - Daftar kategori akan ditampilkan dalam bentuk tabel

2. **Menambah Kategori Baru**
   - Pada halaman daftar kategori, klik tombol "Tambah Kategori"
   - Isi form dengan data kategori baru
   - Klik "Simpan" untuk menyimpan data

3. **Mengedit Kategori**
   - Pada daftar kategori, klik tombol "Edit" pada baris kategori yang ingin diedit
   - Ubah data sesuai kebutuhan
   - Klik "Simpan" untuk menyimpan perubahan

4. **Menghapus Kategori**
   - Pada daftar kategori, klik tombol "Hapus" pada baris kategori yang ingin dihapus
   - Konfirmasi penghapusan
   - Data akan dihapus dari database (beserta semua peralatan dalam kategori tersebut)

### Mengelola Penyewaan
1. **Melihat Daftar Penyewaan**
   - Klik menu "Penyewaan" pada navigasi
   - Daftar penyewaan akan ditampilkan dalam bentuk tabel

2. **Membuat Penyewaan Baru**
   - Pada halaman daftar penyewaan, klik tombol "Tambah Penyewaan"
   - Isi form dengan data penyewa
   - Pilih peralatan yang akan disewa dan jumlahnya
   - Tentukan tanggal mulai dan selesai penyewaan
   - Klik "Simpan" untuk menyimpan data

3. **Melihat Detail Penyewaan**
   - Pada daftar penyewaan, klik tombol "Detail" pada baris penyewaan yang ingin dilihat
   - Detail penyewaan beserta item yang disewa akan ditampilkan

4. **Mengubah Status Penyewaan**
   - Pada halaman detail penyewaan, pilih status baru dan klik "Update Status"
   - Status penyewaan akan diperbarui

5. **Mengembalikan Peralatan**
   - Pada halaman detail penyewaan, centang peralatan yang dikembalikan dan klik "Proses Pengembalian"
   - Status peralatan akan diperbarui dan stok akan dikembalikan

### Pencarian
- Gunakan form pencarian di header untuk mencari peralatan berdasarkan nama atau deskripsi
- Hasil pencarian akan ditampilkan dalam bentuk tabel

## Keamanan
Aplikasi ini menerapkan beberapa fitur keamanan:
1. **PDO dan Prepared Statement** - Untuk mencegah SQL Injection
2. **Validasi Input** - Untuk memastikan data yang diinput valid
3. **HTML Escaping** - Untuk mencegah XSS (Cross-Site Scripting)

## Pengembangan Lebih Lanjut
1. Menambahkan sistem autentikasi user (login/logout)
2. Menambahkan fitur upload gambar untuk peralatan
3. Menambahkan fitur laporan dan statistik
4. Mengintegrasikan dengan sistem pembayaran online
5. Mengembangkan notifikasi email untuk konfirmasi penyewaan

## Teknologi yang Digunakan
- PHP 7.4+
- MySQL/MariaDB
- PDO Extension
- HTML5
- CSS3
- Bootstrap 5
- Font Awesome 6
- JavaScript

## Lisensi
Aplikasi ini dibuat untuk tujuan pendidikan dan dapat digunakan secara bebas.
