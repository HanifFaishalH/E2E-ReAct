// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Sarpras - Login Tests', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should login successfully with valid sarpras credentials', async ({ page }) => {
    // Fill login form
    await page.fill('input[name="username"]', 'sarpras');
    await page.fill('input[name="password"]', 'sarpras');
    
    // Submit form
    await page.click('button[type="submit"]');
    
    // Wait for navigation to complete
    await page.waitForLoadState('networkidle');
    
    // Verify redirect to dashboard
    await expect(page).toHaveURL(/\/(welcome|dashboard|home)/);
    
    // Verify user is logged in (check for user info or logout button)
    await expect(page.locator('text=/sarpras/i').first()).toBeVisible({ timeout: 10000 });
  });

  test('should show error message with invalid credentials', async ({ page }) => {
    await page.fill('input[name="username"]', 'sarpras');
    await page.fill('input[name="password"]', 'wrongpassword');
    
    await page.click('button[type="submit"]');
    
    // Wait for error message
    await page.waitForTimeout(1000);
    
    // Should stay on login page or show error
    const currentUrl = page.url();
    expect(currentUrl).toContain('login');
  });

  test('should show validation error with empty fields', async ({ page }) => {
    await page.click('button[type="submit"]');
    
    // Check for HTML5 validation or error messages
    const usernameInput = page.locator('input[name="username"]');
    await expect(usernameInput).toHaveAttribute('required', '');
  });

  test('should logout successfully', async ({ page }) => {
    // Login first
    await page.fill('input[name="username"]', 'sarpras');
    await page.fill('input[name="password"]', 'sarpras');
    await page.click('button[type="submit"]');
    
    await page.waitForLoadState('networkidle');
    
    // Find and click logout button/link
    const logoutButton = page.locator('a[href*="logout"], button:has-text("logout"), a:has-text("logout")').first();
    await logoutButton.click();
    
    // Wait for redirect
    await page.waitForTimeout(1000);
    
    // Verify redirected to login page
    await expect(page).toHaveURL(/login/);
  });
});
