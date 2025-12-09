import { test, expect } from '@playwright/test';

test.describe.parallel('Auth - Register (UI Realistic)', () => {
  const base = 'https://reportaction.dbsnetwork.my.id';
  const registerUrl = `${base}/register`;

  // Matikan animasi & preloader sebelum setiap test
  test.beforeEach(async ({ page }) => {
    await page.setExtraHTTPHeaders({ 'X-Requested-With': 'XMLHttpRequest' });
    await page.addStyleTag({
      content: `
        #preloader, .loader { display:none!important; opacity:0!important; pointer-events:none!important; }
        *, *::before, *::after { animation: none!important; transition: none!important; }
      `
    });
    page.setDefaultTimeout(120000);
  });

  // 🔹 Fungsi bantu: submit form dan deteksi hasil berdasarkan UI ATAU response HTTP
  async function runRegister(page) {
    // Klik submit + tangkap respons
    const [response] = await Promise.all([
      page.waitForResponse(res => res.url().includes('/register') && res.status() >= 200, { timeout: 10000 }),
      page.click('button[type="submit"]'),
    ]);

    await page.waitForTimeout(2000); // beri waktu modal / alert muncul

    // 1️⃣ Modal sukses muncul
    const modal = page.locator('#successModalLabel');
    if (await modal.isVisible()) return await modal.innerText();

    // 2️⃣ Alert gagal muncul
    const alert = page.locator('.alert-danger');
    if (await alert.isVisible()) {
      const msg = await alert.innerText();
      if (/Server|Error|Gagal/i.test(msg)) return 'Server Error';
      return msg.trim();
    }

    // 3️⃣ Deteksi dari body text
    const bodyText = await page.textContent('body');
    if (/Pendaftaran Berhasil/i.test(bodyText)) return 'Pendaftaran Berhasil';
    if (/Server|Error|Gagal/i.test(bodyText)) return 'Server Error';

    // 4️⃣ Deteksi dari status HTTP
    if (response.status() >= 500) return 'Server Error';
    if (response.status() === 200) return 'Pendaftaran Berhasil';

    return 'Tidak Diketahui';
  }

  /* ===========================
      POSITIVE TEST
  ============================ */
  test('Positive: register berhasil dengan data valid', async ({ page }) => {
    await page.goto(registerUrl, { waitUntil: 'networkidle' });

    const unique = Date.now(); // buat data unik
    await page.selectOption('#level_id', '2');
    await page.fill('#no_induk', `MHS${unique}`);
    await page.fill('#nama', 'Mahasiswa Test');
    await page.fill('#username', `user_${unique}`);
    await page.fill('#password', 'password123');
    await page.fill('#confirm_password', 'password123');

    const status = await runRegister(page);
    expect(status).toMatch(/Pendaftaran Berhasil/i);
  });

  /* ===========================
      NEGATIVE TEST - PASSWORD TIDAK COCOK
  ============================ */
  test('Negative: password dan konfirmasi tidak cocok', async ({ page }) => {
    await page.goto(registerUrl, { waitUntil: 'networkidle' });

    await page.selectOption('#level_id', '2');
    await page.fill('#no_induk', 'MHS001');
    await page.fill('#nama', 'User Salah');
    await page.fill('#username', `usersalah_${Date.now()}`);
    await page.fill('#password', 'password123');
    await page.fill('#confirm_password', 'password321');

    await page.click('button[type="submit"]');

    const alert = page.locator('.alert-danger');
    // 🧩 Tambah "must match" ke regex biar cocok dengan pesan default Laravel
    await expect(alert).toContainText(/konfirmasi|tidak cocok|must match/i, { timeout: 8000 });
  });

  /* ===========================
      NEGATIVE TEST - INPUT KOSONG
  ============================ */
  test('Negative: semua input kosong', async ({ page }) => {
    await page.goto(registerUrl, { waitUntil: 'domcontentloaded' });
    await page.click('button[type="submit"]');

    await expect(page.locator('#level_id')).toHaveAttribute('required', '');
    await expect(page.locator('#no_induk')).toHaveAttribute('required', '');
    await expect(page.locator('#nama')).toHaveAttribute('required', '');
    await expect(page.locator('#username')).toHaveAttribute('required', '');
    await expect(page.locator('#password')).toHaveAttribute('required', '');
    await expect(page.locator('#confirm_password')).toHaveAttribute('required', '');
  });

  /* ===========================
      NEGATIVE TEST - DUPLIKAT USERNAME
  ============================ */
  test('Negative: register gagal karena username sudah dipakai', async ({ page }) => {
    await page.goto(registerUrl, { waitUntil: 'networkidle' });

    await page.selectOption('#level_id', '2');
    await page.fill('#no_induk', 'MHS002');
    await page.fill('#nama', 'User Lama');
    await page.fill('#username', 'mahasiswa'); // username yang sudah terdaftar
    await page.fill('#password', 'password123');
    await page.fill('#confirm_password', 'password123');

    const status = await runRegister(page);
    expect(status).toMatch(/Gagal|Username|Terdaftar/i);
  });

  /* ===========================
      NEGATIVE TEST - SERVER ERROR (MOCK)
  ============================ */
  test('Negative: server error (mock 500)', async ({ page }) => {
    await page.route('**/register', route => {
      if (route.request().method() === 'POST') {
        return route.fulfill({
          status: 500,
          contentType: 'application/json',
          body: JSON.stringify({ message: 'Server Error' }),
        });
      }
      route.continue();
    });

    await page.goto(registerUrl);
    await page.selectOption('#level_id', '2');
    await page.fill('#no_induk', 'MHS500');
    await page.fill('#nama', 'Server Error User');
    await page.fill('#username', `servererror_${Date.now()}`);
    await page.fill('#password', 'password123');
    await page.fill('#confirm_password', 'password123');

    const status = await runRegister(page);
    expect(status).toMatch(/Server|Error|Gagal/i);
  });
});
