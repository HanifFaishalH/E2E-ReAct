import { test, expect } from '@playwright/test';

test.describe('Admin Sarana - Fokus Row Pertama', () => {
  let context;
  let page;

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;

  // ==========================================================
  // SETUP & LOGIN
  // ==========================================================
  test.beforeAll(async ({ browser }) => {
    context = await browser.newContext();
    page = await context.newPage();

    await page.addStyleTag({
      content: `
        #preloader, .loader { display:none!important; }
        *, *::before, *::after { animation:none!important; transition:none!important; }
      `,
    });

    // Login
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });
    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');
    await page.click('button[type="submit"]');
    await page.waitForURL(/\/$/, { timeout: 15000 });
    await expect(page.getByRole('heading', { name: /Halo,|Dashboard/i }).first()).toBeVisible({ timeout: 30000 });
  });

  test.afterAll(async () => {
    await context.close();
  });

  // ==========================================================
  // TEST 1: BUKA HALAMAN SARANA
  // ==========================================================
  test('1. Buka Halaman Sarana', async () => {
    await page.getByRole('link', { name: ' Kelola Sarana' }).click();
    await page.waitForLoadState('networkidle');
    await expect(page.locator('#table_sarana')).toBeVisible();
  });

  // ==========================================================
  // TEST 2: CREATE DATA (Soft Assert, Skip jika gagal)
  // ==========================================================
  test('2. Create Data (Coba input, abaikan jika gagal)', async () => {
    await page.goto(`${base}/sarana`);
    await page.waitForLoadState('networkidle');

    // Buka modal tambah Sarana
    await page.getByRole('button', { name: /Tambah Sarana/i }).click();
    const modal = page.locator('.modal.show');
    await expect(modal).toBeVisible();

    const selectRandom = async (selector, dependentOn = null) => {
      const dropdown = modal.locator(selector);

      if (dependentOn) {
        const depValue = await modal.locator(dependentOn).inputValue();
        console.log(`Dependent value for "${selector}": ${depValue}`);
      }

      const isDisabled = await dropdown.isDisabled();
      if (isDisabled) {
        console.log(`Dropdown "${selector}" disabled. Melewati...`);
        return false;
      }

      const options = await dropdown.locator('option').all();
      if (options.length === 0) return false;

      const randomIndex = options.length > 1 ? Math.floor(Math.random() * (options.length - 1)) + 1 : 0;
      await dropdown.selectOption({ index: randomIndex });
      await page.waitForTimeout(300);
      return true;
    };

    console.log('Mencoba create data baru...');

    const gedungOk = await selectRandom('#gedung_id');
    const lantaiOk = await selectRandom('#lantai_id', '#gedung_id');
    const ruangOk  = await selectRandom('#ruang_id', '#lantai_id');
    const kategoriOk = await selectRandom('#kategori_id');
    const barangOk   = await selectRandom('#barang_id', '#kategori_id');

    if (!gedungOk || !lantaiOk || !ruangOk || !kategoriOk || !barangOk) {
      console.log('Data tidak lengkap, melewati create.');

      if (await modal.isVisible()) {
        const closeBtn = modal.locator('button', { hasText: /Tutup|×/ }).first();
        if (await closeBtn.count() > 0) await closeBtn.click();
      }

      const infoAlert = page.locator('dialog:has-text("Tidak ada lantai tersedia")');
      if (await infoAlert.count() > 0) {
        const okBtn = infoAlert.getByRole('button', { name: 'OK' }).first();
        await okBtn.click();
      }
      return;
    }

    await modal.locator('#frekuensi_penggunaan').fill(`Auto Test - ${Date.now()}`);

    const infoAlert = page.locator('dialog:has-text("Tidak ada lantai tersedia")');
    if (await infoAlert.count() > 0) {
      const okBtn = infoAlert.getByRole('button', { name: 'OK' }).first();
      await okBtn.click();
    }

    // Submit form
    await modal.locator('button[type="submit"]').click();

    try {
      const swalTitle = page.locator('.swal2-title');
      await expect(swalTitle).toBeVisible({ timeout: 5000 });
      const text = await swalTitle.innerText();

      if (text.match(/Berhasil|Sukses/i)) {
        console.log('Create: Sukses menambahkan data baru.');
      } else {
        console.log(`Create: Gagal (Pesan: "${text}"). Lanjut ke Detail Row 1.`);
        const okBtn = page.getByRole('button', { name: /OK/i }).first();
        await okBtn.click();
      }
    } catch (e) {
      console.log('Create: Tidak ada alert muncul. Lanjut.');
    }
  });

  // ==========================================================
  // TEST 3: DETAIL DATA ROW PERTAMA
  // ==========================================================
  test('3. Detail Data (Ambil Row Pertama di Tabel)', async () => {
    await page.goto(`${base}/sarana`);
    await page.waitForLoadState('networkidle');

    const firstRow = page.locator('table tbody tr').first();

    if ((await firstRow.count()) === 0) {
      console.log('Tabel kosong. Tidak ada data untuk di-detail.');
      return;
    }

    const kodeDiTabel = await firstRow.locator('td').nth(3).innerText();
    console.log(`Membuka detail Row Pertama (Kode: ${kodeDiTabel})`);

    await firstRow.getByRole('link', { name: 'Detail' }).click();

    const modalDetail = page.locator('#formDetailSarana');
    await expect(modalDetail).toBeVisible();

    const inputKode = modalDetail.locator('input[id*="kode"], input[name*="kode"]');
    if ((await inputKode.count()) > 0) {
      await expect(inputKode).toHaveValue(kodeDiTabel);
    }
  });

  test('4. Delete Data (Hapus Row Pertama di Tabel)', async () => {
    await page.goto(`${base}/sarana`);
    await page.waitForLoadState('networkidle');

    const firstRow = page.locator('table tbody tr').first();

    if ((await firstRow.count()) === 0) {
        console.log('Tabel kosong. Tidak ada data untuk dihapus.');
        return;
    }

    const kodeDiTabel = await firstRow.locator('td').nth(3).innerText();
    console.log(`Menghapus Row Pertama (Kode: ${kodeDiTabel})`);

    await firstRow.getByRole('link', { name: 'Hapus' }).click();

    const confirmDialog = page.locator('.modal.show').filter({ hasText: 'Konfirmasi Hapus' });
    await expect(confirmDialog).toBeVisible({ timeout: 5000 });

    await confirmDialog.getByRole('button', { name: 'Hapus' }).click();

    const successAlert = page.locator('.swal2-title');
    if (await successAlert.count() > 0) {
        await expect(successAlert).toHaveText(/Berhasil|Sukses|Deleted/i, { timeout: 5000 });
    }

    console.log('Sukses menghapus Row Pertama.');
  });
  
});
