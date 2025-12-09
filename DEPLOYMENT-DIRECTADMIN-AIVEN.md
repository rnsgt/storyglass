# 🚀 Panduan Deployment Laravel ke DirectAdmin + Aiven Database

Panduan lengkap untuk hosting aplikasi StoryGlass Laravel ke DirectAdmin dengan database MySQL di Aiven.

---

## 📋 Daftar Isi

1. [Persiapan Database di Aiven](#1-persiapan-database-di-aiven)
2. [Persiapan File untuk Upload](#2-persiapan-file-untuk-upload)
3. [Setup di DirectAdmin](#3-setup-di-directadmin)
4. [Upload File ke Server](#4-upload-file-ke-server)
5. [Konfigurasi Database & Environment](#5-konfigurasi-database--environment)
6. [Instalasi Dependencies](#6-instalasi-dependencies)
7. [Setup Storage & Permissions](#7-setup-storage--permissions)
8. [Migrate Database](#8-migrate-database)
9. [Optimasi Production](#9-optimasi-production)
10. [Testing & Troubleshooting](#10-testing--troubleshooting)

---

## 1. Persiapan Database di Aiven

### 1.1 Buat Database MySQL di Aiven

1. Login ke [Aiven Console](https://console.aiven.io/)
2. Klik **Create Service** → Pilih **MySQL**
3. Pil (piliih cloud provider dan regionh yang terdekat dengan server DirectAdmin)
4. Pilih plan (Free/Trial untuk testing, Paid untuk production)
5. Beri nama service: `storyglass-db`
6. Klik **Create Service** dan tunggu hingga status **Running**

### 1.2 Download SSL Certificate

1. Masuk ke service MySQL yang baru dibuat
2. Pergi ke tab **Overview**
3. Download **CA Certificate** (file `ca.pem`)
4. Simpan file ini, akan diupload ke server nanti

### 1.3 Catat Kredensial Database

Di halaman Overview, catat informasi berikut:
- **Host**: `xxx.aivencloud.com`
- **Port**: `xxxxx` (biasanya 5-digit)
- **User**: `avnadmin`
- **Password**: `AVNS_xxxxx`
- **Database**: `defaultdb` atau buat database baru

### 1.4 Whitelist IP Server (Opsional)

Jika menggunakan plan berbayar:
1. Pergi ke tab **Security**
2. Tambahkan IP address server DirectAdmin Anda
3. Save changes

---

## 2. Persiapan File untuk Upload

### 2.1 Build Assets Production

Di komputer lokal, jalankan:

```bash
npm install
npm run build
```

**✅ Pastikan folder `public/build` sudah tergenerate**

### 2.2 Siapkan File .env Production

1. Copy file `.env.production` ke `.env`
2. Edit kredensial Aiven:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=xxx.aivencloud.com
DB_PORT=xxxxx
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=AVNS_xxxxx

MYSQL_ATTR_SSL_CA=/home/username/domains/yourdomain.com/public_html/storage/ssl/ca.pem
MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=false
```
I
**⚠️ Jangan generate APP_KEY dulu, akan dilakukan di server**

### 2.3 Compress Project

**EXCLUDE folder berikut saat compress:**

```
node_modules/
.git/
.env (local development)
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

**File yang HARUS diinclude:**
- ✅ `vendor/` (sudah ada composer dependencies)
- ✅ `public/build/` (hasil npm run build)
- ✅ `.env.production` (template)
- ✅ `storage/ssl/` (akan upload ca.pem nanti)

Compress menjadi `storyglass.zip`

---

## 3. Setup di DirectAdmin

### 3.1 Login ke DirectAdmin

1. Buka `https://your-server-ip:2222` atau domain panel
2. Login dengan kredensial hosting Anda

### 3.2 Buat Domain/Subdomain (jika belum ada)

1. **Account Manager** → **Domain Setup**
2. Tambah domain baru atau subdomain
3. Catat lokasi: `/home/username/domains/yourdomain.com/public_html`

### 3.3 Set PHP Version

1. **Advanced Features** → **PHP Version Selector**
2. Pilih domain Anda
3. Pilih **PHP 8.1** atau lebih tinggi
4. Enable extensions yang dibutuhkan:
   - ✅ PDO
   - ✅ PDO_MySQL
   - ✅ OpenSSL
   - ✅ Mbstring
   - ✅ Tokenizer
   - ✅ XML
   - ✅ Ctype
   - ✅ JSON
   - ✅ BCMath
   - ✅ Fileinfo

---

## 4. Upload File ke Server

### 4.1 Upload via File Manager

1. **File Manager** → Navigate ke folder domain
2. Upload `storyglass.zip`
3. Extract zip file
4. **Penting**: Pindahkan semua file dari folder hasil extract ke root domain

**Struktur akhir harus seperti ini:**

```
/home/username/domains/yourdomain.com/
├── public_html/           ← Document Root (harus folder public Laravel)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
└── .env (akan dibuat nanti)
```

### 4.2 Atur Document Root

**PENTING**: Document root harus mengarah ke folder `public`

1. **Domain Setup** → Pilih domain
2. Set **Document Root** ke: `/home/username/domains/yourdomain.com/public_html`
3. Save

**Atau** pindahkan isi folder `public` ke `public_html`:

```bash
# Via SSH
cd /home/username/domains/yourdomain.com/
mv public/* public_html/
mv public/.htaccess public_html/
```

### 4.3 Upload SSL Certificate

1. Buat folder `storage/ssl/`
2. Upload file `ca.pem` yang didownload dari Aiven ke folder ini
3. Set permission: `644` (readable)

---

## 5. Konfigurasi Database & Environment

### 5.1 Buat File .env

Via File Manager atau SSH, buat file `.env` di root project:

```env
APP_NAME=StoryGlass
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=storyglass-xxx.aivencloud.com
DB_PORT=27963
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=AVNS_xxxxx

MYSQL_ATTR_SSL_CA=/home/username/domains/yourdomain.com/storage/ssl/ca.pem
MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=false

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5.2 Generate Application Key

Via SSH Terminal di DirectAdmin:

```bash
cd /home/username/domains/yourdomain.com
php artisan key:generate
```

**✅ APP_KEY akan otomatis terisi di .env**

---

## 6. Instalasi Dependencies

### 6.1 Install Composer Dependencies (jika belum)

Jika folder `vendor/` belum ada atau perlu update:

```bash
cd /home/username/domains/yourdomain.com
php composer.phar install --optimize-autoloader --no-dev
```

**Note**: Gunakan `composer.phar` jika composer global tidak tersedia

### 6.2 Download Composer (jika belum ada)

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install --optimize-autoloader --no-dev
```

---

## 7. Setup Storage & Permissions

### 7.1 Set Permissions

```bash
cd /home/username/domains/yourdomain.com

# Storage
chmod -R 755 storage
chmod -R 755 storage/framework
chmod -R 755 storage/logs

# Bootstrap cache
chmod -R 755 bootstrap/cache
```

### 7.2 Create Storage Link

```bash
php artisan storage:link
```

**✅ Ini akan membuat symlink dari `public/storage` → `storage/app/public`**

---

## 8. Migrate Database

### 8.1 Test Koneksi Database

```bash
php artisan tinker
DB::connection()->getPdo();
exit
```

**✅ Jika tidak error, koneksi berhasil**

### 8.2 Run Migrations

```bash
php artisan migrate --force
```

**Parameter `--force` diperlukan karena APP_ENV=production**

### 8.3 Seed Data (Opsional)

```bash
php artisan db:seed --force
```

---

## 9. Optimasi Production

### 9.1 Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9.2 Optimize Autoloader

```bash
php artisan optimize
```

### 9.3 Setup Scheduled Tasks (Cron Job)

Di DirectAdmin → **Advanced Features** → **Cron Jobs**

Tambah cron job:

```bash
* * * * * cd /home/username/domains/yourdomain.com && php artisan schedule:run >> /dev/null 2>&1
```

---

## 10. Testing & Troubleshooting

### 10.1 Test Website

Buka browser dan akses: `https://yourdomain.com`

**✅ Harus muncul homepage Laravel**

### 10.2 Common Issues & Solutions

#### ❌ Error 500 - Internal Server Error

**Solusi:**

```bash
# Check logs
tail -n 50 storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache
```

#### ❌ Database Connection Error

**Cek:**
1. Kredensial database di `.env` benar
2. SSL certificate path benar
3. IP server sudah di-whitelist di Aiven (untuk paid plan)

**Test koneksi:**

```bash
php artisan tinker
DB::connection()->getPdo();
```

#### ❌ Permission Denied

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R username:username storage bootstrap/cache
```

#### ❌ 404 Not Found / Routes tidak bekerja

**Cek:**
1. File `.htaccess` ada di folder `public` (atau `public_html`)
2. Mod_rewrite enabled di Apache
3. Document root sudah benar

**File `.htaccess` root (jika document root tidak bisa diubah):**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### ❌ CSS/JS Not Loading

**Solusi:**
1. Pastikan folder `public/build` ada
2. Cek APP_URL di `.env` sesuai domain
3. Regenerate manifest:

```bash
php artisan view:clear
npm run build
```

---

## 📝 Checklist Deployment

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

- [ ] Database MySQL dibuat di Aiven
- [ ] SSL Certificate (ca.pem) didownload
- [ ] Assets production sudah di-build (`npm run build`)
- [ ] File project dicompress (exclude node_modules, .git)
- [ ] PHP version 8.1+ di DirectAdmin
- [ ] PHP extensions required sudah enabled
- [ ] File project diupload dan extract
- [ ] Document root mengarah ke folder `public`
- [ ] File `ca.pem` diupload ke `storage/ssl/`
- [ ] File `.env` dibuat dengan kredensial production
- [ ] APP_KEY sudah digenerate
- [ ] Composer dependencies terinstall
- [ ] Storage permissions sudah diset (755)
- [ ] Storage link sudah dibuat
- [ ] Database migration berhasil
- [ ] Config, route, view cache sudah dibuat
- [ ] Cron job untuk scheduler sudah disetup
- [ ] Website bisa diakses dan berfungsi normal

---

## 🔧 Maintenance Commands

### Update Code

```bash
cd /home/username/domains/yourdomain.com

# Backup database dulu
php artisan migrate:status

# Pull/upload code baru
# ...

# Update composer
php composer.phar install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear & rebuild cache
php artisan optimize:clear
php artisan optimize
```

### Clear All Cache

```bash
php artisan optimize:clear
# atau
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### View Logs

```bash
tail -f storage/logs/laravel.log
```

---

## 📞 Support

Jika masih mengalami masalah:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Apache error logs via DirectAdmin
3. Enable debug mode sementara: `APP_DEBUG=true` di `.env`
4. Test koneksi database dengan `php artisan tinker`

---

**✅ Deployment Selesai!**

Website Laravel Anda sekarang sudah live di DirectAdmin dengan database Aiven MySQL.
