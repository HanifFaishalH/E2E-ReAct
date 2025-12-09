// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E Test untuk Login User Teknisi
 */

// Kredensial teknisi untuk testing
const TEKNISI_CREDENTIALS = {
  username: 'teknisi1',
  password: 'teknisi1'
};

test.describe('Teknisi - Login', () => {
  
  test.beforeEach(async ({ page }) => {
    // Navigasi ke halaman login
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
  });

  test('TC-LOGIN-01: Login dengan kredensial valid', async ({ page }) => {
    // Input username teknisi
    await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
    
    // Input password
    await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
    
    // Klik tombol submit
    await page.click('button[type="submit"]');
    
    // Tunggu SweetAlert muncul dan redirect
    await page.waitForTimeout(2000);
    
    // Verifikasi redirect ke dashboard
    await expect(page).toHaveURL(/\//);
    
    // Verifikasi ada menu Kelola Laporan (khusus teknisi)
    await expect(page.locator('a.nav-link:has-text("Kelola Laporan")')).toBeVisible();
  });

  test('TC-LOGIN-02: Login dengan username salah', async ({ page }) => {
    await page.fill('input[name="username"]#username', 'teknisi_salah');
    await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
    await page.click('button[type="submit"]');
    
    // Tunggu SweetAlert error muncul
    await page.waitForTimeout(1000);
    
    // Verifikasi SweetAlert dengan pesan error muncul
    const swalTitle = await page.locator('.swal2-title');
    await expect(swalTitle).toContainText(/Login Gagal|Kesalahan/i);
  });

  test('TC-LOGIN-03: Login dengan password salah', async ({ page }) => {
    await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
    await page.fill('input[name="password"]#password', 'password_salah');
    await page.click('button[type="submit"]');
    
    // Tunggu SweetAlert error muncul
    await page.waitForTimeout(1000);
    
    // Verifikasi SweetAlert dengan pesan error muncul
    const swalTitle = await page.locator('.swal2-title');
    await expect(swalTitle).toContainText(/Login Gagal|Kesalahan/i);
  });

  test('TC-LOGIN-04: Login dengan field kosong', async ({ page }) => {
    // Klik submit tanpa mengisi form
    await page.click('button[type="submit"]');
    
    // Verifikasi validasi HTML5 required
    const usernameInput = page.locator('input[name="username"]#username');
    await expect(usernameInput).toHaveAttribute('required');
  });

  test('TC-LOGIN-05: Verifikasi form login memiliki elemen yang benar', async ({ page }) => {
    // Verifikasi judul login
    await expect(page.locator('h4:has-text("Login")')).toBeVisible();
    
    // Verifikasi field username
    await expect(page.locator('input[name="username"]#username')).toBeVisible();
    
    // Verifikasi field password
    await expect(page.locator('input[name="password"]#password')).toBeVisible();
    
    // Verifikasi tombol submit
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    
    // Verifikasi link register
    await expect(page.locator('a[href*="register"]')).toBeVisible();
  });

  test('TC-LOGIN-06: Verifikasi password field tersembunyi', async ({ page }) => {
    const passwordInput = page.locator('input[name="password"]#password');
    
    // Verifikasi type adalah password
    const inputType = await passwordInput.getAttribute('type');
    expect(inputType).toBe('password');
  });

  test('TC-LOGIN-07: Logout setelah login', async ({ page }) => {
    // Login terlebih dahulu
    await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
    await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000);
    
    // Navigasi langsung ke logout URL
    await page.goto('/logout');
    
    // Verifikasi redirect ke halaman login
    await expect(page).toHaveURL(/.*login/);
  });

  test('TC-LOGIN-08: Akses halaman yang memerlukan autentikasi tanpa login', async ({ page }) => {
    // Coba akses halaman kelola laporan langsung tanpa login
    await page.goto('/laporan/kelola');
    
    // Verifikasi redirect ke login
    await expect(page).toHaveURL(/.*login/);
  });

  test('TC-LOGIN-09: Verifikasi menu yang terlihat setelah login sebagai teknisi', async ({ page }) => {
    // Login
    await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
    await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000);
    
    // Verifikasi menu yang seharusnya terlihat oleh teknisi
    await expect(page.locator('a.nav-link:has-text("Dashboard")')).toBeVisible();
    await expect(page.locator('a.nav-link:has-text("Kelola Laporan")')).toBeVisible();
    await expect(page.locator('a.nav-link:has-text("Daftar Umpan Balik")')).toBeVisible();
    
    // Verifikasi menu admin tidak terlihat
    await expect(page.locator('a.nav-link:has-text("Kelola Pengguna")')).not.toBeVisible();
    await expect(page.locator('a.nav-link:has-text("Sarana Prasarana")')).not.toBeVisible();
  });

  test('TC-LOGIN-10: Verifikasi dashboard teknisi setelah login', async ({ page }) => {
    // Login
    await page.fill('input[name="username"]#username', TEKNISI_CREDENTIALS.username);
    await page.fill('input[name="password"]#password', TEKNISI_CREDENTIALS.password);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000);
    
    // Verifikasi sudah di dashboard
    await expect(page).toHaveURL(/\//);
    
    // Verifikasi ada card statistik teknisi dengan selector yang lebih spesifik
    await expect(page.locator('.card.text-white.bg-primary:has-text("Total Tugas")').first()).toBeVisible();
    await expect(page.locator('.card.text-white.bg-info:has-text("Sedang Diproses")').first()).toBeVisible();
    await expect(page.locator('.card.text-white.bg-warning:has-text("Dikerjakan")').first()).toBeVisible();
    await expect(page.locator('.card.text-white.bg-success:has-text("Selesai")').first()).toBeVisible();
    
    // Verifikasi greeting message
    await expect(page.locator('h5:has-text("Halo")').first()).toBeVisible();
  });

});
