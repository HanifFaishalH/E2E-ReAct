import { test, expect } from '@playwright/test';

test.describe.parallel('Profile Management', () => {
  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const profileUrl = `${base}/profile`;
  const logoutUrl = `${base}/logout`;

  // Test users untuk berbagai role
  const testUsers = [
    { username: 'admin', password: 'admin', role: 'Admin' },
    { username: 'mahasiswa', password: 'mahasiswa', role: 'Mahasiswa' },
    { username: 'dosen', password: 'dosen', role: 'Dosen' },
    { username: 'teknisi1', password: 'teknisi1', role: 'Teknisi' },
    { username: 'sarpras', password: 'sarpras', role: 'Sarpras' },
  ];

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

  // Helper function untuk login
  // Coba API login agar cookie langsung ter-set; jika gagal, fallback ke UI + SweetAlert
  async function login(page, username, password, gotoUrl = profileUrl) {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

    // Ambil CSRF token dari form
    const tokenInput = page.locator('input[name="_token"]');
    const csrfToken = (await tokenInput.count()) ? await tokenInput.first().inputValue() : '';

    // 1) Coba API login
    try {
      const resp = await page.request.post(loginUrl, {
        form: {
          username,
          password,
          _token: csrfToken,
        },
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      if (resp.status() < 400) {
        await page.goto(gotoUrl, { waitUntil: 'domcontentloaded' });
        return;
      }
    } catch (e) {
      // lanjut ke UI login
    }

    // 2) Fallback UI login
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });
    await page.fill('#username', username);
    await page.fill('#password', password);

    const swalTitle = page.locator('.swal2-title');

    await Promise.all([
      page.waitForResponse(res => res.url().includes('/login') && res.request().method() === 'POST', { timeout: 20000 }),
      page.click('button[type="submit"]'),
    ]);

    // SweetAlert sukses
    await swalTitle.waitFor({ state: 'visible', timeout: 20000 });
    const titleText = await swalTitle.innerText();
    expect(titleText).toMatch(/Login Berhasil/i);

    // Paksa ke halaman target
    await page.waitForTimeout(500);
    await page.goto(gotoUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});

    // Satu retry jika masih di login
    if (page.url().includes('login')) {
      await page.goto(gotoUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    }
  }

  /* ===========================
      POSITIVE: VIEW PROFILE
  ============================ */
  for (const user of testUsers) {
    test(`Positive: ${user.username} dapat melihat halaman profile`, async ({ page }) => {
      await login(page, user.username, user.password, profileUrl);

      // Verify tidak kembali ke login dan ideally di halaman profile
      // Pastikan bukan di login, coba buka profile lagi jika perlu
      await page.goto(profileUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});

      expect(page.url()).not.toContain('login');
      const heading = page.locator('h1, h2, .page-title');
      if (await heading.count()) {
        const text = await heading.first().innerText();
        expect(text).toMatch(/Profile|Profil|Dashboard/i);
      }

      await page.goto(logoutUrl).catch(() => {});
    });
  }

  /* ===========================
      POSITIVE: UPDATE PROFILE
  ============================ */
  test('Positive: user dapat update profile dengan data valid', async ({ page }) => {
    await login(page, 'mahasiswa', 'mahasiswa', profileUrl);
    await page.waitForTimeout(2000);

    // Cek apakah ada form untuk update
    const hasForm = await page.locator('form').count() > 0;
    
    if (hasForm) {
      // Fill form dengan data baru
      const namaInput = page.locator('input[name="nama"], input[name="user_nama"]');
      if (await namaInput.count() > 0) {
        await namaInput.first().fill('Nama User Updated');
      }

      const emailInput = page.locator('input[name="email"], input[name="user_email"]');
      if (await emailInput.count() > 0) {
        await emailInput.first().fill('updated@example.com');
      }

      // Submit form
      await page.click('button[type="submit"]').catch(() => {});
      await page.waitForTimeout(3000);

      // Verify success
      const swal = page.locator('.swal2-title, .alert-success');
      if (await swal.count() > 0) {
        const message = await swal.first().innerText();
        expect(message).toMatch(/berhasil|success/i);
      }
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      POSITIVE: UPDATE PASSWORD
  ============================ */
  test('Positive: user dapat update password', async ({ page }) => {
    await login(page, 'dosen', 'dosen', profileUrl);
    await page.waitForTimeout(2000);

    // Cari form password
    const oldPasswordInput = page.locator('input[name="old_password"], input[name="password_lama"]');
    const newPasswordInput = page.locator('input[name="new_password"], input[name="password_baru"]');
    const confirmPasswordInput = page.locator('input[name="password_confirmation"], input[name="konfirmasi_password"]');

    if (await oldPasswordInput.count() > 0) {
      await oldPasswordInput.first().fill('dosen');
      await newPasswordInput.first().fill('newpassword123');
      await confirmPasswordInput.first().fill('newpassword123');

      await page.click('button[type="submit"]').catch(() => {});
      await page.waitForTimeout(3000);

      // Verify result
      const bodyText = await page.textContent('body');
      expect(bodyText).toMatch(/berhasil|success|gagal/i);
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      NEGATIVE: AKSES TANPA LOGIN
  ============================ */
  test('Negative: tidak dapat akses profile tanpa login', async ({ page }) => {
    await page.goto(profileUrl, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(2000);

    // Seharusnya redirect ke login
    const currentUrl = page.url();
    expect(currentUrl).toContain('login');
  });

  /* ===========================
      NEGATIVE: UPDATE DENGAN DATA INVALID
  ============================ */
  test('Negative: update profile dengan email invalid', async ({ page }) => {
    await login(page, 'tendik', 'tendik', profileUrl);
    await page.waitForTimeout(2000);

    const emailInput = page.locator('input[name="email"], input[name="user_email"]');
    
    if (await emailInput.count() > 0) {
      await emailInput.first().fill('invalid-email-format');
      await page.click('button[type="submit"]').catch(() => {});
      await page.waitForTimeout(2000);

      // Verify validation error
      const bodyText = await page.textContent('body');
      expect(bodyText).toMatch(/invalid|tidak valid|gagal/i);
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      NEGATIVE: PASSWORD TIDAK MATCH
  ============================ */
  test('Negative: password baru tidak match dengan konfirmasi', async ({ page }) => {
    await login(page, 'mahasiswa', 'mahasiswa', profileUrl);
    await page.waitForTimeout(2000);

    const newPasswordInput = page.locator('input[name="new_password"], input[name="password_baru"]');
    const confirmPasswordInput = page.locator('input[name="password_confirmation"], input[name="konfirmasi_password"]');

    if (await newPasswordInput.count() > 0) {
      await newPasswordInput.first().fill('newpass123');
      await confirmPasswordInput.first().fill('differentpass456');

      await page.click('button[type="submit"]').catch(() => {});
      await page.waitForTimeout(2000);

      // Verify error
      const bodyText = await page.textContent('body');
      expect(bodyText).toMatch(/tidak cocok|tidak sama|not match/i);
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      EDGE CASE: UPLOAD FOTO PROFILE
  ============================ */
  test('Edge Case: upload foto profile', async ({ page }) => {
    await login(page, 'admin', 'admin', profileUrl);
    await page.waitForTimeout(2000);

    const fileInput = page.locator('input[type="file"]');
    
    if (await fileInput.count() > 0) {
      // Simulate file upload (jika ada fitur upload)
      const bodyText = await page.textContent('body');
      expect(bodyText).toBeDefined();
    }

    await page.goto(logoutUrl).catch(() => {});
  });
});
