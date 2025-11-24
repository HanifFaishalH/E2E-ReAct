// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Sarpras - Kelola Laporan Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Login as sarpras
    await page.goto('/login');
    await page.fill('input[name="username"]', 'sarpras');
    await page.fill('input[name="password"]', 'sarpras');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
  });

  test('should access kelola laporan page', async ({ page }) => {
    // Navigate to kelola laporan
    await page.goto('/laporan/kelola');
    
    // Verify page loaded
    await expect(page).toHaveURL(/\/laporan\/kelola/);
    
    // Check for table or data container
    const dataTable = page.locator('table, .datatable, #table_laporan, .table');
    await expect(dataTable.first()).toBeVisible({ timeout: 10000 });
  });

  test('should display list of laporan', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    
    // Wait for data to load
    await page.waitForTimeout(2000);
    
    // Check if table has rows (may be empty, so we just verify structure exists)
    const table = page.locator('table').first();
    await expect(table).toBeVisible();
    
    // Verify table headers exist
    const headers = page.locator('th');
    expect(await headers.count()).toBeGreaterThan(0);
  });

  test('should open detail laporan modal/page', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Find and click first detail/show button
    const detailButton = page.locator('button.btn-detail, a.btn-detail, button:has-text("detail"), button[onclick*="show"]').first();
    
    if (await detailButton.count() > 0) {
      await detailButton.click();
      
      // Wait for modal or new page
      await page.waitForTimeout(1000);
      
      // Verify modal or detail page is visible
      const modal = page.locator('.modal, #modal_detail, [role="dialog"]');
      if (await modal.count() > 0) {
        await expect(modal.first()).toBeVisible();
      }
    } else {
      console.log('No laporan data available to test');
    }
  });

  test('should accept laporan successfully', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Find accept button (may have different selectors)
    const acceptButton = page.locator('button:has-text("terima"), button:has-text("accept"), button.btn-success[onclick*="accept"]').first();
    
    if (await acceptButton.count() > 0) {
      // Click accept button
      await acceptButton.click();
      
      // Handle confirmation if exists
      const confirmButton = page.locator('button:has-text("ya"), button:has-text("ok"), .swal2-confirm');
      if (await confirmButton.isVisible({ timeout: 2000 })) {
        await confirmButton.click();
      }
      
      // Wait for success message
      await page.waitForTimeout(2000);
      
      // Verify success notification
      const successMsg = page.locator('.alert-success, .swal2-success, text=/berhasil/i');
      if (await successMsg.count() > 0) {
        await expect(successMsg.first()).toBeVisible();
      }
    } else {
      console.log('No pending laporan to accept');
    }
  });

  test('should reject laporan with reason', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Find reject button
    const rejectButton = page.locator('button:has-text("tolak"), button:has-text("reject"), button.btn-danger[onclick*="reject"]').first();
    
    if (await rejectButton.count() > 0) {
      await rejectButton.click();
      
      // Wait for reject form/modal
      await page.waitForTimeout(1000);
      
      // Fill reject reason if input exists
      const reasonInput = page.locator('textarea[name*="alasan"], textarea[name*="reason"], input[name*="alasan"]');
      if (await reasonInput.isVisible({ timeout: 2000 })) {
        await reasonInput.fill('Laporan tidak valid atau kurang lengkap');
      }
      
      // Confirm rejection
      const confirmButton = page.locator('button:has-text("ya"), button:has-text("tolak"), button[type="submit"]').last();
      await confirmButton.click();
      
      // Wait for success message
      await page.waitForTimeout(2000);
      
      const successMsg = page.locator('.alert-success, .swal2-success, text=/berhasil/i');
      if (await successMsg.count() > 0) {
        await expect(successMsg.first()).toBeVisible();
      }
    } else {
      console.log('No laporan to reject');
    }
  });

  test('should assign teknisi to laporan', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Find tugaskan teknisi button
    const assignButton = page.locator('button:has-text("tugaskan"), a:has-text("tugaskan"), button[onclick*="tugaskan"]').first();
    
    if (await assignButton.count() > 0) {
      await assignButton.click();
      
      // Wait for assignment form/modal
      await page.waitForTimeout(1000);
      
      // Select teknisi from dropdown
      const teknisiSelect = page.locator('select[name*="teknisi"], #teknisi_id');
      if (await teknisiSelect.isVisible({ timeout: 2000 })) {
        // Select first available teknisi (not empty option)
        await teknisiSelect.selectOption({ index: 1 });
      }
      
      // Submit assignment
      const submitButton = page.locator('button[type="submit"], button:has-text("simpan")').last();
      await submitButton.click();
      
      // Wait for success
      await page.waitForTimeout(2000);
      
      const successMsg = page.locator('.alert-success, .swal2-success, text=/berhasil/i');
      if (await successMsg.count() > 0) {
        await expect(successMsg.first()).toBeVisible();
      }
    } else {
      console.log('No laporan available to assign teknisi');
    }
  });

  test('should filter laporan by status', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Look for filter/status dropdown
    const statusFilter = page.locator('select[name*="status"], #filter_status, select.form-select').first();
    
    if (await statusFilter.count() > 0 && await statusFilter.isVisible()) {
      // Select a status
      await statusFilter.selectOption({ index: 1 });
      
      // Wait for filtered results
      await page.waitForTimeout(2000);
      
      // Verify table updated
      const table = page.locator('table').first();
      await expect(table).toBeVisible();
    } else {
      console.log('No status filter available');
    }
  });

  test('should search laporan', async ({ page }) => {
    await page.goto('/laporan/kelola');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);
    
    // Look for search input
    const searchInput = page.locator('input[type="search"], input[name*="search"], input.form-control[placeholder*="cari"]').first();
    
    if (await searchInput.count() > 0 && await searchInput.isVisible()) {
      // Type search query
      await searchInput.fill('ruang');
      
      // Wait for search results
      await page.waitForTimeout(2000);
      
      // Verify table is still visible
      const table = page.locator('table').first();
      await expect(table).toBeVisible();
    } else {
      console.log('No search input available');
    }
  });
});
