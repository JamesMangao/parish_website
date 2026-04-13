<?php

namespace App\Http\Controllers;

use App\Models\MassSchedule;
use Illuminate\Http\Request;

class MassScheduleController extends Controller
{
    public function index()
    {
        $schedules = MassSchedule::where('is_active', true)
            ->orderByRaw('time->>0 asc')
            ->get()
            ->groupBy('mass_type');

        return view('mass-schedule', compact('schedules'));
    }

    public function generateICal($id)
    {
        $schedule = MassSchedule::findOrFail($id);
        
        $title = $schedule->title ?: 'Mass Schedule';
        $location = 'Sto. Rosario Parish, Pacita';
        
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Sto Rosario Parish//NONSGML Event Calendar//EN\r\n";
        
        $times = is_array($schedule->time) ? $schedule->time : [$schedule->time];
        
        foreach($times as $time) {
            $dtStartBase = $schedule->specific_date ? $schedule->specific_date : now()->next('Sunday');
            $startTime = \Carbon\Carbon::parse($time);
            $dtStart = $dtStartBase->copy()->setTime($startTime->hour, $startTime->minute)->format('Ymd\THis');
            $dtEnd = $dtStartBase->copy()->setTime($startTime->hour, $startTime->minute)->addHour()->format('Ymd\THis');
            
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . uniqid() . "@parishpal.com\r\n";
            $ical .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $ical .= "DTSTART:" . $dtStart . "\r\n";
            $ical .= "DTEND:" . $dtEnd . "\r\n";
            $ical .= "SUMMARY:" . $title . "\r\n";
            $ical .= "LOCATION:" . $location . "\r\n";
            
            if ($schedule->day_of_week) {
                $days = is_array($schedule->day_of_week) ? $schedule->day_of_week : [$schedule->day_of_week];
                $dayMap = [
                    'sunday' => 'SU', 'monday' => 'MO', 'tuesday' => 'TU', 
                    'wednesday' => 'WE', 'thursday' => 'TH', 'friday' => 'FR', 'saturday' => 'SA',
                    'Sunday' => 'SU', 'Monday' => 'MO', 'Tuesday' => 'TU', 
                    'Wednesday' => 'WE', 'Thursday' => 'TH', 'Friday' => 'FR', 'Saturday' => 'SA'
                ];
                $byDay = [];
                foreach($days as $d) {
                    if (isset($dayMap[$d])) $byDay[] = $dayMap[$d];
                }
                if (!empty($byDay)) {
                    $ical .= "RRULE:FREQ=WEEKLY;BYDAY=" . implode(',', $byDay) . "\r\n";
                }
            }
            
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR";
        
        return response($ical)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="mass-schedule-' . $id . '.ics"');
    }
}
