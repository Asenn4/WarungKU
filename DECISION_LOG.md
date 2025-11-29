# Decision Log - WarungKU

> Dokumentasi ini mencatat semua keputusan teknis dan arsitektural yang dibuat dalam pengembangan WarungKU. Tujuannya untuk memberikan konteks kepada developer baru atau maintainer tentang "mengapa" keputusan tertentu dibuat.

---

## 📋 Daftar Isi

- [Technology Stack](#technology-stack)
  - [Laravel vs Next.js/Node.js](#1-mengapa-laravel-bukan-nextjs-atau-nodejs)
  - [MySQL vs Database Lain](#2-mengapa-mysql-bukan-database-lain)
- [Architecture & Design](#architecture--design)
  - [Pendekatan UI/UX](#3-mengapa-pendekatan-uiux-ini)
  - [Authentication Strategy](#4-mengapa-email--password-bukan-anonymous-auth)
- [Frontend Stack](#frontend-stack)
  - [Tailwind CSS](#5-mengapa-tailwind-css-bukan-framework-css-lain)
  - [Laravel Breeze](#6-mengapa-laravel-breeze-bukan-package-auth-lain)

---

## Technology Stack

### 1. Mengapa Laravel (Bukan Next.js atau Node.js)?

**Keputusan:** Menggunakan **Laravel 12** sebagai backend framework utama.

#### Pertimbangan

**✅ Alasan Memilih Laravel:**

1. **Ekosistem Lengkap & Stabil**
   - Laravel menyediakan banyak fitur bawaan seperti:
     - Routing system yang powerful
     - Artisan CLI untuk automation
     - Queue system untuk background jobs
     - Mail system untuk notifikasi
     - Authentication & authorization bawaan
   - Mempercepat development tanpa perlu banyak setup manual atau instalasi package tambahan

2. **Performa Cukup untuk Skala UMKM**
   - Cocok untuk WarungKU yang membutuhkan operasi CRUD cepat
   - Dapat menangani proses transaksi ringan sampai menengah dengan baik
   - Caching bawaan (Redis, Memcached) siap digunakan untuk optimasi

3. **Kemudahan untuk Tim**
   - Dokumentasi sangat lengkap dan terstruktur rapi
   - Banyak tutorial dalam bahasa Indonesia
   - Mudah dipahami oleh developer pemula hingga menengah
   - Learning curve yang tidak terlalu curam

4. **Deployment yang Mudah**
   - Bisa di-deploy ke shared hosting sederhana
   - Tidak memerlukan infrastruktur kompleks seperti Node.js yang butuh PM2/Docker
   - Cocok untuk target user (UMKM) yang mungkin budget-limited

#### Alternatif yang Dipertimbangkan

| Framework | Kelebihan | Alasan Tidak Dipilih |
|-----------|-----------|---------------------|
| **Next.js** | - Modern & fast<br>- SEO friendly<br>- React ecosystem | - Overkill untuk POS sederhana<br>- Butuh Node.js hosting (lebih mahal)<br>- Learning curve lebih tinggi |
| **Node.js + Express** | - Cepat & ringan<br>- JavaScript full-stack | - Harus build dari nol (no batteries included)<br>- Lebih banyak boilerplate code<br>- Hosting lebih kompleks |
| **CodeIgniter** | - Ringan & cepat<br>- Dokumentasi Indonesia bagus | - Fitur kurang lengkap<br>- Ekosistem lebih kecil<br>- Kurang modern (PHP versi lama) |

#### Kesimpulan

Laravel dipilih karena memberikan **balance terbaik** antara:
- ⚡ Kecepatan development
- 📦 Kelengkapan fitur
- 💰 Biaya deployment
- 👥 Kemudahan tim

---

### 2. Mengapa MySQL (Bukan Database Lain)?

**Keputusan:** Menggunakan **MySQL** sebagai database management system.

#### Pertimbangan

**✅ Alasan Memilih MySQL:**

1. **Stabil untuk Aplikasi Transaksional**
   - MySQL sudah terbukti kuat untuk operasi CRUD yang cepat dan intens
   - ACID compliance yang baik untuk data transaksi keuangan
   - Transaction locking yang reliable untuk mencegah race condition di kasir

2. **Integrasi Sempurna dengan Laravel**
   - Laravel memiliki dukungan bawaan yang sangat matang untuk MySQL
   - Eloquent ORM bekerja optimal dengan MySQL
   - Migration dan seeding sangat smooth
   - Query builder sudah di-optimize untuk MySQL

3. **Ketersediaan Hosting Universal**
   - Hampir semua shared hosting menyediakan MySQL secara default
   - Tidak perlu setup tambahan di hosting murah
   - Panel control seperti cPanel/Plesk sudah include phpMyAdmin
   - Cocok untuk target deployment UMKM

4. **Komunitas & Dokumentasi Besar**
   - Sangat mudah menemukan tutorial dan solusi error
   - Stack Overflow penuh dengan jawaban MySQL
   - Banyak tools gratis (MySQL Workbench, phpMyAdmin, Adminer)

5. **Performa Excellent untuk Skala Kecil-Menengah**
   - Cocok untuk aplikasi UMKM dengan traffic moderat
   - Dapat menangani ribuan transaksi per hari dengan mudah
   - Indexing yang powerful untuk query cepat
   - Join performance sangat baik

#### Alternatif yang Dipertimbangkan

| Database | Kelebihan | Alasan Tidak Dipilih |
|----------|-----------|---------------------|
| **PostgreSQL** | - Fitur lebih advanced<br>- JSON support lebih baik<br>- Open source murni | - Jarang tersedia di shared hosting murah<br>- Overkill untuk kebutuhan WarungKU<br>- Learning curve lebih tinggi |
| **MongoDB** | - Flexible schema<br>- Horizontal scaling mudah | - Tidak cocok untuk data transaksional<br>- No ACID guarantee (versi lama)<br>- Laravel support kurang mature |
| **SQLite** | - Zero configuration<br>- Tidak butuh server | - Tidak cocok untuk multi-user (kasir ganda)<br>- Performance issue di concurrent writes<br>- Tidak scalable |

#### Struktur Data yang Cocok untuk MySQL

```sql
-- Relational data structure cocok untuk:
- Produk & Kategori (one-to-many)
- Transaksi & Detail Transaksi (one-to-many)
- User & Transaksi (one-to-many)
- Referential integrity dengan foreign keys
```

#### Kesimpulan

MySQL dipilih karena:
- 🎯 **Perfect fit** untuk use case POS/transaksional
- 💰 **Cost-effective** untuk deployment
- 🚀 **Mature integration** dengan Laravel
- 🛠️ **Easy maintenance** dan troubleshooting

---

## Architecture & Design

### 3. Mengapa Pendekatan UI/UX Ini?

**Keputusan:** Menerapkan **minimalist & task-focused UI/UX design**.

#### Pertimbangan

**✅ Alasan Pendekatan Ini:**

1. **Dashboard Ringkas & Actionable**
   - Fokus pada informasi yang **benar-benar penting** untuk pemilik warung:
     - 💰 Omzet hari ini (angka besar, jelas)
     - 📦 Stok menipis (alert merah, butuh action)
     - 📊 Grafik omzet 7 hari terakhir (trend overview)
   - Pemilik warung bisa **langsung ambil keputusan** tanpa scroll panjang
   - Menggunakan kartu-kartu (cards) untuk grouping informasi

2. **Navigasi Super Sederhana**
   ```
   Dashboard → Kasir → Produk → Riwayat
        ↓        ↓       ↓        ↓
     Overview  POS    Inventory History
   ```
   - Hanya 4 menu utama di sidebar
   - Mengurangi cognitive load dan kebingungan
   - User tidak perlu "belajar" navigasi

3. **Halaman Kasir: Speed is King**
   - Interface minimalis dengan 2 area utama:
     - 🔍 Search bar produk (autocomplete)
     - 🛒 Keranjang belanja (real-time update)
   - **Workflow cepat:**
     1. Ketik/scan produk → Enter
     2. Adjust quantity (jika perlu)
     3. Klik "Bayar"
   - Tidak ada distraksi: no sidebar, no header kompleks
   - Full-screen mode untuk fokus maksimal

4. **Manajemen Produk: Table-First Approach**
   - Semua info penting dalam 1 tabel:
     - SKU | Nama | Kategori | Harga | Stok | Aksi
   - **Tidak perlu klik detail** untuk info dasar
   - Inline editing (click to edit) untuk update cepat
   - Bulk actions untuk efisiensi (hapus banyak, export)

5. **Prinsip Design yang Diterapkan**
   - **Progressive Disclosure:** Info kompleks disembunyikan di dalam modal/dropdown
   - **Consistency:** Tombol, warna, spacing konsisten di semua halaman
   - **Feedback:** Loading state, success/error message yang jelas
   - **Accessibility:** Ukuran font besar, kontras tinggi, keyboard shortcuts

#### User Research Findings

Berdasarkan observasi target user (pemilik warung):

| Pain Point | Solusi di WarungKU |
|------------|-------------------|
| "Aplikasi kasir ribet, banyak tombol" | Kasir cuma ada search + keranjang |
| "Saya ga paham istilah teknis" | Pakai bahasa Indonesia, istilah familiar |
| "Saya butuh lihat omzet hari ini cepat" | Dashboard ada card besar "Omzet Hari Ini" |
| "Aplikasi lain lambat, sering loading" | Optimasi query, caching, lazy loading |

#### Kesimpulan

UI/UX ini dipilih karena:
- 🎯 **Task-oriented**: Setiap halaman punya tujuan jelas
- ⚡ **Speed-focused**: Minimal clicks untuk complete task
- 👴 **Age-friendly**: Mudah digunakan semua umur
- 📱 **Mobile-ready**: Responsive untuk tablet kasir

---

### 4. Mengapa Email & Password (Bukan Anonymous Auth)?

**Keputusan:** Menggunakan **traditional email/password authentication**.

#### Pertimbangan

**✅ Alasan Memilih Email/Password:**

1. **Laravel Breeze Sudah Siap Pakai**
   - Template login, register, reset password sudah tersedia
   - Tidak perlu coding dari nol
   - Hashing password (bcrypt) sudah di-handle Laravel
   - Remember me, session management sudah built-in

2. **Cocok untuk Use Case Warung**
   - Admin/pemilik warung **butuh kontrol akses**
   - Kasir yang berbeda harus punya akun sendiri untuk tracking
   - Setiap transaksi tercatat siapa yang melakukan (audit trail)
   - Pemilik bisa nonaktifkan akun kasir yang sudah resign

3. **Tidak Butuh Infrastruktur Tambahan**
   - Cukup tabel `users` di database
   - Tidak perlu OAuth provider (Google, Facebook) yang ribet setup
   - Tidak perlu API key dari third-party service

4. **Familiar untuk Target User**
   - Kasir/admin warung sudah terbiasa dengan email/password
   - Tidak perlu edukasi tentang authentication method yang aneh
   - Proses reset password mudah dipahami (link ke email)

5. **Security yang Cukup**
   - Password hashing dengan bcrypt (cost 10)
   - CSRF protection bawaan Laravel
   - Rate limiting untuk brute force protection
   - Session-based auth (secure untuk internal use)

#### Alternatif yang Dipertimbangkan

| Auth Method | Kelebihan | Alasan Tidak Dipilih |
|-------------|-----------|---------------------|
| **Anonymous Auth** | - No login friction<br>- Cepat mulai pakai | - Tidak bisa tracking siapa yang transaksi<br>- Tidak ada kontrol akses<br>- Masalah audit/accountability |
| **OAuth (Google/FB)** | - User ga perlu ingat password<br>- Secure | - Overkill untuk internal app<br>- Perlu internet untuk login<br>- Setup OAuth ribet |
| **SMS OTP** | - Passwordless<br>- High security | - Biaya SMS gateway<br>- Perlu internet/sinyal<br>- Lambat (tunggu SMS) |
| **Biometric (Fingerprint)** | - Super secure<br>- User-friendly | - Butuh hardware khusus<br>- Mahal implementasi<br>- Not supported semua device |

#### Role-Based Access Control (RBAC)

```php
// Implementasi sederhana di WarungKU
if (auth()->user()->role === 'admin') {
    // Bisa akses manajemen produk, laporan, settings
}

if (auth()->user()->role === 'kasir') {
    // Hanya bisa akses POS dan lihat produk
}
```

#### Kesimpulan

Email/password dipilih karena:
- 🛠️ **Out of the box** dengan Laravel Breeze
- 🎯 **Fit dengan use case** (multi-user, audit trail)
- 💰 **Zero cost** (no third-party service)
- 👥 **User-friendly** untuk target audience

---

## Frontend Stack

### 5. Mengapa Tailwind CSS (Bukan Framework CSS Lain)?

**Keputusan:** Menggunakan **Tailwind CSS** untuk styling.

#### Pertimbangan

**✅ Alasan Memilih Tailwind CSS:**

1. **Utility-First Approach**
   - Tidak perlu menulis custom CSS untuk styling umum
   - Class langsung di HTML: `<button class="bg-blue-500 text-white px-4 py-2 rounded">`
   - Faster development, tidak bolak-balik file CSS

2. **Tampilan Konsisten**
   - Design system built-in (spacing scale, color palette, typography)
   - Spacing: `p-4` = 1rem, `p-8` = 2rem (konsisten di semua komponen)
   - Colors: `blue-500`, `red-600` (shade yang konsisten)
   - Tidak ada "magic numbers" seperti `margin: 23px`

3. **Banyak Komponen Siap Pakai**
   - Tailwind UI (berbayar) atau Flowbite (gratis)
   - Button, card, modal, table sudah ada template
   - Copy-paste lalu customize sesuai kebutuhan

4. **Responsive Design Mudah**
   ```html
   <div class="text-sm md:text-base lg:text-lg">
     Otomatis responsive tanpa media query manual
   </div>
   ```
   - Breakpoints built-in: `sm:`, `md:`, `lg:`, `xl:`
   - Mobile-first approach by default

5. **Production Build Kecil**
   - PurgeCSS otomatis remove unused classes
   - CSS final size bisa < 10KB (gzipped)
   - Faster loading time untuk user

6. **Integrasi Laravel Breeze**
   - Breeze sudah include Tailwind + Vite config
   - Tidak perlu setup manual
   - Hot reload works out of the box

#### Alternatif yang Dipertimbangkan

| Framework | Kelebihan | Alasan Tidak Dipilih |
|-----------|-----------|---------------------|
| **Bootstrap** | - Banyak komponen ready<br>- Dokumentasi lengkap<br>- Familiar | - File size lebih besar<br>- Design terlihat "bootstrap-y"<br>- Customization lebih ribet |
| **Bulma** | - Pure CSS (no JS)<br>- Syntax clean | - Komunitas lebih kecil<br>- Komponen kurang banyak<br>- Kurang populer (susah cari help) |
| **Material UI (CSS)** | - Google Material Design<br>- Modern look | - Lebih cocok untuk React<br>- Bundle size besar<br>- Overkill untuk Laravel |
| **Custom CSS** | - Full control<br>- Belajar CSS murni | - Sangat lambat development<br>- Sulit maintain consistency<br>- Reinvent the wheel |

#### Best Practices yang Diterapkan

1. **Component Classes (Extracting Patterns)**
   ```css
   /* resources/css/app.css */
   @layer components {
     .btn-primary {
       @apply bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600;
     }
   }
   ```

2. **Responsive Strategy**
   - Mobile-first: Design untuk mobile dulu, baru scale up
   - Breakpoints: `sm: 640px`, `md: 768px`, `lg: 1024px`

3. **Dark Mode Ready**
   ```html
   <div class="bg-white dark:bg-gray-800">
     (Siap untuk dark mode di masa depan)
   </div>
   ```

#### Kesimpulan

Tailwind CSS dipilih karena:
- ⚡ **Rapid development** dengan utility classes
- 🎨 **Consistent design** dengan design system built-in
- 🔧 **Easy customization** tanpa fight framework
- 📦 **Small bundle size** setelah purge
- 🤝 **Perfect match** dengan Laravel Breeze

---

### 6. Mengapa Laravel Breeze (Bukan Package Auth Lain)?

**Keputusan:** Menggunakan **Laravel Breeze** untuk authentication scaffolding.

#### Pertimbangan

**✅ Alasan Memilih Laravel Breeze:**

1. **Official & Lightweight**
   - Package resmi dari Laravel team
   - Hanya menyediakan essentials, tidak bloated
   - Bukan "black box" seperti Jetstream
   - Kode bisa dibaca dan dimodifikasi dengan mudah

2. **Authentication Features yang Cukup**
   - ✅ Login
   - ✅ Register
   - ✅ Forgot password (email reset link)
   - ✅ Email verification (opsional)
   - ✅ Password confirmation (untuk aksi sensitif)
   - **Tidak ada fitur overkill** yang ga kepake

3. **Cocok untuk Skala Kecil-Menengah**
   - Perfect untuk WarungKU yang cuma butuh basic auth
   - Tidak perlu two-factor authentication (2FA) untuk kasir warung
   - Tidak perlu API tokens (Sanctum) karena bukan API-first app
   - Tidak perlu teams/multi-tenancy (Jetstream feature)

4. **Struktur Folder Rapi**
   ```
   app/Http/Controllers/Auth/
   ├── AuthenticatedSessionController.php    (Login)
   ├── RegisteredUserController.php          (Register)
   ├── PasswordResetLinkController.php       (Forgot password)
   └── ...
   
   resources/views/auth/
   ├── login.blade.php
   ├── register.blade.php
   └── ...
   ```
   - Semua kode visible dan bisa diubah
   - Tidak ada "magic" atau abstraction berlapis

5. **Tailwind CSS Template Built-in**
   - Breeze sudah include Blade views dengan Tailwind
   - Design modern dan clean
   - Responsive out of the box
   - Tinggal customize warna/logo

6. **Vite Integration**
   - Asset bundling dengan Vite (faster than Webpack)
   - Hot reload saat development
   - Optimized build untuk production

#### Alternatif yang Dipertimbangkan

| Package | Kelebihan | Alasan Tidak Dipilih |
|---------|-----------|---------------------|
| **Laravel Jetstream** | - Fitur lengkap (2FA, teams, API)<br>- Livewire/Inertia stack<br>- Professional UI | - **Terlalu kompleks** untuk WarungKU<br>- Banyak fitur yang ga kepake<br>- Learning curve lebih tinggi<br>- Susah customize karena banyak abstraction |
| **Laravel Fortify** | - Backend-only (headless)<br>- Flexible frontend | - Harus build UI sendiri dari nol<br>- Tidak ada Blade template<br>- Overkill kalau cuma butuh basic auth |
| **Laravel UI** | - Support Bootstrap/Vue/React<br>- Legacy package | - **Deprecated** (ga diupdate lagi)<br>- Pakai Webpack (lambat)<br>- Dokumentasi outdated |
| **Spatie Laravel Permission** | - Role & permission advanced<br>- Database-driven RBAC | - Bukan auth scaffolding (perlu combo dengan Breeze/Jetstream)<br>- Overkill untuk 2 role (admin, kasir) |
| **Manual Auth (from scratch)** | - Full control<br>- Learn everything | - **Sangat lambat** development<br>- Rawan security issue<br>- Reinvent the wheel |

#### Breeze Stacks yang Tersedia

Breeze punya beberapa stack:

```bash
# 1. Blade + Tailwind (yang kita pilih)
php artisan breeze:install blade

# 2. Livewire + Alpine
php artisan breeze:install livewire

# 3. React + Inertia
php artisan breeze:install react

# 4. Vue + Inertia
php artisan breeze:install vue
```

**Kita pilih Blade + Tailwind karena:**
- Paling simple (no JavaScript framework overhead)
- Cukup untuk SSR (server-side rendering) app
- Alpine.js sudah include untuk interaktivitas kecil

#### Customization yang Dilakukan

1. **Menambahkan Role ke Migration**
   ```php
   Schema::create('users', function (Blueprint $table) {
       $table->id();
       $table->string('name');
       $table->string('email')->unique();
       $table->string('password');
       $table->enum('role', ['admin', 'kasir'])->default('kasir'); // ← tambahan
       $table->timestamps();
   });
   ```

2. **Menambahkan Role Middleware**
   ```php
   // app/Http/Middleware/CheckRole.php
   public function handle($request, Closure $next, $role) {
       if ($request->user()->role !== $role) {
           abort(403);
       }
       return $next($request);
   }
   ```

3. **Protected Routes**
   ```php
   Route::middleware(['auth', 'role:admin'])->group(function () {
       Route::resource('products', ProductController::class);
   });
   ```

#### Kesimpulan

Laravel Breeze dipilih karena:
- 🪶 **Lightweight** tapi complete untuk kebutuhan WarungKU
- 📖 **Easy to understand** karena kode transparan
- 🎨 **Tailwind included** (sesuai stack kita)
- 🚀 **Fast setup** (install → migrate → ready)
- 🔧 **Easy to customize** untuk role-based auth

---

## 📝 Notes untuk Developer Baru

Jika Anda baru bergabung di project WarungKU:

1. **Baca dokumentasi ini dulu** sebelum coding
2. **Pahami "why"** di balik setiap keputusan, bukan hanya "how"
3. **Jika ingin mengubah stack**, diskusikan dulu dengan tim dan update decision log ini
4. **Dokumentasi ini living document**, update jika ada keputusan baru

---

## 🔄 Change History

| Date | Decision | Author | Reason |
|------|----------|--------|--------|
| 2024-11-29 | Initial decision log | [Ri.W.A.Ra] | Dokumentasi awal keputusan teknis |

---

<div align="center">
  <p><strong>Remember:</strong> Every decision is a trade-off. <br>We chose what's best for WarungKU's context, not what's "best" in absolute.</p>
</div>