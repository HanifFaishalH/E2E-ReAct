import { test, expect } from '@playwright/test';

test.describe.parallel('Auth - login (robust)', () => {

  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const logoutUrl = `${base}/logout`;

  const users = [
    { username: 'admin', password: 'admin' },
    { username: 'mahasiswa', password: 'mahasiswa' },
    { username: 'dosen', password: 'dosen' },
    { username: 'tendik', password: 'tendik' },
    { username: 'teknisi1', password: 'teknisi1' },
    { username: 'teknisi2', password: 'teknisi2' },
    { username: 'teknisi3', password: 'teknisi3' },
    { username: 'teknisi4', password: 'teknisi4' },
    { username: 'teknisi5', password: 'teknisi5' },
    { username: 'sarpras', password: 'sarpras' },
  ];

  // Disable preloader & animations
  test.beforeEach(async ({ page }) => {
    await page.setExtraHTTPHeaders({ 'X-Requested-With': 'XMLHttpRequest' });

    await page.addStyleTag({
      content: `
        #preloader, .loader {
          display:none!important;
          opacity:0!important;
          pointer-events:none!important;
        }
        *, *::before, *::after {
          animation: none!important;
          transition: none!important;
        }
      `,
    });

    page.setDefaultTimeout(120000);
  });


  // Helper login → hanya berdasarkan SweetAlert
  async function runLogin(page) {
    await page.click('button[type="submit"]');

    const swal = page.locator('.swal2-title');
    await expect(swal).toBeVisible({ timeout: 20000 });

    return await swal.innerText();
  }

  /* ===========================
        POSITIVE TEST
  ============================ */
  for (const user of users) {
    test(`Positive: login berhasil untuk ${user.username}`, async ({ page }) => {

      await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

      await page.fill('#username', user.username);
      await page.fill('#password', user.password);

      const title = await runLogin(page);

      expect(title).toMatch(/Login Berhasil/i);

      // Setelah swal, lanjut redirect
      await page.waitForTimeout(2000);

      // Logout
      await page.goto(logoutUrl).catch(() => {});
    });
  }

  /* ===========================
        NEGATIVE: PASSWORD SALAH
  ============================ */
  for (const user of users) {
    test(`Negative: password salah untuk ${user.username}`, async ({ page }) => {

      await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

      await page.fill('#username', user.username);
      await page.fill('#password', 'password_salah');

      const title = await runLogin(page);

      expect(title).toMatch(/Login Gagal/i);
    });
  }

  /* ===========================
        NEGATIVE: USERNAME SALAH
  ============================ */
  for (const user of users) {
    test(`Negative: username tidak ada (skenario untuk ${user.username})`, async ({ page }) => {

      await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

      await page.fill('#username', 'user_tidak_ada_12345');
      await page.fill('#password', user.password);

      const title = await runLogin(page);

      expect(title).toMatch(/Login Gagal/i);
    });
  }

  /* ===========================
       NEGATIVE: INPUT KOSONG
  ============================ */
  test('Negative: input kosong', async ({ page }) => {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

    await page.click('button[type="submit"]');

    await expect(page.locator('#username')).toHaveAttribute('required', '');
    await expect(page.locator('#password')).toHaveAttribute('required', '');
  });

  /* ===========================
       NEGATIVE: SERVER ERROR
  ============================ */
  test('Negative: server error (mock 500)', async ({ page }) => {

    await page.route('**/login', route => {
      if (route.request().method() === 'POST') {
        return route.fulfill({
          status: 500,
          contentType: 'application/json',
          body: JSON.stringify({ message: 'Server Error' })
        });
      }
      route.continue();
    });

    await page.goto(loginUrl);

    await page.fill('#username', 'admin');
    await page.fill('#password', 'admin');

    const title = await runLogin(page);

    expect(title).toMatch(/Kesalahan|Error|Server/i);
  });

});
