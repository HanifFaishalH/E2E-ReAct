// @ts-check
import { test } from '@playwright/test';

test.describe.configure({ timeout: 120000 });

test.describe('Fitur Umpan Balik - Mahasiswa', () => {
  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const feedbackUrl = `${base}/feedback`;

  test('Mahasiswa dapat memberikan umpan balik seperti alur pengguna asli', async ({ page }) => {
    test.setTimeout(120000);
    await page.setViewportSize({ width: 1920, height: 1080 });

    // 🔹 1. Login
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });
    await page.fill('#username', 'mahasiswa');
    await page.fill('#password', 'mahasiswa');
    await page.click('button[type="submit"]');
    await page.waitForTimeout(2000); // tunggu redirect ke dashboard

    // 🔹 2. Klik menu sidebar "Umpan Balik" (bukan Daftar Umpan Balik)
    const menuFeedback = page.locator('a.nav-link[href$="/feedback"]'); // 🎯 target langsung berdasarkan href
    await menuFeedback.waitFor({ state: 'visible', timeout: 15000 });
    await page.evaluate(el => el.click(), await menuFeedback.elementHandle());

    // 🔹 3. Tunggu halaman feedback terbuka
    await page.waitForURL(/feedback$/, { timeout: 30000 });
    await page.waitForTimeout(1500);

    // 🔹 4. Klik tombol "Pilih" pada laporan pertama yang tersedia
    const tombolPilih = page.locator('#tabel-laporan tbody tr:first-child .pilih-laporan');
    await tombolPilih.waitFor({ state: 'visible', timeout: 10000 });
    await page.evaluate(el => el.click(), await tombolPilih.elementHandle());

    // 🔹 5. Tunggu teks konfirmasi laporan terpilih muncul
    await page.waitForSelector('#laporanTerpilih', { timeout: 5000 });
    await page.waitForTimeout(1000);

    // 🔹 6. Pilih rating (acak 1–5)
    const rating = Math.floor(Math.random() * 5) + 1;
    await page.check(`#rating${rating}`);

    // 🔹 7. Isi komentar
    const komentar = `Feedback otomatis mahasiswa rating ${rating} - ${new Date().toLocaleTimeString()}`;
    await page.fill('textarea[name="komentar"]', komentar);

    // 🔹 8. Klik tombol Kirim
    const tombolKirim = page.locator('button.btn-primary', { hasText: 'Kirim' });
    await page.evaluate(el => el.click(), await tombolKirim.elementHandle());

    // 🔹 9. Tunggu form selesai dikirim (tanpa reload)
    await page.waitForTimeout(2000);

    // 🔹 10. Screenshot hasil akhir
    await page.screenshot({
      path: 'test-results/mahasiswa-feedback-finish.png',
      fullPage: true,
    });
  });
});
