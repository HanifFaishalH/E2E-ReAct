import { test, expect } from '@playwright/test';

test.describe('Admin - Daftar Umpan Balik', () => {

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const feedbackListUrl = `${base}/feedback/list`;

  // Reusable admin login
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

  test.beforeEach(async ({ page }) => {
    // Disable animations/preloader
    await page.addStyleTag({
      content: `
        #preloader, .loader { display:none !important; }
        *, *::before, *::after {
          animation: none !important;
          transition: none !important;
        }
      `
    });
  });

  test('Halaman Daftar Feedback memuat tabel dan heading dengan benar', async ({ page }) => {

    // Login as admin
    await loginAdmin(page);

    // Open admin feedback list page
    await page.goto(feedbackListUrl, { waitUntil: 'domcontentloaded' });

    // Header text must match Blade template
    await expect(page.locator('h4.header-title')).toHaveText('Daftar Umpan Balik');

    // Table should be visible
    const table = page.locator('table.table');
    await expect(table).toBeVisible();

    // Case 1 → Data kosong
    const emptyText = page.locator('text=Tidak ada umpan balik yang tersedia.');
    if (await emptyText.isVisible()) {
      await expect(emptyText).toBeVisible();
      return; // Stop here if empty → test already valid
    }

    // Case 2 → Data ada → check kolom
    const firstRow = table.locator('tbody tr').first();
    await expect(firstRow).toBeVisible();

    await expect(firstRow.locator('td').nth(0)).not.toBeEmpty(); // Feedback ID
    await expect(firstRow.locator('td').nth(1)).not.toBeEmpty(); // User ID
    await expect(firstRow.locator('td').nth(2)).not.toBeEmpty(); // Laporan ID

  });

});
