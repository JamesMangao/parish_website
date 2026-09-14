<?php

namespace Tests\Feature;

use App\Models\MassSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MassScheduleIcalTest extends TestCase
{
    use RefreshDatabase;

    private function schedule(array $overrides = []): MassSchedule
    {
        return MassSchedule::create(array_merge([
            'title' => 'Weekday Mass',
            'mass_type' => 'weekday',
            'day_of_week' => ['Monday', 'Wednesday'],
            'time' => ['18:00'],
            'location' => 'Main Church',
            'is_active' => true,
        ], $overrides));
    }

    public function test_recurring_schedule_has_deterministic_uid_and_rrule(): void
    {
        $schedule = $this->schedule();

        $response = $this->get(route('mass-schedule.ical', $schedule->id))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8');

        $body = $response->getContent();

        $this->assertStringContainsString('RRULE:FREQ=WEEKLY;BYDAY=MO,WE', $body);
        $this->assertStringContainsString('UID:mass-schedule-'.$schedule->id.'-1800@storosario.ph', $body);
        $this->assertStringNotContainsString('parishpal.com', $body);
        $this->assertStringNotContainsString('uniqid', $body);
    }

    public function test_uid_is_stable_across_generations(): void
    {
        $schedule = $this->schedule();

        $first = $this->get(route('mass-schedule.ical', $schedule->id))->getContent();
        $second = $this->get(route('mass-schedule.ical', $schedule->id))->getContent();

        preg_match_all('/UID:(.+)/', $first, $a);
        preg_match_all('/UID:(.+)/', $second, $b);

        $this->assertEquals($a[1], $b[1]);
    }

    public function test_specific_date_schedule_is_single_event_without_rrule(): void
    {
        $schedule = $this->schedule([
            'specific_date' => now()->addDays(10)->toDateString(),
            'day_of_week' => null,
        ]);

        $body = $this->get(route('mass-schedule.ical', $schedule->id))->getContent();

        $this->assertStringContainsString('DTSTART:'.now()->addDays(10)->format('Ymd'), $body);
        $this->assertStringNotContainsString('RRULE:', $body);
    }

    public function test_special_characters_are_escaped_in_ical_fields(): void
    {
        $schedule = $this->schedule(['title' => 'Friday, 6 PM & Rosary']);

        $body = $this->get(route('mass-schedule.ical', $schedule->id))->getContent();

        $this->assertStringContainsString('Friday\\, 6 PM & Rosary', $body);
    }
}
