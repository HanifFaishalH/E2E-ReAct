import { test, expect } from '@playwright/test';

// PENTING: Mode 'serial' agar tes berjalan berurutan
test.describe.configure({ mode: 'serial' });

test.describe('Admin Lantai - Siklus Hidup Data Lengkap', () => {
  let page;
  let context;

  const base = 'https://reportaction.dbsnetwork.my.id'; // Sesuaikan URL
  
  // Variabel global untuk menyimpan data dinamis antar test
  let createdLantaiNama = "";

  // ==========================================================
  // SETUP & LOGIN
  // ==========================================================
  test.beforeAll(async ({ browser }) => {
    context = await browser.newContext();
    page = await context.newPage();

    // Inject CSS untuk mematikan animasi (Bootstrap fade/shake) agar tes stabil
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
  // 1. CREATE LANTAI
  // ==========================================================
  test('1. Create Lantai Baru', async () => {
    await page.goto(`${base}/lantai`);
    await page.waitForLoadState('networkidle');

    // Buat nama unik menggunakan timestamp
    const timeStamp = Date.now();
    createdLantaiNama = `Lantai E2E ${timeStamp}`;

    // 1. Klik Tombol Tambah
    await page.getByRole('button', { name: /Tambah Lantai/i }).click();

    // 2. Tunggu Modal Muncul
    const modal = page.locator('#myModal');
    await expect(modal).toBeVisible();

    // Catatan: Sesuai view blade, ID form create adalah 'form-create-ruang'
    const formCreate = page.locator('#form-create-ruang'); 
    await expect(formCreate).toBeVisible();

    // 3. Isi Form
    // Pilih Gedung (Ambil opsi ke-2 / index 1)
    const selectGedung = formCreate.locator('select[name="gedung_id"]');
    await selectGedung.selectOption({ index: 1 });

    // Isi Nama Lantai
    await formCreate.locator('input[name="lantai_nama"]').fill(createdLantaiNama);

    // 4. Submit
    await formCreate.locator('button[type="submit"]').click();

    // 5. Verifikasi Alert Sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toContainText(/Berhasil|Sukses/i);
    
    // Klik OK pada alert jika ada
    if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
        await page.getByRole('button', { name: 'OK' }).click();
    }
  });

  // ==========================================================
  // 2. SHOW (DETAIL) - FIXED
  // ==========================================================
  test('2. Lihat Detail Lantai', async () => {
    // Pastikan di halaman lantai
    if (!page.url().includes('/lantai')) {
        await page.goto(`${base}/lantai`);
    }

    console.log(`[SHOW] Mencari: ${createdLantaiNama}`);

    // 1. Cari data di tabel
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdLantaiNama);
    await page.waitForTimeout(1000); // Tunggu filter

    // 2. Targetkan baris spesifik
    const specificRow = page.locator('#lantai-table tbody tr').filter({ hasText: createdLantaiNama });
    await expect(specificRow).toBeVisible();

    // 3. Klik tombol Detail
    const btnDetail = specificRow.locator('.btn-info, .fa-eye').first(); 
    await btnDetail.click();

    // 4. Tunggu Modal Detail Muncul
    const modalTitle = page.locator('.modal-title', { hasText: `Detail Lantai: ${createdLantaiNama}` });
    await expect(modalTitle).toBeVisible();

    // 5. Verifikasi Konten
    // Gunakan scope #myModal agar spesifik
    const modalBody = page.locator('#myModal .modal-body');
    
    // Cek ada teks "Nama Gedung"
    await expect(modalBody).toContainText('Nama Gedung:');
    
    // === PERBAIKAN UTAMA ===
    // Menggunakan .or() PADA LOCATOR, bukan pada expect assertion
    // Logic: Pastikan elemen Tabel Kelihatan ATAU Teks "Tidak ada ruang" Kelihatan
    const tableLocator = modalBody.locator('table');
    const emptyMsgLocator = modalBody.locator('text=Tidak ada ruang');
    
    await expect(tableLocator.or(emptyMsgLocator)).toBeVisible();

    console.log('[SHOW] Detail valid.');

    // 6. Tutup Modal
    await page.locator('#myModal button[data-dismiss="modal"]').first().click();
    await expect(modalTitle).toBeHidden();
  });

  // ==========================================================
  // 3. EDIT LANTAI
  // ==========================================================
  test('3. Edit Lantai', async () => {
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdLantaiNama);
    await page.waitForTimeout(1000);

    const specificRow = page.locator('#lantai-table tbody tr').filter({ hasText: createdLantaiNama });
    
    // Klik tombol Edit
    await specificRow.locator('.btn-warning, .fa-edit').first().click();

    // Tunggu Form Edit
    const formUpdate = page.locator('#form-update-lantai');
    await expect(formUpdate).toBeVisible();

    // Ubah Nama
    const newName = `${createdLantaiNama} (Upd)`;
    await formUpdate.locator('input[name="lantai_nama"]').fill(newName);

    // Submit
    await formUpdate.locator('button[type="submit"]').click();

    // Verifikasi Sukses
    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
    
    // Update variabel global untuk delete test
    createdLantaiNama = newName;
  });

  // ==========================================================
  // 4. DELETE LANTAI
  // ==========================================================
  test('4. Delete Lantai', async () => {
    // Cari nama baru
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdLantaiNama); 
    await page.waitForTimeout(1000);

    const specificRow = page.locator('#lantai-table tbody tr').filter({ hasText: createdLantaiNama });
    
    // Klik tombol Delete
    await specificRow.locator('.btn-danger, .fa-trash').first().click();

    // Tunggu Modal Konfirmasi Hapus
    const formDelete = page.locator('#form-delete-lantai');
    await expect(formDelete).toBeVisible();
    
    // Pastikan teks konfirmasi mengandung nama lantai
    await expect(formDelete).toContainText(createdLantaiNama);

    // Klik Hapus
    await formDelete.locator('button[type="submit"]').click();

    // Verifikasi Sukses
    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
  });

  // ==========================================================
  // 5. VERIFIKASI DATA HILANG
  // ==========================================================
  test('5. Verifikasi Data Hilang', async () => {
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdLantaiNama);
    await page.waitForTimeout(1000);

    const tableBody = page.locator('#lantai-table tbody');
    await expect(tableBody).toContainText(/No matching|Data tidak ditemukan|Kosong/i);
  });

});