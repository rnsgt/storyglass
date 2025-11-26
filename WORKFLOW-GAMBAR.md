# Workflow Upload Gambar Produk

## 📸 Setelah Menambah/Edit Produk dengan Gambar

1. **Cek file baru**:
   ```bash
   git status public/image/
   ```

2. **Tambahkan ke git**:
   ```bash
   git add public/image/
   ```

3. **Commit**:
   ```bash
   git commit -m "Add/Update product images"
   ```

4. **Pull perubahan tim** (jika ada):
   ```bash
   git pull origin main --rebase
   ```

5. **Push ke GitHub**:
   ```bash
   git push origin main
   ```

## ⚠️ Catatan Penting

- **Selalu pull dulu** sebelum push jika tim lain juga upload gambar
- **Jangan hapus** file gambar lama tanpa koordinasi
- **Size limit**: Maksimal 2MB per gambar (sudah diatur di validasi)

## 🆘 Troubleshooting

### Tim tidak lihat gambar saya?
```bash
# Cek apakah gambar sudah di-commit
git log --oneline --name-only | grep "public/image"

# Jika belum, lakukan langkah 2-5 di atas
```

### Conflict saat push?
```bash
git pull origin main --rebase
# Resolve conflict jika ada
git push origin main
```
