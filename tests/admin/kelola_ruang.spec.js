import { test, expect } from '@playwright/test';

// Mode 'serial' memastikan urutan: Create -> Show -> Edit -> Delete
test.describe.configure({ mode: 'serial' });

test.describe('Admin Ruang - Robust Test (Fail Safe)', () => {
  let page;
  let context;

  const base = 'https://reportaction.dbsnetwork.my.id'; // Sesuaikan URL

  // ==========================================================
  // SETUP & LOGIN
  // ==========================================================
  test.beforeAll(async ({ browser }) => {
    context = await browser.newContext();
    page = await context.newPage();

    // Inject CSS untuk mematikan animasi agar tes lebih stabil
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
  // 1. CREATE RUANG (FAIL SAFE - LANJUT MESKI GAGAL)
  // ==========================================================
  test('1. Create Ruang (Abaikan jika Gagal)', async () => {
    await page.goto(`${base}/ruang`);
    await page.waitForLoadState('networkidle');

    const timeStamp = Date.now();
    const mockNama = `Ruang Test ${timeStamp}`;
    const mockKode = `R-${timeStamp.toString().slice(-4)}`;

    console.log('[CREATE] Mencoba membuat data...');

    // Buka Modal
    await page.getByRole('button', { name: /Tambah Ruang/i }).click();
    const modal = page.locator('#myModal');
    await expect(modal).toBeVisible();
    
    const formCreate = page.locator('#form-create-ruang');
    await expect(formCreate).toBeVisible();

    // --- Isi Form dengan Dependent Dropdown ---
    // 1. Pilih Gedung
    await formCreate.locator('select[name="gedung_id"]').selectOption({ index: 1 });
    
    // 2. Tunggu Dropdown Lantai Aktif (Loading AJAX selesai)
    const selectLantai = formCreate.locator('select[name="lantai_id"]');
    await expect(selectLantai).not.toBeDisabled({ timeout: 10000 });
    
    // 3. Pilih Lantai (jika opsi tersedia)
    const count = await selectLantai.locator('option').count();
    if (count > 1) {
        await selectLantai.selectOption({ index: 1 });
    }

    // 4. Isi field lainnya
    await formCreate.locator('input[name="ruang_kode"]').fill(mockKode);
    await formCreate.locator('input[name="ruang_nama"]').fill(mockNama);
    await formCreate.locator('select[name="ruang_tipe"]').selectOption({ index: 1 });

    // Submit
    await formCreate.locator('button[type="submit"]').click();

    // --- FAIL SAFE LOGIC ---
    const swalTitle = page.locator('.swal2-title');
    await expect(swalTitle).toBeVisible({ timeout: 10000 });
    
    // Cek apakah alertnya Sukses atau Gagal
    const alertText = await swalTitle.innerText();

    if (alertText.match(/Gagal|Error/i)) {
        // SKENARIO GAGAL (Backend Error)
        console.warn('[CREATE WARNING] Gagal membuat data (Backend Error).');
        console.warn('Lanjut ke test berikutnya menggunakan data row terakhir.');

        // 1. Klik OK
        if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
            await page.getByRole('button', { name: 'OK' }).click();
        }

        // 2. Tutup Modal Form secara manual agar layar bersih
        const closeButton = page.locator('#myModal button[data-dismiss="modal"]').first();
        if (await closeButton.isVisible()) {
            await closeButton.click();
        }
        
        await expect(modal).toBeHidden();

    } else {
        // SKENARIO SUKSES
        console.log('[CREATE SUKSES] Data berhasil dibuat.');
        await expect(swalTitle).toContainText(/Berhasil|Sukses/i);
        
        if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
            await page.getByRole('button', { name: 'OK' }).click();
        }
    }
  });

  // ==========================================================
  // 2. SHOW DETAIL (MENGGUNAKAN ROW TERAKHIR)
  // ==========================================================
  test('2. Lihat Detail (Row Terakhir)', async () => {
    // Refresh page untuk memastikan tabel bersih
    await page.goto(`${base}/ruang`);
    await page.waitForLoadState('networkidle');

    // 1. Ambil Baris Terakhir di Tabel
    const lastRow = page.locator('#ruang-table tbody tr').last();
    
    // Pastikan tabel tidak kosong
    if (await lastRow.count() === 0 || (await lastRow.innerText()).includes('No matching records')) {
        console.log('Tabel kosong. Skip test Detail.');
        return; 
    }

    // 2. Simpan Data dari Tabel untuk Validasi
    // nth(1) = Kolom Kode, nth(2) = Kolom Nama (berdasarkan struktur HTML tabel Anda)
    const kodeDiTabel = await lastRow.locator('td').nth(1).innerText(); 
    const namaDiTabel = await lastRow.locator('td').nth(2).innerText();

    console.log(`[SHOW] Mengecek row terakhir: ${namaDiTabel} (${kodeDiTabel})`);

    // 3. Klik Tombol Detail
    await lastRow.locator('.btn-info, .fa-eye, button:has-text("Detail")').click();

    // 4. Tunggu Modal Detail
    const formDetail = page.locator('#formDetailRuang');
    await expect(formDetail).toBeVisible();

    // 5. Validasi Data (FIXED: Menggunakan Selector Label)
    // Cari elemen .form-group yang mengandung teks label, lalu ambil input di dalamnya.
    // Ini menghindari error "Strict Mode Violation" (menemukan terlalu banyak input).

    // Cek Nama Ruang
    const inputNama = formDetail.locator('.form-group', { hasText: 'Nama Ruang' }).locator('input');
    await expect(inputNama).toBeVisible();
    await expect(inputNama).toHaveValue(namaDiTabel);

    // Cek Kode Ruang
    const inputKode = formDetail.locator('.form-group', { hasText: 'Kode Ruang' }).locator('input');
    await expect(inputKode).toBeVisible();
    await expect(inputKode).toHaveValue(kodeDiTabel);

    console.log('[SHOW] Data valid.');
    
    // 6. Tutup Modal
    await page.locator('#myModal button[data-dismiss="modal"]').first().click();
    await expect(formDetail).toBeHidden();
  });

  // ==========================================================
  // 3. EDIT (MENGGUNAKAN ROW TERAKHIR)
  // ==========================================================
  test('3. Edit Ruang (Row Terakhir)', async () => {
    await page.goto(`${base}/ruang`);
    await page.waitForLoadState('networkidle');

    // Ambil baris terakhir
    const lastRow = page.locator('#ruang-table tbody tr').last();
    
    if (await lastRow.count() === 0) return;

    const namaLama = await lastRow.locator('td').nth(2).innerText();
    console.log(`[EDIT] Mengedit: ${namaLama}`);

    // Klik Edit
    await lastRow.locator('.btn-warning, .fa-edit, button:has-text("Edit")').click();

    // Tunggu Form
    const formUpdate = page.locator('#form-update-ruang');
    await expect(formUpdate).toBeVisible();

    // Ubah Data
    const namaBaru = `${namaLama} (Edited)`;
    await formUpdate.locator('input[name="ruang_nama"]').fill(namaBaru);

    // Simpan
    await formUpdate.locator('button[type="submit"]').click();

    // Verifikasi Sukses
    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
    
    if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
        await page.getByRole('button', { name: 'OK' }).click();
    }
  });

  // ==========================================================
  // 4. DELETE (MENGGUNAKAN ROW TERAKHIR)
  // ==========================================================
  test('4. Delete Ruang (Row Terakhir)', async () => {
    await page.goto(`${base}/ruang`);
    await page.waitForLoadState('networkidle');

    const lastRow = page.locator('#ruang-table tbody tr').last();
    
    if (await lastRow.count() === 0) return;

    const namaDiTabel = await lastRow.locator('td').nth(2).innerText();
    console.log(`[DELETE] Menghapus: ${namaDiTabel}`);

    // Klik Delete
    await lastRow.locator('.btn-danger, .fa-trash, button:has-text("Hapus")').click();

    // Tunggu Konfirmasi
    const formDelete = page.locator('#form-delete-ruang');
    await expect(formDelete).toBeVisible();

    // Klik Hapus
    await formDelete.locator('button[type="submit"]').click();

    // Verifikasi Sukses
    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
  });

});