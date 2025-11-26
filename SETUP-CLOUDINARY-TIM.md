# 🚀 Setup Cloudinary untuk Tim StoryGlass

## 📋 Langkah-Langkah Setup (Wajib untuk Semua Tim)

### 1️⃣ Pull Code Terbaru

```bash
git pull origin main
composer install
```

### 2️⃣ Daftar/Login Cloudinary

**Pilih salah satu**:

#### A. Pakai Akun Bersama (Rekomendasi) ⭐
- Minta credentials dari ketua tim
- Skip ke langkah 3

#### B. Buat Akun Sendiri
1. Buka: https://cloudinary.com/users/register_free
2. Daftar dengan email
3. Verify email
4. Login ke Dashboard

### 3️⃣ Copy Credentials

Setelah login ke **Cloudinary Dashboard**:

1. Klik menu **Dashboard** (icon home)
2. Lihat section **Account Details**
3. Copy 3 informasi ini:
   - **Cloud Name**: `dxxxxx`
   - **API Key**: `123456789012345`
   - **API Secret**: `abcdefghijklmnopqrstuvwxyz`

### 4️⃣ Update File .env

Buka file `.env` di root project, tambahkan:

```env
# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=dxxxxx
CLOUDINARY_API_KEY=123456789012345
CLOUDINARY_API_SECRET=abcdefghijklmnopqrstuvwxyz
CLOUDINARY_URL=cloudinary://123456789012345:abcdefghijklmnopqrstuvwxyz@dxxxxx
```

**⚠️ PENTING**: 
- Ganti `dxxxxx`, `123456789012345`, dll dengan credentials Anda
- **Jangan commit file `.env`** ke Git!
- Jika pakai akun bersama, **gunakan credentials yang sama** untuk semua tim

### 5️⃣ Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 6️⃣ Test Upload

1. Jalankan aplikasi: `php artisan serve`
2. Login sebagai **admin**
3. Buka menu **Produk** → **Tambah Produk**
4. Upload gambar produk
5. Klik **Simpan**

**✅ Jika berhasil**:
- Produk muncul dengan gambar
- Cek URL gambar: harus mulai dengan `https://res.cloudinary.com/...`
- Tim lain langsung bisa lihat gambar (tanpa git pull)

**❌ Jika gagal**:
- Cek `storage/logs/laravel.log`
- Pastikan credentials benar
- Cek koneksi internet

---

## 🎯 Cara Kerja

### Sebelum (Pakai Folder Lokal)
```
Admin A upload gambar → Tersimpan di public/image/
                      → Admin B tidak bisa lihat (harus git pull)
```

### Sekarang (Pakai Cloudinary)
```
Admin A upload gambar → Upload ke Cloudinary
                      → URL tersimpan di database
                      → Admin B langsung bisa lihat! ✅
```

---

## 📝 FAQ

### Q: Apakah harus daftar Cloudinary sendiri?
A: Tidak. Bisa pakai 1 akun untuk semua tim (rekomen).

### Q: Berapa biaya Cloudinary?
A: **GRATIS** untuk:
- 25 GB storage
- 25 GB bandwidth/bulan
- Unlimited transformations

### Q: Apa yang tersimpan di database?
A: **URL gambar**, contoh:
```
https://res.cloudinary.com/dxxxxx/image/upload/v1234567890/storyglass/products/abc123.jpg
```

### Q: Bagaimana dengan gambar lama di folder public/image?
A: Gambar lama tetap ada, tapi **tidak akan muncul** di aplikasi. Bisa upload ulang atau migrate manual (lihat SOLUSI-CLOUD-STORAGE.md).

### Q: Apakah bisa upload gambar tanpa internet?
A: **Tidak**. Upload ke Cloudinary butuh koneksi internet.

### Q: Bagaimana jika quota Cloudinary habis?
A: 
1. Upgrade ke paid plan ($0.3/GB)
2. Atau hapus gambar lama yang tidak terpakai via dashboard

---

## 🆘 Troubleshooting

### Error: "Missing CLOUDINARY_CLOUD_NAME"
```bash
# Pastikan sudah tambahkan ke .env
php artisan config:clear
```

### Error: "Upload failed" / "Invalid signature"
- Cek API Key & Secret benar
- Cek tidak ada spasi di credentials
- Cek koneksi internet

### Gambar tidak muncul (broken image)
- Buka URL gambar di browser
- Jika 404: gambar belum terupload ke Cloudinary
- Jika forbidden: cek API credentials

### Log error di `storage/logs/laravel.log`
```bash
# Lihat log terbaru
tail -f storage/logs/laravel.log

# Atau di Windows
Get-Content storage/logs/laravel.log -Tail 20
```

---

## 🎓 Next Steps

1. ✅ Setup Cloudinary di `.env`
2. ✅ Test upload 1 produk
3. ✅ Konfirmasi ke tim bahwa sudah berhasil
4. 🚀 Mulai upload produk baru!

---

**Butuh bantuan?** Kontak ketua tim atau cek file `SOLUSI-CLOUD-STORAGE.md`
