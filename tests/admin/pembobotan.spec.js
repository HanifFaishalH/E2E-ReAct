import { test, expect } from '@playwright/test';

test.describe('Admin - Pembobotan AHP & SAW', () => {

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const pembobotanUrl = `${base}/bobot`;

  // ===============================
  // BEFORE EACH
  // ===============================
  test.beforeEach(async ({ page }) => {
    // Disable animations
    await page.addStyleTag({
      content: `
        #preloader, .loader { display:none !important; opacity:0!important; }
        *, *::before, *::after {
          transition:none !important;
          animation:none !important;
        }
      `
    });

    page.setDefaultTimeout(120000);
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

  // ===============================
  // TEST: Cek halaman pembobotan
  // ===============================
  test('Admin dapat membuka halaman pembobotan & melihat semua tabel', async ({ page }) => {

    await loginAdmin(page);

    // Open pembobotan page — FIX: wait for networkidle
    await Promise.all([
      page.waitForLoadState('networkidle'),
      page.goto(pembobotanUrl),
    ]);

    // Pastikan tidak 404 — cek element bukan title
    const header = page.locator('h4.header-title');
    await expect(header).toBeVisible();
    await expect(header).toHaveText(/AHP & SAW Analysis Tables/i);

    // ===========================
    // CEK ACCORDION
    // ===========================
    const acc1 = page.locator('#collapseWeights');
    const acc2 = page.locator('#collapseAHPWeights');
    const acc3 = page.locator('#collapsePairwise');
    const acc4 = page.locator('#collapseResults');

    await expect(acc1).toBeVisible();

    await page.click('a[href="#collapseAHPWeights"]');
    await expect(acc2).toBeVisible();

    await page.click('a[href="#collapsePairwise"]');
    await expect(acc3).toBeVisible();

    await page.click('a[href="#collapseResults"]');
    await expect(acc4).toBeVisible();

    // ===========================
    // CEK TABEL 1 – Comparison Table of Weights
    // ===========================
    const table1 = page.locator('#collapseWeights table tbody');

    await expect(table1.locator('tr')).toHaveCount(6);

    await expect(table1.locator('tr:nth-child(1) td:nth-child(1)'))
      .toHaveText('Kerusakan');

    await expect(table1.locator('tr:nth-child(1) td:nth-child(3)'))
      .toHaveText('3');

    // ===========================
    // CEK TABEL 2 – AHP Weights
    // ===========================
    const table2 = page.locator('#collapseAHPWeights table tbody');

    await expect(table2.locator('tr')).toHaveCount(6);
    await expect(table2.locator('tr:nth-child(1) td:nth-child(2)'))
      .toContainText('0.273');

    // ===========================
    // CEK TABEL 3 – Pairwise Matrix
    // ===========================
    const pairwise = page.locator('#collapsePairwise table');

    await expect(pairwise.locator('thead tr th')).toHaveCount(7);
    await expect(pairwise.locator('tbody tr')).toHaveCount(6);

    await expect(pairwise.locator('tbody tr:nth-child(1) td:nth-child(4)'))
      .toHaveText('2.00');

    // ===========================
    // CEK TABEL 4 – Perhitungan Final
    // ===========================
    const results = page.locator('#collapseResults table');

    await expect(results.locator('tbody tr')).toHaveCount(8); // total & final

    await expect(results.locator('tbody tr.table-info td:nth-child(2)'))
        .toContainText('2.415');

      // CATATAN: Baris berikutnya (123) mungkin juga perlu disesuaikan menjadi nth-child(2) 
      // karena baris table-success juga menggunakan colspan="4" pada sel pertamanya.
    await expect(results.locator('tbody tr.table-success td:nth-child(2)'))
        .toContainText('237.23');

  });

});
