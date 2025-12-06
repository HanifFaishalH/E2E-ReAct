// @ts-check
import { test, expect } from '@playwright/test';

test.describe.configure({ timeout: 120000 });

test.describe('Fitur Laporan - Mahasiswa', () => {
  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;

  test('Mahasiswa dapat membuat laporan kerusakan', async ({ page }) => {
    test.setTimeout(120000);
    await page.setViewportSize({ width: 1920, height: 1080 });

    // 🔹 1. Login
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded', timeout: 90000 });
    await page.fill('#username', 'mahasiswa');
    await page.fill('#password', 'mahasiswa');
    await page.click('button[type="submit"]');
    await expect(page.locator('.swal2-title')).toHaveText(/Login Berhasil/i, { timeout: 20000 });
    await page.waitForURL(/\/$/, { timeout: 20000 });

    // 🔹 2. Pastikan menu sidebar muncul
    const menuLaporan = page.locator('a.nav-link', { hasText: 'Buat Laporan' });
    await expect(menuLaporan).toBeVisible({ timeout: 15000 });

    // 🔹 3. Klik via evaluate untuk hindari "outside viewport"
    const handle = await menuLaporan.elementHandle();
    await page.evaluate((el) => {
      el.scrollIntoView({ behavior: 'instant', block: 'center' });
      el.click(); // klik langsung lewat JS
    }, handle);

    // 🔹 4. Pastikan halaman laporan sudah terbuka
    await page.waitForURL(/laporan$/, { timeout: 30000 });

    // 🔹 5. Klik tombol "Buat Laporan"
    const tombolBuat = page.locator('button.btn-info', { hasText: 'Buat Laporan' });
    await tombolBuat.waitFor({ state: 'visible', timeout: 30000 });
    await page.evaluate((el) => el.click(), await tombolBuat.elementHandle());

    // 🔹 6. Tunggu modal form muncul
    const modal = page.locator('#myModal');
    await expect(modal).toBeVisible({ timeout: 30000 });

    // 🔹 7. Isi form laporan
    await page.selectOption('select[name="lantai_id"]', { index: 1 });
    await page.waitForTimeout(1000);
    await page.selectOption('select[name="ruang_id"]', { index: 1 });
    await page.waitForTimeout(1000);
    await page.selectOption('select[name="sarana_id"]', { index: 1 });
    await page.fill('input[name="laporan_judul"]', 'Testing laporan Mahasiswa');
    await page.selectOption('select[name="tingkat_kerusakan"]', 'tinggi');
    await page.selectOption('select[name="tingkat_urgensi"]', 'sedang');
    await page.selectOption('select[name="dampak_kerusakan"]', 'besar');

    // 🔹 8. Submit form
    await page.click('#form-create-laporan button[type="submit"]');
    await expect(page.locator('.swal2-title')).toHaveText(/Berhasil/i, { timeout: 20000 });
  });
});
