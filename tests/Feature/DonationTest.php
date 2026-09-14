<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    private function staffAdmin(): User
    {
        return User::create([
            'name' => 'Staff',
            'email' => 'staff@parish.test',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'is_active' => true,
        ]);
    }

    private function donation(array $overrides = []): Donation
    {
        return Donation::create(array_merge([
            'donor_name' => 'Donor',
            'amount' => 50000, // ₱500.00 (centavos)
            'currency' => 'PHP',
            'purpose' => 'General Donation',
            'status' => 'paid',
            'checkout_session_id' => 'cs_'.uniqid(),
        ], $overrides));
    }

    public function test_admin_donations_aggregates_only_paid(): void
    {
        $this->donation(['status' => 'paid', 'amount' => 50000]);
        $this->donation(['status' => 'failed', 'amount' => 70000]);
        $this->donation(['status' => 'pending', 'amount' => 30000]);

        $response = $this->actingAs($this->staffAdmin())
            ->get(route('admin.donations'))
            ->assertOk();

        $response->assertSee('500.00'); // Total Received (paid only)
        $response->assertSee('1');       // Total Donations count
        $response->assertSee('700.00');  // failed donation row still listed
    }

    public function test_donation_paid_scope_uses_paid_status(): void
    {
        $this->donation(['status' => 'paid']);
        $this->donation(['status' => 'completed']);

        $this->assertSame(1, Donation::paid()->count());
    }
}
