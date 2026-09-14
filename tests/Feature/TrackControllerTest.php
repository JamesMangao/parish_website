<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\MassIntention;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TrackControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_reference_id_lookup_still_works(): void
    {
        $intention = MassIntention::create([
            'full_name' => 'Juan Dela Cruz',
            'intention_type' => 'thanksgiving',
            'raw_message' => 'Thanksgiving mass',
            'formatted_message' => 'Thanksgiving',
            'preferred_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->get(route('track.status', $intention->reference_number))
            ->assertOk()
            ->assertSee($intention->reference_number)
            ->assertSee('Juan Dela Cruz');
    }

    public function test_email_lookup_no_longer_exposes_records(): void
    {
        MassIntention::create([
            'full_name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'intention_type' => 'thanksgiving',
            'raw_message' => 'Thanksgiving mass',
            'formatted_message' => 'Thanksgiving',
            'preferred_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        Inquiry::create([
            'full_name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'inquiry_type' => 'Baptism',
            'message' => 'Need baptism details',
            'preferred_date' => now()->addWeek()->toDateString(),
            'status' => 'pending',
            'reference_id' => (string) Str::uuid(),
        ]);

        $this->get(route('track.status', 'juan@example.com'))
            ->assertRedirect(route('track'))
            ->assertSessionHasErrors('reference_id');
    }

    public function test_static_reference_to_a_failed_match_redirects_with_error(): void
    {
        $this->get(route('track.status', 'NO-SUCH-REF'))
            ->assertRedirect(route('track'))
            ->assertSessionHasErrors('reference_id');
    }
}
