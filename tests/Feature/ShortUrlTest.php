<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /** Helper company */
    private function makeCompany(string $name = 'Sembark Tech'): Company
    {
        return Company::create(['name' => $name]);
    }

    /** Helper user */
    private function makeUser(string $role, ?int $companyId = null): User
    {
        return User::create([
            'name' => ucfirst($role) . ' User',
            'email' => strtolower($role) . uniqid() . '@example.com',
            'password' => 'password123',
            'role' => $role,
            'company_id' => $companyId,
        ]);
    }

    /* Admin can create short URL */
    public function test_admin_can_create_short_urls(): void
    {
        $company = $this->makeCompany();
        $admin = $this->makeUser('Admin', $company->id);

        $response = $this->actingAs($admin)->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertRedirect('/short-urls');
        $this->assertDatabaseCount('short_urls', 1);
    }

    /*  Member can create short urls */
    public function test_member_can_create_short_urls(): void
    {
        $company = $this->makeCompany();
        $member = $this->makeUser('Member', $company->id);

        $response = $this->actingAs($member)->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertRedirect('/short-urls');
        $this->assertDatabaseCount('short_urls', 1);
    }

    /* Summary of test_super_admin_cannot_create_short_urls */
    public function test_super_admin_cannot_create_short_urls(): void
    {
        $superAdmin = $this->makeUser('SuperAdmin');

        $response = $this->actingAs($superAdmin)->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('short_urls', 0);
    }

    /* Admin sees OnlY their Cmpny short urls*/
    public function test_admin_sees_only_short_urls_in_their_own_company(): void
    {
        $companyA = $this->makeCompany('Company A');
        $companyB = $this->makeCompany('Company B');

        $admin = $this->makeUser('Admin', $companyA->id);
        $adminB = $this->makeUser('Admin', $companyB->id);

        /* Company A short url */
        ShortUrl::create([
            'company_id' => $companyA->id,
            'user_id' => $admin->id,
            'original_url' => 'https://companyA.com',
            'short_code' => 'cA111111',
        ]);

        /* Company B short url */
        ShortUrl::create([
            'company_id' => $companyB->id,
            'user_id' => $adminB->id,
            'original_url' => 'https://companyB.com',
            'short_code' => 'cB222222',
        ]);

        $response = $this->actingAs($admin)->get('/short-urls');
        $response->assertStatus(200);
        $response->assertViewHas('shortUrls', function ($shortUrls) use ($companyA) {
            return $shortUrls->count() === 1
                && $shortUrls->first()->company_id === $companyA->id;
        });
    }

    /* Member sees only their own created short urls */
    public function test_member_sees_only_short_urls_created_by_themselves(): void
    {
        $company = $this->makeCompany();
        $member = $this->makeUser('Member', $company->id);
        $admin = $this->makeUser('Admin', $company->id);

        /* Member short url */
        ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'original_url' => 'https://mine.com',
            'short_code' => 'mine1234',
        ]);

        /* Admin short  */
        ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $admin->id,
            'original_url' => 'https://admin.com',
            'short_code' => 'admn1234',
        ]);

        $response = $this->actingAs($member)->get('/short-urls');
        $response->assertStatus(200);
        $response->assertViewHas('shortUrls', function ($shortUrls) use ($member) {
            return $shortUrls->count() === 1
                && $shortUrls->first()->user_id === $member->id;
        });
    }

    /* Short Urls Are publicly resolvable and redirect  */
    public function test_short_urls_are_publicly_resolvable_and_redirect(): void
    {
        $company = $this->makeCompany();
        $admin = $this->makeUser('Admin', $company->id);

        ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $admin->id,
            'original_url' => 'https://google.com',
            'short_code' => 'pubcode1',
        ]);

        /** acceSs Without Login */
        $response = $this->get('/s/pubcode1');
        $response->assertRedirect('https://google.com');
    }
}