import { test, expect } from '@playwright/test';

// PENTING: Gunakan mode 'serial' agar tes berjalan berurutan.
// Jika tidak, tes Edit mungkin jalan sebelum Create selesai.
test.describe.configure({ mode: 'serial' });

test.describe('Admin Gedung - Siklus Hidup Data (Create -> Edit -> Delete)', () => {
  let page;
  let context;

  // Variabel untuk menyimpan data dinamis yang kita buat
  let createdGedungNama = "";
  let createdGedungKode = "";

  const base = 'https://reportaction.dbsnetwork.my.id';

  // ==========================================================
  // SETUP & LOGIN (Dijalankan sekali di awal)
  // ==========================================================
  test.beforeAll(async ({ browser }) => {
    context = await browser.newContext();
    page = await context.newPage();

    // Matikan animasi CSS agar tes lebih stabil
    await page.addStyleTag({
      content: `
        #preloader, .loader { display:none!important; }
        *, *::before, *::after { animation:none!important; transition:none!important; }
      `,
    });

    // Login
    await page.goto(`${base}/login`);
    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');
    await page.click('button[type="submit"]');
    await page.waitForURL(/\/$/, { timeout: 15000 });
  });

  test.afterAll(async () => {
    await context.close();
  });

  // ==========================================================
  // TEST 1: CREATE DATA BARU
  // ==========================================================
  test('1. Create Data Baru', async () => {
    await page.goto(`${base}/gedung`);
    await page.waitForLoadState('networkidle');

    // Generate data unik menggunakan Timestamp
    const timeStamp = Date.now();
    createdGedungNama = `Gedung E2E ${timeStamp}`;
    createdGedungKode = `G-${timeStamp}`;

    console.log(`[CREATE] Membuat data: ${createdGedungNama}`);

    // Buka Modal
    await page.getByRole('button', { name: /Tambah Gedung/i }).click();
    const modal = page.locator('#myModal');
    await expect(modal).toBeVisible();

    // Isi Form
    await modal.locator('input[name="gedung_nama"]').fill(createdGedungNama);
    await modal.locator('input[name="gedung_kode"]').fill(createdGedungKode);

    // Submit
    await modal.locator('button[type="submit"]').click();

    // Verifikasi Alert Sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toContainText(/Berhasil|Sukses/i);
    
    // Tunggu alert hilang atau klik OK
    if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
        await page.getByRole('button', { name: 'OK' }).click();
    }
  });

  // ==========================================================
  // TEST 2: EDIT DATA (YANG BARU SAJA DIBUAT)
  // ==========================================================
  test('2. Edit Data yang Baru Dibuat', async () => {
    // Pastikan kita masih di halaman gedung
    if (!page.url().includes('/gedung')) {
        await page.goto(`${base}/gedung`);
    }

    console.log(`[EDIT] Mencari data: ${createdGedungNama}`);

    // 1. Cari data spesifik menggunakan Kotak Pencarian DataTable
    // Ini memastikan kita mendapatkan baris yang benar meskipun data ada di page 2, dst.
    const searchBox = page.locator('input[type="search"]'); // Selector standar DataTables
    await searchBox.fill(createdGedungNama);
    
    // Tunggu tabel refresh (biasanya ada loading indicator, atau kita tunggu sebentar)
    await page.waitForTimeout(1000); 

    // 2. Targetkan baris yang mengandung teks nama gedung kita
    const specificRow = page.locator('#table_gedung tbody tr').filter({ hasText: createdGedungNama });
    
    // Pastikan baris ditemukan
    await expect(specificRow).toBeVisible();

    // 3. Klik tombol Edit di baris tersebut
    // Biasanya tombol edit berwarna kuning (btn-warning) atau icon pensil
    await specificRow.locator('.btn-warning, .fa-edit, a[title="Edit"]').first().click();

    // 4. Tunggu Modal Edit muncul
    const modalEdit = page.locator('#editGedungForm');
    await expect(modalEdit).toBeVisible();

    // 5. Ubah Nama Gedung
    const newName = `${createdGedungNama} (Edited)`;
    await modalEdit.locator('input[name="gedung_nama"]').fill(newName);

    // 6. Simpan
    await page.locator('button[form="editGedungForm"]').click();

    // 7. Verifikasi Sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toContainText(/Berhasil|Sukses/i);

    // Update variabel global agar Test Delete tahu nama baru ini
    createdGedungNama = newName;
    console.log(`[EDIT] Data berhasil diubah menjadi: ${createdGedungNama}`);
  });

  // ==========================================================
  // TEST 3: DELETE DATA (YANG BARU SAJA DIEDIT)
  // ==========================================================
  test('3. Delete Data yang Baru Diedit', async () => {
    // Pastikan di halaman gedung
    if (!page.url().includes('/gedung')) {
        await page.goto(`${base}/gedung`);
    }

    console.log(`[DELETE] Mencari data untuk dihapus: ${createdGedungNama}`);

    // 1. Cari data yang SUDAH DIEDIT tadi
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdGedungNama);
    await page.waitForTimeout(1000);

    // 2. Targetkan baris spesifik
    const specificRow = page.locator('#table_gedung tbody tr').filter({ hasText: createdGedungNama });
    await expect(specificRow).toBeVisible();

    // 3. Klik tombol Delete di baris tersebut
    // Biasanya tombol delete berwarna merah (btn-danger) atau icon sampah
    await specificRow.locator('.btn-danger, .fa-trash').first().click();

    // 4. Tunggu Modal Konfirmasi Hapus
    const modalDelete = page.locator('#deleteGedungForm');
    await expect(modalDelete).toBeVisible();

    // Pastikan teks konfirmasi menyebutkan nama gedung yang benar (Optional tapi bagus)
    await expect(modalDelete).toContainText(createdGedungNama);

    // 5. Konfirmasi Hapus
    await modalDelete.locator('button[type="submit"]').click();

    // 6. Verifikasi Sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toContainText(/Berhasil|Sukses|Deleted/i);
    
    console.log(`[DELETE] Sukses menghapus data: ${createdGedungNama}`);
  });

  // ==========================================================
  // TEST 4: VERIFIKASI DATA HILANG
  // ==========================================================
  test('4. Verifikasi Data Sudah Hilang', async () => {
    // Cari lagi data tersebut
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdGedungNama);
    await page.waitForTimeout(1000);

    // Cek apakah tabel kosong atau menampilkan "No matching records"
    const tableBody = page.locator('#table_gedung tbody');
    
    // Cara 1: Cek teks "No matching records found" / "Data tidak ditemukan"
    // Cara 2: Cek jumlah row yang valid
    await expect(tableBody).toContainText(/No matching|Data tidak ditemukan|Kosong/i);
    
    console.log('[VERIFY] Data benar-benar sudah hilang dari tabel.');
  });

});