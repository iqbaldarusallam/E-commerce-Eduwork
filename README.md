# Panduan Fork → Clone → Branch → Commit → Push → Pull Request

---

## ⚡ 1. Fork Repository

Jika repository **bukan** milik Anda:

1. Buka repository sumber di GitHub.
2. Klik tombol **Fork** (kanan atas).
3. Pilih akun Anda.

Hasilnya: Anda memiliki salinan repo tersebut di akun GitHub Anda.

---

## ⚡ 2. Clone Repository Fork

Clone repository fork ke komputer lokal:

```bash
git clone https://github.com/iqbaldarusallam/E-commerce-Eduwork.git
cd E-commerce-Eduwork
```

---

## ⚡ 3. Tambahkan Remote `upstream` (Jika Repo hasil Fork)

Digunakan untuk mendapatkan update dari repository sumber.

```bash
git remote add upstream https://github.com/ORIGINAL_OWNER/E-commerce-Eduwork.git
git remote -v
```

> Jika repository utama adalah milik Anda sendiri, langkah ini tidak perlu dilakukan.

---

## ⚡ 4. Update Branch `main`

Selalu lakukan update sebelum mulai mengerjakan fitur:

```bash
git checkout main
git pull origin main
```

Jika repo menggunakan upstream:

```bash
git fetch upstream
git merge upstream/main
git push origin main
```

---

## ⚡ 5. Buat Branch Baru

Jangan pernah bekerja langsung di branch `main`.

```bash
git checkout -b feature/nama-fitur
```

Contoh:

```bash
git checkout -b feature/login-validation
```

---

## ⚡ 6. Lakukan Perubahan Kode

Edit atau tambahkan file sesuai kebutuhan.

---

## ⚡ 7. Cek Perubahan

```bash
git status
```

---

## ⚡ 8. Stage Perubahan

Tambahkan semua perubahan:

```bash
git add -A
```

Atau file tertentu:

```bash
git add path/ke/file
```

---

## ⚡ 9. Commit dengan Format yang Benar

Gunakan format commit singkat, jelas, dan terstruktur.

```bash
git commit -m "feat(auth): add login form validation"
```

### Format commit:

```
type(scope): message
```

### Tipe umum commit:

* **feat** → fitur baru
* **fix** → perbaikan bug
* **refactor** → perbaikan kode tanpa mengubah fungsi
* **style** → formatting, layout
* **docs** → dokumentasi
* **chore** → konfigurasi, non-code

---

## ⚡ 10. Push Branch ke Repo Fork

```bash
git push origin feature/login-validation
```

Branch akan muncul di GitHub.

---

## ⚡ 11. Buat Pull Request (PR)

1. Buka GitHub → tampil tombol **Compare & pull request**.
2. Pastikan:

   * **base repo** = repository sumber
   * **base branch** = `main` / `develop`
   * **compare** = branch fitur Anda
3. Isi deskripsi PR:

   * Perubahan apa
   * Alasan
   * Cara menguji perubahan

Klik **Create pull request**.

---

## ⚡ 12. Sinkronisasi Fork Jika Ada Update

Untuk update branch `main`:

```bash
git checkout main
git fetch upstream
git merge upstream/main
git push origin main
```

Update pada branch fitur:

```bash
git checkout feature/login-validation
git merge main
git push origin feature/login-validation
```

---

## ⚡ 13. Jika Terjadi Konflik

Jika Git menampilkan tanda konflik:

```
<<<<< HEAD
```

Selesaikan konflik, lalu:

```bash
git add -A
git commit
git push
```

PR akan diperbarui otomatis.

---

## ⚡ 14. Ringkasan Super Singkat

```
Fork → Clone → Add upstream → Update main →
Buat branch → Coding → add → commit → push → PR
```
