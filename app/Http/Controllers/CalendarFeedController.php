<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\MassSchedule;

class CalendarFeedController extends Controller
{
    public function ical()
    {
        $events = Event::where('event_date', '>=', now()->subMonth())->get();
        $masses = MassSchedule::where('is_active', true)->get();

        $lines = [];
        $lines[] = 'BEGIN:VCALENDAR';
        $lines[] = 'VERSION:2.0';
        $lines[] = 'PRODID:-//StoRosarioParish//Calendar//EN';
        $lines[] = 'CALSCALE:GREGORIAN';
        $lines[] = 'METHOD:PUBLISH';
        $lines[] = 'X-WR-CALNAME:Sto. Rosario Parish';

        foreach ($events as $event) {
            $dtStart = \Carbon\Carbon::parse($event->event_date)->format('Ymd');
            $dtEnd = \Carbon\Carbon::parse($event->event_date)->format('Ymd');

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:event-' . $event->id . '@storosario.ph';
            $lines[] = 'DTSTART;VALUE=DATE:' . $dtStart;
            $lines[] = 'DTEND;VALUE=DATE:' . $dtEnd;
            $lines[] = 'SUMMARY:' . $this->escapeIcal($event->title);
            if ($event->description) {
                $lines[] = 'DESCRIPTION:' . $this->escapeIcal($event->description);
            }
            $lines[] = 'END:VEVENT';
        }

        foreach ($masses as $mass) {
            $daysOfWeek = [
                'Sunday' => 'SU', 'Monday' => 'MO', 'Tuesday' => 'TU',
                'Wednesday' => 'WE', 'Thursday' => 'TH', 'Friday' => 'FR', 'Saturday' => 'SA',
            ];
            if ($mass->day_of_week && isset($daysOfWeek[$mass->day_of_week])) {
                $time = $mass->mass_time ? \Carbon\Carbon::parse($mass->mass_time)->format('His') : '090000';
                $rrule = 'FREQ=WEEKLY;BYDAY=' . $daysOfWeek[$mass->day_of_week];

                $lines[] = 'BEGIN:VEVENT';
                $lines[] = 'UID:mass-' . $mass->id . '@storosario.ph';
                $lines[] = 'DTSTART;TZID=Asia/Manila:' . now()->format('Ymd') . 'T' . $time;
                $lines[] = 'RRULE:' . $rrule;
                $lines[] = 'SUMMARY:' . $this->escapeIcal($mass->mass_type . ' Mass');
                if ($mass->description) {
                    $lines[] = 'DESCRIPTION:' . $this->escapeIcal($mass->description);
                }
                $lines[] = 'LOCATION:' . ($mass->location ?? 'Sto. Rosario Parish Church');
                $lines[] = 'END:VEVENT';
            }
        }

        $lines[] = 'END:VCALENDAR';

        $content = implode("\r\n", $lines);

        return response($content, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="calendar.ics"',
        ]);
    }

    private function escapeIcal($text)
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $text = str_replace([',', ';', "\n", "\r"], ['\,', '\;', '\\n', ''], $text);
        return $text;
    }
}
