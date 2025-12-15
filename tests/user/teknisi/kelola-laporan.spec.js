// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E Test untuk Kelola Laporan - User Teknisi
 * Fitur: Melihat, Filter, dan Mengelola Laporan
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
  
  // Tunggu cukup lama untuk SweetAlert dan redirect
  await page.waitForTimeout(5000);
}

test.describe('Teknisi - Kelola Laporan', () => {
  
  test.beforeEach(async ({ page }) => {
    // Login sebelum setiap test
    await loginAsTeknisi(page);
  });

  test('TC-KELOLA-01: Melihat halaman kelola laporan', async ({ page }) => {
    // Navigasi ke halaman Kelola Laporan
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    
    // Verifikasi tabel laporan muncul
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 10000 });
    
    // Verifikasi ada kolom-kolom yang sesuai
    await expect(page.locator('th:has-text("No")')).toBeVisible();
    await expect(page.locator('th:has-text("Judul")')).toBeVisible();
    await expect(page.locator('th:has-text("Status Laporan")')).toBeVisible();
    await expect(page.locator('th:has-text("Aksi")')).toBeVisible();
  });

  test('TC-KELOLA-02: Verifikasi filter status tersedia', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    
    // Verifikasi dropdown filter status ada
    const filterStatus = page.locator('select#status[name="status"]');
    await expect(filterStatus).toBeVisible({ timeout: 10000 });
    
    // Verifikasi opsi filter ada
    const optionCount = await filterStatus.locator('option').count();
    expect(optionCount).toBeGreaterThan(0);
  });

  test('TC-KELOLA-03: Filter laporan dengan status pending', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    
    // Filter dengan status "pending"
    await page.selectOption('select#status[name="status"]', 'pending');
    await page.waitForTimeout(4000);
    
    // Verifikasi tabel masih terlihat
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 10000 });
  });

  test('TC-KELOLA-04: Filter laporan dengan status proses', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    await page.waitForTimeout(1000);
    
    // Filter dengan status "proses"
    await page.selectOption('select#status[name="status"]', 'proses');
    await page.waitForTimeout(4000);
    
    // Verifikasi tabel masih terlihat
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 15000 });
  });

  test('TC-KELOLA-05: Filter laporan dengan status selesai', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    await page.waitForTimeout(1000);
    
    // Filter dengan status "selesai"
    await page.selectOption('select#status[name="status"]', 'selesai');
    await page.waitForTimeout(4000);
    
    // Verifikasi tabel masih terlihat
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 15000 });
  });

  test('TC-KELOLA-06: Reset filter ke semua status', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(4000);
    
    // Tunggu DataTable selesai load
    await page.waitForSelector('table#table_laporan', { timeout: 20000 });
    
    // Filter dengan status tertentu
    await page.selectOption('select#status[name="status"]', 'pending');
    await page.waitForTimeout(4000);
    
    // Reset filter ke semua status
    await page.selectOption('select#status[name="status"]', '');
    await page.waitForTimeout(4000);
    
    // Verifikasi tabel masih terlihat
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 10000 });
  });

  test('TC-KELOLA-07: Verifikasi DataTable search berfungsi', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);
    
    // Verifikasi search box DataTable ada
    const searchBox = page.locator('input[type="search"]');
    await expect(searchBox).toBeVisible({ timeout: 10000 });
    
    // Test search functionality
    await searchBox.fill('test');
    await page.waitForTimeout(1000);
    
    // Verifikasi tabel masih terlihat
    await expect(page.locator('table#table_laporan')).toBeVisible({ timeout: 10000 });
  });

  test('TC-KELOLA-08: Verifikasi DataTable pagination info', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);
    
    // Verifikasi pagination info ada
    await expect(page.locator('.dataTables_info')).toBeVisible({ timeout: 10000 });
  });

  test('TC-KELOLA-09: Verifikasi kolom tabel lengkap', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Verifikasi semua kolom ada
    await expect(page.locator('th:has-text("No")')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('th:has-text("Judul")')).toBeVisible();
    await expect(page.locator('th:has-text("Status Laporan")')).toBeVisible();
    await expect(page.locator('th:has-text("Aksi")')).toBeVisible();
  });

  test('TC-KELOLA-10: Verifikasi header title halaman', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Verifikasi ada header title
    await expect(page.locator('h4.header-title')).toBeVisible({ timeout: 10000 });
  });

});
