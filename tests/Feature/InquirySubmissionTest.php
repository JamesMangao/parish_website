<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_wedding_inquiry_stores_message_from_textarea(): void
    {
        $response = $this->post(route('inquiry.store'), [
            'fullName' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'phone' => '09123456789',
            'inquiryType' => 'Wedding',
            'preferredDate' => now()->addMonths(3)->toDateString(),
            'message' => 'Wedding inquiry for December ceremony.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reference_id');

        $this->assertDatabaseHas('inquiries', [
            'email' => 'juan@example.com',
            'inquiry_type' => 'Wedding',
            'message' => 'Wedding inquiry for December ceremony.',
            'status' => 'pending',
        ]);
    }

    public function test_baptismal_certificate_stores_structured_message(): void
    {
        $message = "NAME: Maria Santos\nBIRTHDATE: 1990-01-15\nBAPTISM: 1990-02-01";

        $response = $this->post(route('inquiry.store'), [
            'fullName' => 'Maria Santos',
            'email' => 'maria@example.com',
            'inquiryType' => 'Baptismal Certificate',
            'message' => $message,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reference_id');

        $inquiry = Inquiry::where('email', 'maria@example.com')->first();
        $this->assertNotNull($inquiry);
        $this->assertSame('Baptismal Certificate', $inquiry->inquiry_type);
        $this->assertStringContainsString('NAME: Maria Santos', $inquiry->message);
        $this->assertStringContainsString('BIRTHDATE: 1990-01-15', $inquiry->message);
        $this->assertStringContainsString('BAPTISM: 1990-02-01', $inquiry->message);
    }

    public function test_confirmation_certificate_stores_textarea_message(): void
    {
        $response = $this->post(route('inquiry.store'), [
            'fullName' => 'Pedro Reyes',
            'email' => 'pedro@example.com',
            'inquiryType' => 'Confirmation Certificate',
            'wants_document' => 'yes',
            'message' => 'Requesting confirmation certificate for employment.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reference_id');

        $inquiry = Inquiry::where('email', 'pedro@example.com')->first();
        $this->assertNotNull($inquiry);
        $this->assertStringContainsString('Requesting confirmation certificate', $inquiry->message);
        $this->assertStringContainsString('[Wants to request a document: YES]', $inquiry->message);
    }

    public function test_empty_message_fails_validation(): void
    {
        $response = $this->post(route('inquiry.store'), [
            'fullName' => 'Test User',
            'email' => 'test@example.com',
            'inquiryType' => 'Other',
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('inquiries', 0);
    }
}
