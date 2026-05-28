<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlankPaymentMethodIntentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_without_payment_method_stores_null(): void
    {
        $response = $this->postJson('/submit-intention', [
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'intentionType' => 'Healing',
            'preferredDate' => now()->addDays(5)->toDateString(),
            'massTime' => '6:00 AM',
            'description' => 'Prayer for healing',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mass_intentions', [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'payment_method' => null,
        ]);
    }

    public function test_submission_with_empty_payment_method_stores_null(): void
    {
        $response = $this->postJson('/submit-intention', [
            'fullName' => 'Jane Doe',
            'email' => 'jane@example.com',
            'intentionType' => 'Thanksgiving',
            'preferredDate' => now()->addDays(6)->toDateString(),
            'massTime' => '8:30 AM',
            'description' => 'Thanksgiving prayer',
            'paymentMethod' => '',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mass_intentions', [
            'full_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'payment_method' => null,
        ]);
    }

    public function test_submission_with_selected_payment_method_stores_value(): void
    {
        $response = $this->postJson('/submit-intention', [
            'fullName' => 'Maria Cruz',
            'email' => 'maria@example.com',
            'intentionType' => 'Birthday',
            'preferredDate' => now()->addDays(7)->toDateString(),
            'massTime' => '10:00 AM',
            'description' => 'Birthday intention',
            'paymentMethod' => 'GCash',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mass_intentions', [
            'full_name' => 'Maria Cruz',
            'email' => 'maria@example.com',
            'payment_method' => 'GCash',
        ]);
    }

    public function test_submit_intention_page_does_not_default_payment_method_to_gcash(): void
    {
        $response = $this->get('/submit-intention');

        $response->assertStatus(200);
        $response->assertDontSee("paymentMethod: 'GCash'");
    }
}
