import { test, expect } from '@playwright/test';

test.describe('Admin - Kelola Periode', () => {

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const periodeUrl = `${base}/laporan/periode`;

  // Matikan animasi agar stabil
  test.beforeEach(async ({ page }) => {
    await page.addStyleTag({
      content: `
        *, *::before, *::after {
          animation: none !important;
          transition: none !important;
        }
        #preloader, .loader { display:none!important; }
      `
    });
  });

  // Helper login admin
  async function loginAdmin(page) {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');
    await page.click('button[type="submit"]');

    const swal = page.locator('.swal2-title');
    await expect(swal).toBeVisible();
    await expect(swal).toHaveText(/Login Berhasil/i);

    await page.waitForTimeout(1500);
  }

  /* ============================================================================
      TEST 1: Admin dapat membuka halaman
  ============================================================================ */
  test('Admin dapat membuka halaman Kelola Periode', async ({ page }) => {
    await loginAdmin(page);
    await page.goto(periodeUrl);

    await expect(page.locator('h2')).toHaveText(/Kelola Periode/i);
    await expect(page.locator('#filter-form')).toBeVisible();
    await expect(page.locator('#statistik-utama')).toBeVisible();
    await expect(page.locator('#tabel-detail')).toBeVisible();
  });

  /* ============================================================================
      TEST 2: Semua dropdown tampil & bisa dipilih
  ============================================================================ */
  test('Filter dropdown tampil dan bisa dipilih', async ({ page }) => {
    await loginAdmin(page);
    await page.goto(periodeUrl);

    const tahun = page.locator('#filter-tahun');
    const bulan = page.locator('#bulan');
    const barang = page.locator('#barang');

    await expect(tahun).toBeVisible();
    await expect(bulan).toBeVisible();
    await expect(barang).toBeVisible();

    const tahunOptions = await tahun.locator('option').count();
    if (tahunOptions > 1) {
      await tahun.selectOption({ index: 1 });
    }

    await bulan.selectOption('1');       // Januari
    await barang.selectOption({ index: 1 });
  });

  /* ============================================================================
      TEST 3: AJAX fetchData berhasil
  ============================================================================ */
  test('AJAX fetchData memuat statistik dan tabel', async ({ page }) => {
    await loginAdmin(page);

    const [response] = await Promise.all([
      page.waitForResponse(res => res.url().includes('periode/data') && res.status() === 200),
      page.goto(periodeUrl)
    ]);

    const json = await response.json();
    expect(json).toHaveProperty('statistik_html');
    expect(json).toHaveProperty('chart');
    expect(json).toHaveProperty('tabel');

    await expect(page.locator('#statistik-utama')).toBeVisible();

    const rows = page.locator('#tabel-detail tbody tr');
    await expect(rows.first()).toBeVisible();
    const rowCount = await rows.count();
    expect(rowCount).toBeGreaterThan(0);
  });

  /* ============================================================================
      TEST 4: Chart.js muncul & ter-render
  ============================================================================ */
  test('Chart total laporan ter-render', async ({ page }) => {
    await loginAdmin(page);
    await page.goto(periodeUrl);

    const canvas = page.locator('#chartTotalLaporan');
    await expect(canvas).toBeVisible({ timeout: 8000 });

    const box = await canvas.boundingBox();
    expect(box?.width).toBeGreaterThan(50);
    expect(box?.height).toBeGreaterThan(30);
  });

  /* ============================================================================
      TEST 5: Export PDF Berhasil (HAPPY PATH)
  ============================================================================ */
  test('Export PDF berhasil tanpa error', async ({ page }) => {
    await loginAdmin(page);
    await page.goto(periodeUrl);

    // Setup filter bulan agar data ada
    await page.selectOption('#bulan', '2'); 
    await Promise.all([
      page.waitForLoadState('networkidle'), 
      page.click('#btn-export-pdf')
    ]);

    await expect(page.locator('body')).not.toContainText('Class "Barryvdh\\DomPDF\\Facade\\Pdf" not found');
    
    await expect(page.locator('body')).not.toContainText('Ignition'); 
  });

  /* ============================================================================
      TEST 6: Error handling AJAX (Mocking)
  ============================================================================ */
  test('Jika server error (AJAX), muncul pesan error di tabel', async ({ page }) => {
    await loginAdmin(page);

    await page.route('**/periode/data*', route => {
      return route.fulfill({
        status: 500,
        body: JSON.stringify({ message: 'Server error' })
      });
    });

    await page.goto(periodeUrl);

    await expect(page.locator('.alert.alert-danger')).toBeVisible();
    await expect(page.locator('#tabel-detail tbody')).toContainText(/kesalahan server/i);
  });

});