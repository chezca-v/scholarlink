import { test, expect } from '@playwright/test';

// Uses the seeded applicant account (database/seeders/UserSeeder.php,
// database/seeders/ApplicantProfileSeeder.php). Run
// `php artisan migrate:fresh --seed` before this spec.
const SEEDED_APPLICANT = {
    email: 'maria.santos@gmail.com',
    password: 'Maria@001',
};

test.describe('Applicant portal', () => {
    test('can log in and reach the dashboard', async ({ page }) => {
        await page.goto('/login');
        await page.fill('input[name="email"]', SEEDED_APPLICANT.email);
        await page.fill('input[name="password"]', SEEDED_APPLICANT.password);
        await page.click('button.btn-login');

        await expect(page).toHaveURL(/\/dashboard$/);
        await expect(page.getByText('AI-Matched for You')).toBeVisible();
    });

    test('can browse scholarships and open the application wizard', async ({ page }) => {
        await page.goto('/login');
        await page.fill('input[name="email"]', SEEDED_APPLICANT.email);
        await page.fill('input[name="password"]', SEEDED_APPLICANT.password);
        await page.click('button.btn-login');
        await expect(page).toHaveURL(/\/dashboard$/);

        await page.goto('/scholarships');
        await page.locator('a.btn-apply').first().click();

        // The 3-step wizard (resources/views/applicant/applications/create.blade.php).
        // Step 1 shows eligibility checks; advancing past it depends on the
        // seeded applicant's profile matching the specific scholarship's
        // requirements, so this spec stops at confirming the wizard loads.
        await expect(page.locator('#wizard')).toBeVisible();
        await expect(page.locator('#panel-1')).toHaveClass(/active/);
    });
});
