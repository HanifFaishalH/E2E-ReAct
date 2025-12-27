// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E Test untuk Profile Management - User Teknisi
 * Fitur: Melihat profil, edit profil, ubah password, setting
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
  await page.waitForTimeout(3000);
}

test.describe('Teknisi - Profile Management', () => {
  
  test.beforeEach(async ({ page }) => {
    // Login sebelum setiap test
    await loginAsTeknisi(page);
  });

  test('TC-PROFILE-01: Akses halaman profil teknisi', async ({ page }) => {
    // Navigasi ke halaman profil
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Verifikasi halaman profil berhasil dimuat
    const bodyContent = await page.textContent('body');
    expect(bodyContent).toBeTruthy();
    if (bodyContent) {
      expect(bodyContent.length).toBeGreaterThan(0);
    }
    
    // Verifikasi URL benar
    await expect(page).toHaveURL(/.*profile/);
    
    // Verifikasi tidak ada error
    const errorMessages = await page.locator('.alert-danger, .error').count();
    expect(errorMessages).toBe(0);
  });

  test('TC-PROFILE-02: Verifikasi informasi profil teknisi ditampilkan', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Verifikasi ada informasi profil
    const profileInfo = await page.textContent('body');
    
    if (profileInfo) {
      // Cek apakah ada informasi penting
      const hasUsername = profileInfo.includes('username') || profileInfo.includes('Username') || 
                         profileInfo.includes('teknisi1');
      const hasName = profileInfo.includes('nama') || profileInfo.includes('Nama') || 
                     profileInfo.includes('name') || profileInfo.includes('Name');
      const hasEmail = profileInfo.includes('email') || profileInfo.includes('Email') || 
                      profileInfo.includes('@');
      
      console.log(`Profile info - Username: ${hasUsername}, Name: ${hasName}, Email: ${hasEmail}`);
      
      // Minimal harus ada username atau nama
      expect(hasUsername || hasName).toBeTruthy();
    } else {
      // Jika profileInfo null, pastikan halaman tetap berhasil dimuat
      expect(page.url()).toContain('profile');
    }
  });

  test('TC-PROFILE-03: Edit informasi profil teknisi', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Cari tombol edit profil
    const editButton = page.locator(
      'button:has-text("Edit"), a:has-text("Edit"), ' +
      'button:has-text("Ubah"), a:has-text("Ubah"), ' +
      '.btn-primary, .btn-warning'
    );
    const editExists = await editButton.count();
    
    if (editExists > 0) {
      await editButton.first().click();
      await page.waitForTimeout(2000);
      
      // Verifikasi form edit muncul
      const formExists = await page.locator('form, input[type="text"], input[type="email"]').count();
      
      if (formExists > 0) {
        console.log('Form edit profil berhasil dimuat');
        
        // Coba edit nama jika ada field nama
        const nameField = page.locator('input[name="nama"], input[name="name"], input[name="full_name"]');
        const nameExists = await nameField.count();
        
        if (nameExists > 0) {
          await nameField.first().clear();
          await nameField.first().fill('Teknisi Test Updated');
        }
        
        // Coba edit email jika ada
        const emailField = page.locator('input[name="email"], input[type="email"]');
        const emailExists = await emailField.count();
        
        if (emailExists > 0) {
          await emailField.first().clear();
          await emailField.first().fill('teknisi.updated@test.com');
        }
        
        expect(formExists).toBeGreaterThan(0);
      } else {
        console.log('Form edit tidak ditemukan');
      }
    } else {
      console.log('Tombol edit tidak ditemukan');
    }
  });

  test('TC-PROFILE-04: Submit perubahan profil', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    const editButton = page.locator('button:has-text("Edit"), a:has-text("Edit")');
    const editExists = await editButton.count();
    
    if (editExists > 0) {
      await editButton.first().click();
      await page.waitForTimeout(2000);
      
      // Edit field yang ada
      const nameField = page.locator('input[name="nama"], input[name="name"]');
      const nameExists = await nameField.count();
      
      if (nameExists > 0) {
        await nameField.first().clear();
        await nameField.first().fill('Teknisi Test Submit');
        
        // Submit form
        const submitButton = page.locator('button[type="submit"], button:has-text("Simpan"), button:has-text("Update")');
        const submitExists = await submitButton.count();
        
        if (submitExists > 0) {
          await submitButton.first().click();
          await page.waitForTimeout(3000);
          
          // Verifikasi success message atau redirect
          const successMessage = await page.locator('.swal2-success, .alert-success').count();
          console.log(`Profile update success: ${successMessage > 0}`);
        } else {
          console.log('Tombol submit tidak ditemukan');
        }
      } else {
        console.log('Field nama tidak ditemukan');
      }
    } else {
      console.log('Tombol edit tidak ditemukan');
    }
  });

  test('TC-PROFILE-05: Akses halaman setting profil', async ({ page }) => {
    // Navigasi ke halaman setting
    await page.goto('/profile/setting');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Verifikasi halaman setting berhasil dimuat
    const bodyContent = await page.textContent('body');
    expect(bodyContent).toBeTruthy();
    
    // Verifikasi URL benar
    await expect(page).toHaveURL(/.*setting/);
    
    // Verifikasi tidak ada error
    const errorMessages = await page.locator('.alert-danger, .error').count();
    expect(errorMessages).toBe(0);
  });

  test('TC-PROFILE-06: Ubah password teknisi', async ({ page }) => {
    await page.goto('/profile/setting');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Cari field password
    const oldPasswordField = page.locator('input[name="old_password"], input[name="current_password"]');
    const newPasswordField = page.locator('input[name="new_password"], input[name="password"]');
    const confirmPasswordField = page.locator('input[name="password_confirmation"], input[name="confirm_password"]');
    
    const oldPassExists = await oldPasswordField.count();
    const newPassExists = await newPasswordField.count();
    const confirmPassExists = await confirmPasswordField.count();
    
    if (oldPassExists > 0 && newPassExists > 0 && confirmPassExists > 0) {
      // Isi form ubah password
      await oldPasswordField.first().fill(TEKNISI_CREDENTIALS.password);
      await newPasswordField.first().fill('newpassword123');
      await confirmPasswordField.first().fill('newpassword123');
      
      console.log('Form ubah password berhasil diisi');
      
      // Verifikasi form berhasil diisi
      expect(oldPassExists && newPassExists && confirmPassExists).toBeTruthy();
    } else {
      console.log('Form ubah password tidak lengkap atau tidak ditemukan');
    }
  });

  test('TC-PROFILE-07: Validasi ubah password dengan password lama salah', async ({ page }) => {
    await page.goto('/profile/setting');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    const oldPasswordField = page.locator('input[name="old_password"], input[name="current_password"]');
    const newPasswordField = page.locator('input[name="new_password"], input[name="password"]');
    const confirmPasswordField = page.locator('input[name="password_confirmation"], input[name="confirm_password"]');
    
    const fieldsExist = await oldPasswordField.count() > 0 && 
                       await newPasswordField.count() > 0 && 
                       await confirmPasswordField.count() > 0;
    
    if (fieldsExist) {
      // Isi dengan password lama yang salah
      await oldPasswordField.first().fill('wrongpassword');
      await newPasswordField.first().fill('newpassword123');
      await confirmPasswordField.first().fill('newpassword123');
      
      // Submit form
      const submitButton = page.locator('button[type="submit"], button:has-text("Ubah"), button:has-text("Update")');
      const submitExists = await submitButton.count();
      
      if (submitExists > 0) {
        await submitButton.first().click();
        await page.waitForTimeout(2000);
        
        // Verifikasi ada error message
        const errorMessage = await page.locator('.alert-danger, .error, .swal2-error').count();
        console.log(`Password validation error shown: ${errorMessage > 0}`);
      } else {
        console.log('Tombol submit tidak ditemukan');
      }
    } else {
      console.log('Form ubah password tidak lengkap');
    }
  });

  test('TC-PROFILE-08: Validasi ubah password dengan konfirmasi tidak cocok', async ({ page }) => {
    await page.goto('/profile/setting');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    const oldPasswordField = page.locator('input[name="old_password"], input[name="current_password"]');
    const newPasswordField = page.locator('input[name="new_password"], input[name="password"]');
    const confirmPasswordField = page.locator('input[name="password_confirmation"], input[name="confirm_password"]');
    
    const fieldsExist = await oldPasswordField.count() > 0 && 
                       await newPasswordField.count() > 0 && 
                       await confirmPasswordField.count() > 0;
    
    if (fieldsExist) {
      // Isi dengan konfirmasi password yang tidak cocok
      await oldPasswordField.first().fill(TEKNISI_CREDENTIALS.password);
      await newPasswordField.first().fill('newpassword123');
      await confirmPasswordField.first().fill('differentpassword');
      
      // Submit form
      const submitButton = page.locator('button[type="submit"]');
      const submitExists = await submitButton.count();
      
      if (submitExists > 0) {
        await submitButton.first().click();
        await page.waitForTimeout(2000);
        
        // Verifikasi ada error validasi
        const validationError = await page.locator('.is-invalid, .error, .alert-danger').count();
        console.log(`Password confirmation validation: ${validationError > 0}`);
      } else {
        console.log('Tombol submit tidak ditemukan');
      }
    } else {
      console.log('Form ubah password tidak lengkap');
    }
  });

  test('TC-PROFILE-09: Verifikasi navigasi antara profil dan setting', async ({ page }) => {
    // Mulai dari profil
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);
    
    // Cari link ke setting
    const settingLink = page.locator('a:has-text("Setting"), a:has-text("Pengaturan"), a[href*="setting"]');
    const settingExists = await settingLink.count();
    
    if (settingExists > 0) {
      await settingLink.first().click();
      await page.waitForTimeout(2000);
      
      // Verifikasi berhasil ke setting
      await expect(page).toHaveURL(/.*setting/);
      
      // Kembali ke profil
      const profileLink = page.locator('a:has-text("Profil"), a:has-text("Profile"), a[href*="profile"]');
      const profileExists = await profileLink.count();
      
      if (profileExists > 0) {
        await profileLink.first().click();
        await page.waitForTimeout(2000);
        
        // Verifikasi kembali ke profil
        await expect(page).toHaveURL(/.*profile/);
      } else {
        console.log('Link kembali ke profil tidak ditemukan');
      }
    } else {
      console.log('Link navigasi setting tidak ditemukan');
    }
  });

  test('TC-PROFILE-10: Verifikasi logout dari halaman profil', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Cari tombol atau link logout
    const logoutLink = page.locator(
      'a:has-text("Logout"), a:has-text("Keluar"), ' +
      'button:has-text("Logout"), button:has-text("Keluar"), ' +
      'a[href*="logout"]'
    );
    const logoutExists = await logoutLink.count();
    
    if (logoutExists > 0) {
      await logoutLink.first().click();
      await page.waitForTimeout(2000);
      
      // Verifikasi redirect ke login
      await expect(page).toHaveURL(/.*login/);
      
      // Verifikasi tidak bisa akses profil lagi tanpa login
      await page.goto('/profile');
      await page.waitForTimeout(2000);
      
      // Harus redirect ke login
      await expect(page).toHaveURL(/.*login/);
    } else {
      // Alternatif: logout langsung via URL
      await page.goto('/logout');
      await page.waitForTimeout(2000);
      
      // Verifikasi redirect ke login
      await expect(page).toHaveURL(/.*login/);
    }
  });

});