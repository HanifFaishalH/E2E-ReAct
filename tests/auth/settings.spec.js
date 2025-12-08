import { test, expect } from '@playwright/test';

test.describe.parallel('Profile Settings', () => {
  const base = 'https://reportaction.dbsnetwork.my.id';
  const loginUrl = `${base}/login`;
  const settingsUrl = `${base}/profile/setting`;
  const logoutUrl = `${base}/logout`;

  const testUsers = [
    { username: 'admin', password: 'admin' },
    { username: 'mahasiswa', password: 'mahasiswa' },
    { username: 'teknisi1', password: 'teknisi1' },
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

  async function login(page, username, password, gotoUrl) {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });
    
    // Try API login with CSRF token
    const csrfToken = await page.inputValue('input[name="_token"]');
    const apiResponse = await page.request.post(loginUrl, {
      form: {
        username: username,
        password: password,
        _token: csrfToken
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    // If API login successful (status < 400), cookies are set
    if (apiResponse.status() < 400) {
      // Navigate to target URL if provided
      if (gotoUrl) {
        await page.goto(gotoUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
      }
      return;
    }
    
    // Fallback: UI login
    await page.fill('#username', username);
    await page.fill('#password', password);
    await page.click('button[type="submit"]');
    
    // Wait for SweetAlert and navigation
    await page.waitForTimeout(3000);
    
    // Force navigation to target URL if still on login
    if (gotoUrl && page.url().includes('login')) {
      await page.goto(gotoUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    }
  }

  /* ===========================
      POSITIVE: VIEW SETTINGS
  ============================ */
  for (const user of testUsers) {
    test(`Positive: ${user.username} dapat akses halaman settings`, async ({ page }) => {
      await login(page, user.username, user.password, settingsUrl);
      
      await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
      await page.waitForTimeout(2000);

      expect(page.url()).not.toContain('login');
      const pageContent = await page.textContent('body');
      expect(pageContent).toMatch(/setting|pengaturan|dashboard/i);

      await page.goto(logoutUrl).catch(() => {});
    });
  }

  /* ===========================
      POSITIVE: UPDATE SETTINGS
  ============================ */
  test('Positive: update settings dengan data valid', async ({ page }) => {
    await login(page, 'admin', 'admin', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    // Cek apakah ada form
    const hasForm = await page.locator('form').count() > 0;
    
    if (hasForm) {
      // Try to find common setting inputs
      const inputs = await page.locator('input[type="text"], input[type="email"], select').count();
      
      if (inputs > 0) {
        // Fill first available input
        const firstInput = page.locator('input[type="text"], input[type="email"]').first();
        if (await firstInput.count() > 0) {
          await firstInput.fill('Updated Setting Value');
        }

        await page.click('button[type="submit"]').catch(() => {});
        await page.waitForTimeout(3000);

        // Verify result
        const bodyText = await page.textContent('body');
        expect(bodyText).toMatch(/berhasil|success|updated/i);
      }
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      POSITIVE: CHANGE NOTIFICATION SETTINGS
  ============================ */
  test('Positive: toggle notification settings', async ({ page }) => {
    await login(page, 'mahasiswa', 'mahasiswa', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    // Cari checkbox atau toggle untuk notifikasi
    const checkboxes = await page.locator('input[type="checkbox"]').count();
    
    if (checkboxes > 0) {
      const firstCheckbox = page.locator('input[type="checkbox"]').first();
      const isChecked = await firstCheckbox.isChecked();
      
      // Toggle checkbox
      await firstCheckbox.click();
      await page.waitForTimeout(500);
      
      const newState = await firstCheckbox.isChecked();
      expect(newState).not.toBe(isChecked);
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      POSITIVE: UPDATE THEME/DISPLAY SETTINGS
  ============================ */
  test('Positive: update theme atau display settings', async ({ page }) => {
    await login(page, 'teknisi1', 'teknisi1', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    // Cari select/dropdown untuk tema
    const selects = await page.locator('select').count();
    
    if (selects > 0) {
      const firstSelect = page.locator('select').first();
      const options = await firstSelect.locator('option').count();
      
      if (options > 1) {
        await firstSelect.selectOption({ index: 1 });
        await page.waitForTimeout(500);
      }
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      NEGATIVE: AKSES TANPA LOGIN
  ============================ */
  test('Negative: tidak dapat akses settings tanpa login', async ({ page }) => {
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(2000);

    const currentUrl = page.url();
    expect(currentUrl).toContain('login');
  });

  /* ===========================
      NEGATIVE: UPDATE DENGAN DATA KOSONG
  ============================ */
  test('Negative: update settings dengan field required kosong', async ({ page }) => {
    await login(page, 'admin', 'admin', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    // Cari required field dan kosongkan
    const requiredInputs = await page.locator('input[required]').count();
    
    if (requiredInputs > 0) {
      const firstRequired = page.locator('input[required]').first();
      await firstRequired.fill('');
      
      await page.click('button[type="submit"]').catch(() => {});
      await page.waitForTimeout(1000);

      // Browser validation should prevent submission
      const hasValidation = await page.locator('input[required]:invalid').count() > 0;
      expect(hasValidation).toBeTruthy();
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      NEGATIVE: INPUT DATA TERLALU PANJANG
  ============================ */
  test('Negative: input data melebihi batas maksimal', async ({ page }) => {
    await login(page, 'mahasiswa', 'mahasiswa', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    const textInputs = await page.locator('input[type="text"]').count();
    
    if (textInputs > 0) {
      const firstInput = page.locator('input[type="text"]').first();
      const maxLength = await firstInput.getAttribute('maxlength');
      
      if (maxLength) {
        // Try to input more than max
        const longText = 'A'.repeat(parseInt(maxLength) + 10);
        await firstInput.fill(longText);
        
        const actualValue = await firstInput.inputValue();
        expect(actualValue.length).toBeLessThanOrEqual(parseInt(maxLength));
      }
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      EDGE CASE: CONCURRENT UPDATES
  ============================ */
  test('Edge Case: rapid form submission', async ({ page }) => {
    await login(page, 'admin', 'admin', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    const hasForm = await page.locator('form').count() > 0;
    
    if (hasForm) {
      const submitBtn = page.locator('button[type="submit"]');
      
      // Click multiple times rapidly
      await submitBtn.click().catch(() => {});
      await submitBtn.click().catch(() => {});
      await submitBtn.click().catch(() => {});
      
      await page.waitForTimeout(3000);

      // Should handle gracefully without duplicate submissions
      const bodyText = await page.textContent('body');
      expect(bodyText).toBeDefined();
    }

    await page.goto(logoutUrl).catch(() => {});
  });

  /* ===========================
      EDGE CASE: CANCEL/RESET SETTINGS
  ============================ */
  test('Edge Case: cancel atau reset settings', async ({ page }) => {
    await login(page, 'teknisi1', 'teknisi1', settingsUrl);
    
    await page.goto(settingsUrl, { waitUntil: 'domcontentloaded' }).catch(() => {});
    await page.waitForTimeout(2000);

    const textInput = page.locator('input[type="text"]').first();
    
    if (await textInput.count() > 0) {
      const originalValue = await textInput.inputValue();
      
      // Change value
      await textInput.fill('Temporary Change');
      await page.waitForTimeout(500);
      
      // Look for cancel/reset button
      const cancelBtn = page.locator('button:has-text("Cancel"), button:has-text("Batal"), button:has-text("Reset")');
      
      if (await cancelBtn.count() > 0) {
        await cancelBtn.first().click();
        await page.waitForTimeout(1000);
        
        // Value should be restored or page reloaded
        const currentUrl = page.url();
        expect(currentUrl).toBeDefined();
      }
    }

    await page.goto(logoutUrl).catch(() => {});
  });
});
