import { test, expect } from '@playwright/test';

// PENTING: Mode 'serial' agar tes berjalan berurutan (Create -> Show -> Edit -> Delete)
// Menggunakan data yang sama di setiap tahap.
test.describe.configure({ mode: 'serial' });

test.describe('Admin Barang - Siklus Hidup Data Lengkap', () => {
  let page;
  let context;

  const base = 'https://reportaction.dbsnetwork.my.id';
  
  // Variabel global untuk menyimpan data dinamis
  let createdBarangNama = "";
  let createdSpesifikasi = "";

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

    await page.goto(`${base}/login`);
    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');
    await page.click('button[type="submit"]');
    
    // === PERBAIKAN DI SINI (Login Timeout) ===
    // Regex diubah agar menerima:
    // 1. Root URL (/)
    // 2. Dashboard
    // 3. Welcome
    await page.waitForURL(/(\/$|dashboard|welcome|home)/, { timeout: 30000 });
  });

  test.afterAll(async () => {
    await context.close();
  });

  // ==========================================================
  // 1. CREATE
  // ==========================================================
  test('1. Create Barang Baru', async () => {
    await page.goto(`${base}/barang`);
    await page.waitForLoadState('networkidle');

    // Buat data dummy unik menggunakan Timestamp
    const timeStamp = Date.now();
    createdBarangNama = `Barang Show ${timeStamp}`;
    createdSpesifikasi = `Spek ${timeStamp}`;

    await page.getByRole('button', { name: /Tambah Barang/i }).click();

    const formCreate = page.locator('#form-create-barang');
    await expect(formCreate).toBeVisible();

    await formCreate.locator('input[name="barang_nama"]').fill(createdBarangNama);
    await formCreate.locator('input[name="spesifikasi"]').fill(createdSpesifikasi);
    
    // Pilih Kategori (Index 1: Opsi pertama setelah placeholder)
    const selectKategori = formCreate.locator('select[name="kategori_id"]');
    await selectKategori.selectOption({ index: 1 });

    await formCreate.locator('button[type="submit"]').click();

    // Verifikasi Alert Sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toContainText(/Berhasil|Sukses/i);
    
    if (await page.getByRole('button', { name: 'OK' }).isVisible()) {
        await page.getByRole('button', { name: 'OK' }).click();
    }
  });

  // ==========================================================
  // 2. SHOW (DETAIL)
  // ==========================================================
  test('2. Lihat Detail (Show)', async () => {
    // Pastikan berada di halaman barang
    if (!page.url().includes('/barang')) {
        await page.goto(`${base}/barang`);
    }
    await page.waitForLoadState('networkidle');

    console.log(`[SHOW] Mencari detail: ${createdBarangNama}`);

    // Cari data di tabel
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdBarangNama);
    await page.waitForTimeout(1000);

    // Klik tombol Detail pada baris yang sesuai
    const specificRow = page.locator('#table_barang tbody tr').filter({ hasText: createdBarangNama });
    await expect(specificRow).toBeVisible();

    // Klik tombol detail (btn-info atau icon fa-eye)
    await specificRow.locator('.btn-info, .fa-eye, a[title="Detail"]').first().click();

    // Tunggu Modal Detail
    const formDetail = page.locator('#formDetailBarang');
    await expect(formDetail).toBeVisible();

    // === VERIFIKASI DATA ===
    // Menggunakan strategi scope pencarian ke .form-group
    
    // 1. Verifikasi Nama Barang
    const inputNama = formDetail.locator('.form-group', { hasText: 'Nama Barang' }).locator('input');
    await expect(inputNama).toBeVisible();
    await expect(inputNama).toHaveValue(createdBarangNama);

    // 2. Verifikasi Spesifikasi
    const inputSpek = formDetail.locator('.form-group', { hasText: 'Spesifikasi' }).locator('input');
    await expect(inputSpek).toBeVisible();
    await expect(inputSpek).toHaveValue(createdSpesifikasi);

    console.log('[SHOW] Data detail valid.');

    // Tutup Modal
    await page.locator('#myModal button[data-dismiss="modal"]').first().click();
    await expect(formDetail).toBeHidden();
  });

  // ==========================================================
  // 3. EDIT
  // ==========================================================
  test('3. Edit Barang', async () => {
    // Cari kembali data (untuk memastikan state aman)
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdBarangNama);
    await page.waitForTimeout(1000);

    const specificRow = page.locator('#table_barang tbody tr').filter({ hasText: createdBarangNama });
    
    // Klik tombol Edit (btn-warning)
    await specificRow.locator('.btn-warning, .fa-edit').first().click();

    const formUpdate = page.locator('#form-update-barang');
    await expect(formUpdate).toBeVisible();

    const newName = `${createdBarangNama} (Revisi)`;
    await formUpdate.locator('input[name="barang_nama"]').fill(newName);
    await formUpdate.locator('button[type="submit"]').click();

    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
    
    createdBarangNama = newName; // Update variabel global untuk delete
  });

  // ==========================================================
  // 4. DELETE
  // ==========================================================
  test('4. Delete Barang', async () => {
    const searchBox = page.locator('input[type="search"]');
    await searchBox.fill(createdBarangNama); // Cari nama baru (Revisi)
    await page.waitForTimeout(1000);

    const specificRow = page.locator('#table_barang tbody tr').filter({ hasText: createdBarangNama });
    
    // Klik tombol Delete (btn-danger)
    await specificRow.locator('.btn-danger, .fa-trash').first().click();

    const formDelete = page.locator('#form-delete-barang');
    await expect(formDelete).toBeVisible();
    await formDelete.locator('button[type="submit"]').click();

    await expect(page.locator('.swal2-title')).toContainText(/Berhasil|Sukses/i);
  });
});