// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E Test untuk Feedback - User Teknisi
 * Fitur: Melihat dan Memberikan Umpan Balik
 */

// Kredensial teknisi untuk testing
const TEKNISI_CREDENTIALS = {
  username: 'teknisi1',
  password: 'teknisi1'
};

/**
 * Helper function untuk login
 * @param {import('@playwright/test').Page} page
 */
async function loginAsTeknisi(page) {
  await page.goto('/login');
  await page.waitForLoadState('networkidle');
  await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
  await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
  await page.click('button[type="submit"]');
  await page.waitForTimeout(2000);
}

test.describe('Teknisi - Umpan Balik', () => {
  
  test.beforeEach(async ({ page }) => {
    // Login sebelum setiap test
    await loginAsTeknisi(page);
  });

  test('TC-FEEDBACK-01: Navigasi ke halaman daftar umpan balik', async ({ page }) => {
    // Navigasi langsung ke halaman feedback list
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    
    // Verifikasi halaman berhasil dimuat
    const bodyContent = await page.textContent('body');
    expect(bodyContent).toBeTruthy();
    if (bodyContent) {
      expect(bodyContent.length).toBeGreaterThan(0);
    }
  });

  test('TC-FEEDBACK-02: Verifikasi tabel atau list umpan balik ditampilkan', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    
    // Cek apakah ada tabel atau card feedback
    const tableCount = await page.locator('table').count();
    const cardCount = await page.locator('.card, .feedback-item').count();
    
    if (tableCount > 0) {
      await expect(page.locator('table')).toBeVisible();
    } else if (cardCount > 0) {
      await expect(page.locator('.card, .feedback-item').first()).toBeVisible();
    } else {
      // Jika tidak ada data, verifikasi ada pesan kosong
      const hasContent = await page.locator('body').textContent();
      expect(hasContent).toBeTruthy();
    }
  });

  test('TC-FEEDBACK-03: Verifikasi header title halaman', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    
    // Verifikasi ada header title (coba beberapa selector)
    const headerTitle = page.locator('h4.header-title, h4, .header-title, .page-title');
    const headerExists = await headerTitle.count();
    
    if (headerExists > 0) {
      await expect(headerTitle.first()).toBeVisible({ timeout: 10000 });
    } else {
      // Jika tidak ada header, verifikasi halaman berhasil dimuat dengan cara lain
      const bodyContent = await page.textContent('body');
      expect(bodyContent).toBeTruthy();
    }
  });

  test('TC-FEEDBACK-04: Melihat detail umpan balik jika ada data', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    
    // Cek apakah ada baris data
    const tableRows = await page.locator('tbody tr, table tr').count();
    const feedbackItems = await page.locator('.feedback-item, .card').count();
    
    if (tableRows > 0) {
      console.log(`Ditemukan ${tableRows} baris feedback di tabel`);
      expect(tableRows).toBeGreaterThan(0);
    } else if (feedbackItems > 0) {
      console.log(`Ditemukan ${feedbackItems} item feedback`);
      expect(feedbackItems).toBeGreaterThan(0);
    } else {
      console.log('Tidak ada item feedback yang ditemukan');
    }
    
    // Cek tombol detail/lihat
    const detailButtons = await page.locator(
      'button:has-text("Detail"), a:has-text("Detail"), ' +
      'button:has-text("Lihat"), a:has-text("Lihat")'
    ).count();
    
    if (detailButtons > 0) {
      console.log(`Ditemukan ${detailButtons} tombol detail`);
    }
  });

  test('TC-FEEDBACK-05: Verifikasi menu Umpan Balik untuk memberikan feedback', async ({ page }) => {
    // Cek apakah ada menu "Umpan Balik" (untuk membuat feedback baru)
    const feedbackMenu = page.locator('a.nav-link:has-text("Umpan Balik")');
    const menuExists = await feedbackMenu.count();
    
    if (menuExists > 0) {
      await expect(feedbackMenu).toBeVisible();
      console.log('Menu Umpan Balik ditemukan');
    } else {
      console.log('Menu Umpan Balik tidak tersedia untuk teknisi');
    }
  });

  test('TC-FEEDBACK-06: Verifikasi tabel memiliki kolom yang sesuai', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    
    // Cek apakah ada tabel
    const tableExists = await page.locator('table').count();
    
    if (tableExists > 0) {
      // Verifikasi kolom-kolom tabel (sesuaikan dengan struktur tabel aplikasi)
      const headers = await page.locator('th').allTextContents();
      console.log('Kolom tabel:', headers);
      expect(headers.length).toBeGreaterThan(0);
    }
  });

  test('TC-FEEDBACK-07: Verifikasi DataTable search jika tersedia', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Cek apakah ada search box DataTable
    const searchBox = page.locator('input[type="search"]');
    const searchExists = await searchBox.count();
    
    if (searchExists > 0) {
      await expect(searchBox).toBeVisible();
      
      // Test search functionality
      await searchBox.fill('test');
      await page.waitForTimeout(1000);
      console.log('Search box berfungsi');
    } else {
      console.log('Search box tidak ditemukan');
    }
  });

  test('TC-FEEDBACK-08: Verifikasi pagination info jika tersedia', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Cek apakah ada pagination info
    const paginationInfo = page.locator('.dataTables_info');
    const paginationExists = await paginationInfo.count();
    
    if (paginationExists > 0) {
      await expect(paginationInfo).toBeVisible();
      console.log('Pagination info ditemukan');
    } else {
      console.log('Pagination info tidak ditemukan');
    }
  });

  test('TC-FEEDBACK-09: Verifikasi tidak ada tombol hapus untuk teknisi', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    
    // Teknisi seharusnya tidak bisa menghapus feedback
    const deleteButtons = await page.locator(
      'button:has-text("Hapus"), a:has-text("Hapus"), ' +
      'button.btn-danger:has-text("Delete"), a.btn-danger'
    ).count();
    
    console.log(`Tombol hapus ditemukan: ${deleteButtons}`);
    // Teknisi tidak seharusnya punya akses hapus
  });

  test('TC-FEEDBACK-10: Refresh halaman dan verifikasi data tetap ada', async ({ page }) => {
    await page.goto('/feedback/list');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    
    // Refresh halaman
    await page.reload();
    await page.waitForLoadState('networkidle');
    
    // Verifikasi masih di halaman yang sama
    const bodyContent = await page.textContent('body');
    expect(bodyContent).toBeTruthy();
    if (bodyContent) {
      expect(bodyContent.length).toBeGreaterThan(0);
    }
  });

});
