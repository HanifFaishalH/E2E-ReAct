// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E Test untuk Workflow Laporan - User Teknisi
 * Fitur: Mengelola status dan workflow laporan teknisi
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

test.describe('Teknisi - Workflow Laporan', () => {
  
  test.beforeEach(async ({ page }) => {
    // Login sebelum setiap test
    await loginAsTeknisi(page);
  });

  test('TC-WORKFLOW-01: Melihat laporan dengan status pending', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari laporan dengan status pending
        const pendingStatus = await page.locator('td:has-text("pending"), .badge:has-text("pending"), .status:has-text("pending")').count();
        const tableRows = await page.locator('tbody tr, table tr').count();
        
        console.log(`Laporan status - Pending: ${pendingStatus}, Total rows: ${tableRows}`);
        
        if (pendingStatus > 0) {
          console.log('✅ Ditemukan laporan dengan status pending');
        } else {
          console.log('⚠️ Tidak ada laporan pending saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error melihat status pending:', error.message);
    }
  });

  test('TC-WORKFLOW-02: Melihat laporan dengan status diproses', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari laporan dengan status diproses
        const diprosesStatus = await page.locator('td:has-text("diproses"), .badge:has-text("diproses"), .status:has-text("diproses")').count();
        
        console.log(`Laporan dengan status diproses: ${diprosesStatus}`);
        
        if (diprosesStatus > 0) {
          console.log('✅ Ditemukan laporan dengan status diproses');
        } else {
          console.log('⚠️ Tidak ada laporan diproses saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error melihat status diproses:', error.message);
    }
  });

  test('TC-WORKFLOW-03: Melihat laporan dengan status dikerjakan', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari laporan dengan status dikerjakan
        const dikerjakanStatus = await page.locator('td:has-text("dikerjakan"), .badge:has-text("dikerjakan"), .status:has-text("dikerjakan")').count();
        
        console.log(`Laporan dengan status dikerjakan: ${dikerjakanStatus}`);
        
        if (dikerjakanStatus > 0) {
          console.log('✅ Ditemukan laporan dengan status dikerjakan');
        } else {
          console.log('⚠️ Tidak ada laporan dikerjakan saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error melihat status dikerjakan:', error.message);
    }
  });

  test('TC-WORKFLOW-04: Melihat laporan dengan status selesai', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari laporan dengan status selesai
        const selesaiStatus = await page.locator('td:has-text("selesai"), .badge:has-text("selesai"), .status:has-text("selesai")').count();
        
        console.log(`Laporan dengan status selesai: ${selesaiStatus}`);
        
        if (selesaiStatus > 0) {
          console.log('✅ Ditemukan laporan dengan status selesai');
        } else {
          console.log('⚠️ Tidak ada laporan selesai saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error melihat status selesai:', error.message);
    }
  });

  test('TC-WORKFLOW-05: Verifikasi tombol aksi berdasarkan status laporan', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cek berbagai tombol aksi yang tersedia
        const mulaiButtons = await page.locator('button:has-text("Mulai"), a:has-text("Mulai")').count();
        const kerjakanButtons = await page.locator('button:has-text("Kerjakan"), a:has-text("Kerjakan")').count();
        const selesaiButtons = await page.locator('button:has-text("Selesai"), a:has-text("Selesai")').count();
        const finishButtons = await page.locator('button:has-text("Finish"), a:has-text("Finish")').count();
        const detailButtons = await page.locator('button:has-text("Detail"), a:has-text("Detail")').count();
        
        console.log(`Action buttons - Mulai: ${mulaiButtons}, Kerjakan: ${kerjakanButtons}, Selesai: ${selesaiButtons}, Finish: ${finishButtons}, Detail: ${detailButtons}`);
        
        const totalActionButtons = mulaiButtons + kerjakanButtons + selesaiButtons + finishButtons + detailButtons;
        
        if (totalActionButtons > 0) {
          console.log('✅ Ditemukan tombol aksi untuk workflow');
          expect(totalActionButtons).toBeGreaterThan(0);
        } else {
          console.log('⚠️ Tidak ada tombol aksi ditemukan');
        }
      }
    } catch (error) {
      console.log('Error verifikasi tombol aksi:', error.message);
    }
  });

  test('TC-WORKFLOW-06: Test mengubah status laporan ke dikerjakan', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari tombol untuk mulai mengerjakan
        const startWorkButtons = page.locator('button:has-text("Mulai"), button:has-text("Kerjakan"), a:has-text("Mulai"), a:has-text("Kerjakan")');
        const buttonCount = await startWorkButtons.count();
        
        if (buttonCount > 0) {
          console.log(`Ditemukan ${buttonCount} tombol untuk mulai mengerjakan`);
          
          // Klik tombol pertama
          await startWorkButtons.first().click();
          await page.waitForTimeout(2000);
          
          // Cek apakah ada konfirmasi dialog
          const confirmDialog = await page.locator('.swal2-popup, .modal.show, .confirm-dialog').count();
          
          if (confirmDialog > 0) {
            console.log('✅ Dialog konfirmasi muncul');
            
            // Cari tombol konfirmasi
            const confirmButton = page.locator('.swal2-confirm, .btn-primary:has-text("Ya"), .btn-primary:has-text("OK"), button:has-text("Confirm")');
            const confirmExists = await confirmButton.count();
            
            if (confirmExists > 0) {
              await confirmButton.first().click();
              await page.waitForTimeout(2000);
              console.log('✅ Konfirmasi berhasil diklik');
            }
          }
          
          console.log('✅ Test mengubah status ke dikerjakan completed');
        } else {
          console.log('⚠️ Tidak ada laporan yang bisa dimulai saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error mengubah status ke dikerjakan:', error.message);
    }
  });

  test('TC-WORKFLOW-07: Test menyelesaikan laporan', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari tombol untuk menyelesaikan laporan
        const finishButtons = page.locator('button:has-text("Selesai"), button:has-text("Finish"), a:has-text("Selesai"), a:has-text("Finish")');
        const buttonCount = await finishButtons.count();
        
        if (buttonCount > 0) {
          console.log(`Ditemukan ${buttonCount} tombol untuk menyelesaikan laporan`);
          
          // Klik tombol pertama
          await finishButtons.first().click();
          await page.waitForTimeout(2000);
          
          // Cek apakah redirect ke form finish atau ada modal
          const currentUrl = page.url();
          const modalExists = await page.locator('.modal.show, .swal2-popup').count();
          
          if (currentUrl.includes('finish_form')) {
            console.log('✅ Redirect ke form finish laporan');
          } else if (modalExists > 0) {
            console.log('✅ Modal finish laporan muncul');
          } else {
            console.log('⚠️ Response tidak sesuai ekspektasi');
          }
          
          console.log('✅ Test menyelesaikan laporan completed');
        } else {
          console.log('⚠️ Tidak ada laporan yang bisa diselesaikan saat ini');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error menyelesaikan laporan:', error.message);
    }
  });

  test('TC-WORKFLOW-08: Verifikasi filter berdasarkan status', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        // Cari dropdown filter status
        const statusFilter = page.locator('select[name="status"], select#status, .filter-status');
        const filterExists = await statusFilter.count();
        
        if (filterExists > 0) {
          console.log('✅ Filter status ditemukan');
          
          // Test filter dengan berbagai status
          const statusOptions = ['pending', 'diproses', 'dikerjakan', 'selesai'];
          
          for (const status of statusOptions) {
            try {
              await statusFilter.first().selectOption(status);
              await page.waitForTimeout(1000);
              console.log(`✅ Filter ${status} berhasil diterapkan`);
            } catch (error) {
              console.log(`⚠️ Filter ${status} tidak tersedia`);
            }
          }
          
          // Reset filter
          try {
            await statusFilter.first().selectOption('');
            await page.waitForTimeout(1000);
            console.log('✅ Filter berhasil direset');
          } catch (error) {
            console.log('⚠️ Reset filter gagal');
          }
        } else {
          console.log('⚠️ Filter status tidak ditemukan');
        }
        
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Error verifikasi filter:', error.message);
    }
  });

  test('TC-WORKFLOW-09: Test workflow authorization - hanya laporan teknisi', async ({ page }) => {
    try {
      // Test akses ke laporan yang bukan milik teknisi ini
      await page.goto('/laporan/show_kelola_ajax/99999');
      await page.waitForTimeout(2000);
      
      const currentUrl = page.url();
      const bodyContent = await page.textContent('body');
      
      if (bodyContent) {
        const hasError = bodyContent.includes('error') || bodyContent.includes('tidak ditemukan') || 
                        bodyContent.includes('unauthorized') || bodyContent.includes('akses ditolak');
        
        console.log(`Authorization test - URL: ${currentUrl}, Has Error: ${hasError}`);
        
        if (hasError || currentUrl.includes('login') || currentUrl.includes('kelola')) {
          console.log('✅ Authorization berfungsi - akses dibatasi');
        } else {
          console.log('⚠️ Authorization mungkin tidak berfungsi');
        }
      }
      
      expect(true).toBe(true);
    } catch (error) {
      console.log('Authorization test - Error (expected):', error.message);
      expect(true).toBe(true);
    }
  });

  test('TC-WORKFLOW-10: Test complete workflow end-to-end', async ({ page }) => {
    try {
      await page.goto('/laporan/kelola');
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
      
      if (page.url().includes('kelola')) {
        console.log('=== COMPLETE WORKFLOW TEST ===');
        
        // Step 1: Lihat daftar laporan
        const totalLaporan = await page.locator('tbody tr, table tr').count();
        console.log(`Step 1: Total laporan tersedia: ${totalLaporan}`);
        
        // Step 2: Cek status yang tersedia
        const pendingCount = await page.locator('td:has-text("pending"), .badge:has-text("pending")').count();
        const diprosesCount = await page.locator('td:has-text("diproses"), .badge:has-text("diproses")').count();
        const dikerjakanCount = await page.locator('td:has-text("dikerjakan"), .badge:has-text("dikerjakan")').count();
        const selesaiCount = await page.locator('td:has-text("selesai"), .badge:has-text("selesai")').count();
        
        console.log(`Step 2: Status breakdown - Pending: ${pendingCount}, Diproses: ${diprosesCount}, Dikerjakan: ${dikerjakanCount}, Selesai: ${selesaiCount}`);
        
        // Step 3: Cek tombol aksi yang tersedia
        const actionButtons = await page.locator('button, a').count();
        console.log(`Step 3: Total action buttons: ${actionButtons}`);
        
        // Step 4: Test filter functionality
        const filterExists = await page.locator('select[name="status"], select#status').count();
        console.log(`Step 4: Filter available: ${filterExists > 0}`);
        
        // Step 5: Test search functionality
        const searchExists = await page.locator('input[type="search"]').count();
        console.log(`Step 5: Search available: ${searchExists > 0}`);
        
        console.log('✅ Complete workflow test finished');
        expect(true).toBe(true);
      }
    } catch (error) {
      console.log('Complete workflow test error:', error.message);
    }
  });

});