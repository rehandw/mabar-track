# ⚡ MabarTrack - Sistem Manajemen Antrean Mabar Streamer Gaming

Aplikasi web berbasis **Laravel 12 + Livewire + Tailwind CSS** yang dirancang khusus untuk memecahkan masalah streamer gaming di Indonesia saat membuka jasa mabar (*carry / play-along*) berbayar via **Saweria**.

---

## 🚀 Fitur Unggulan

1. **Streamer Control Center (HUD Cockpit):**
   * **1-Click Decrement:** Tombol besar `-1 Match Selesai` dengan database lock atomic (mencegah race condition & selisih hitung).
   * **⚡ Fast Track VIP:** Opsi prioritas jalur cepat untuk donasi nominal lebih tinggi, otomatis melompati antrean reguler ke posisi terdepan.
   * **+ Tambah Match Ekstra:** Kemampuan menambah match (+1, +2, +3, +N) pada pemain aktif, antrean, maupun match yang sudah selesai.
   * **Salin ID Instan:** 1-klik untuk menyalin In-Game ID / Server pemain ke clipboard tanpa alt-tab lama.
   * **Audit Log Riwayat:** Mencatat timestamp setiap kali sisa match dikurangi/ditambah dan siapa yang melakukan aksi untuk mencegah komplain viewer.
   * **Simulasi Saweria di Web:** Streamer/Mod dapat menguji format pesan dan donasi langsung dari pop-up modal tanpa uang sungguhan.
   * **Antrean Menunggu & Prioritas:** Panggil antrean berikutnya langsung menjadi pemain aktif dengan satu klik.

2. **OBS Live Stream Overlay (`/overlay/{token}`):**
   * Tampilan khusus berlatar belakang transparan (`bg-transparent`) dengan efek neon glow.
   * Dirancang langsung sebagai **Browser Source di OBS Studio / Streamlabs**.
   * Realtime polling otomatis memperbarui status pemain aktif dan sisa game di layar siaran.

3. **Public Queue Tracker (`/queue`):**
   * Halaman pelacak antrean ramah smartphone untuk dibagikan link-nya ke penonton di live chat.
   * Penonton dapat mengetik ID Game / nama mereka untuk mengecek posisi nomor urut dan estimasi sisa game secara mandiri tanpa spam chat.

4. **Saweria Webhook Integration (`/api/saweria/webhook`):**
   * Menerima notifikasi otomatis saat donasi masuk.
   * Regex Parser mendeteksi kode game (MLBB, Valorant, Free Fire, HOK), In-Game ID, dan menghitung otomatis jumlah game berdasarkan tarif per match.

---

## 🛠️ Cara Menjalankan Aplikasi

Pastikan terminal berada di direktori proyek:
```bash
cd D:\project\mabar-track
```

### Konfigurasi Database (.env):
Konfigurasikan database MySQL pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mabar-track
DB_USERNAME=root
DB_PASSWORD=
```
Lalu jalankan migrasi & data awal:
```bash
php artisan migrate --seed
```

### 1. Jalankan Server Web:
```bash
php artisan serve
```
Aplikasi akan aktif di `http://127.0.0.1:8000`.

### 2. Jalankan Build Aset (Opsional / Mode Dev):
Aset frontend sudah ter-*build* secara produksi di folder `public/build`. Jika ingin melakukan perubahan CSS atau JS:
```bash
npm run dev
```

---

## 🔗 Rute & Endpoint Utama

| Halaman / Fitur | URL / Endpoint | Keterangan |
| :--- | :--- | :--- |
| **Streamer Dashboard** | `http://127.0.0.1:8000/` | Dashboard kontrol utama streamer & moderator |
| **Layar OBS Overlay** | `http://127.0.0.1:8000/overlay/{token}` | Dimasukkan ke OBS Studio (Browser Source) |
| **Cek Antrean Publik** | `http://127.0.0.1:8000/queue` | Dibagikan kepada penonton di live chat |
| **Webhook Saweria** | `http://127.0.0.1:8000/api/saweria/webhook` | Menerima payload donasi Saweria |

---

## 🧪 Menjalankan Automated Test

Aplikasi telah dilengkapi unit & feature test suite lengkap:
```bash
php artisan test
```
