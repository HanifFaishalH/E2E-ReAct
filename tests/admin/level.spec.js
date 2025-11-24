import { test, expect } from '@playwright/test';

test.describe('Admin - Akses halaman Level', () => {

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const levelUrl = `${base}/level`;

  // Hapus animasi
  test.beforeEach(async ({ page }) => {
    await page.addStyleTag({ content: `
      *, *::before, *::after {
        animation: none !important;
        transition: none !important;
      }
      #preloader, .loader { display:none!important; }
    ` });
    page.setDefaultTimeout(60000);
  });

  async function loginAdmin(page) {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');

    // Tekan login
    await page.click('button[type="submit"]');

    // Validasi SweetAlert login sukses
    const swal = page.locator('.swal2-title');
    await expect(swal).toBeVisible();
    await expect(swal).toHaveText(/Login Berhasil/i);

    await page.waitForTimeout(2000);
  }

  /* ============================================================================
      TEST 1: Admin bisa membuka halaman /level
  ============================================================================ */
  test('Admin dapat mengakses halaman Level', async ({ page }) => {

    await loginAdmin(page);

    await page.goto(levelUrl, { waitUntil: 'domcontentloaded' });
    await expect(page.locator('h4.header-title')).toHaveText(/level/i);
    await expect(page.locator('#table_level')).toBeVisible();
  });

  /* ============================================================================
      TEST 2: DataTables berhasil load data level
  ============================================================================ */
  test('DataTables berhasil load data level dari server', async ({ page }) => {

    await loginAdmin(page);

    const [response] = await Promise.all([
      page.waitForResponse(resp => resp.url().includes('/level/list') && resp.status() === 200),
      page.goto(levelUrl),
    ]);

    const json = await response.json();
    expect(json).toHaveProperty('data');
  });

  /* ============================================================================
      TEST 3: Filter level bekerja (level_id)
  ============================================================================ */
  test('Filter level_id bekerja dengan baik', async ({ page }) => {

    await loginAdmin(page);
    await page.goto(levelUrl);

    const options = page.locator('#level_id option');

    const count = await options.count();
    expect(count).toBeGreaterThan(1);

    const value = await options.nth(1).getAttribute('value');

    await page.selectOption('#level_id', value);

    const [response] = await Promise.all([
      page.waitForResponse(resp => resp.url().includes('level/list') && resp.status() === 200),
      page.waitForTimeout(1000)
    ]);

    const json = await response.json();
    expect(json).toHaveProperty('data');
  });

  /* ============================================================================
      TEST 4: Modal bisa dibuka (uji fungsi modalAction)
  ============================================================================ */
  test('Modal dapat dimuat dan ditampilkan', async ({ page }) => {

    await loginAdmin(page);
    await page.goto(levelUrl);

    const modalUrl = `${base}/level/add`;

    await page.evaluate((url) => {
      window.modalAction(url);
    }, modalUrl);

    await expect(page.locator('#myModal')).toBeVisible({ timeout: 5000 });
  });

});
