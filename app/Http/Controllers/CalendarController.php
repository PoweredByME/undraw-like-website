<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{

    function _makeWeek($date){
        // This function get a date and then
        // by keeping it the central date of the week,
        // i.e. the 4th day, makes a week and returns
        // an array of the form
        // [ { day : 'Monday, etc.', date : '2019-2-24' ... etc} ]
        // ------------------------------------------------
        // This function gets date in form of 'YYYY-MM-DD'

        $carbon = new Carbon($date);
        $carbon->add(-3, 'day');
        $week = [];
        for($i = 0; $i < 7; $i += 1){
            $_date = $carbon->year.'-'.$carbon->month.'-'.$carbon->day;
            $day = $carbon->englishDayOfWeek;
            $week[] = [
                'date' => $carbon->day,
                'month' => $carbon->month,
                'year' => $carbon->year,
                'english_month' => $carbon->englishMonth,
                'full_date' => $_date,
                'day' => $day,
                'items' => [],
            ];
            $carbon->add(1,'day');
        }

        return $week;
    }

    function _makeMonth($date){
        // This function gets a date in month and year
        // YYYY-MM and return an array of all the days
        // in the the month. The array that is returned
        // is in the same formate which is returned by
        // 'makeWeek' function
        $start = Carbon::parse($date)->startOfMonth();
        $end = Carbon::parse($date)->endOfMonth();

        $month = [];
        while ($start->lte($end)) {
            $carbon = $start;
            $_date = $carbon->year.'-'.$carbon->month.'-'.$carbon->day;
            $day = $start->englishDayOfWeek;
            $month[] = [
                'date' => $carbon->day,
                'month' => $carbon->month,
                'year' => $carbon->year,
                'english_month' => $carbon->englishMonth,
                'full_date' => $_date,
                'day' => $day,
                'items' => [],
            ];
            $start->addDay();
        }

        return $month;

    }

    public function getAgenda($slug){
        $agenda = \App\Agenda::where('slug', $slug)->first();
        abort_unless($agenda != null, 404);
        $_agenda = [
            'text' => $agenda->title,
            'description' => $agenda->description,
            'video' => $agenda->video == 1 ? true : false,
            'videoURL' => $agenda->videoURL,
            'videoBackgroundImage' => $agenda->videoBackgroundImage,
            'redirectTo' => url('/agenda/'.$agenda->slug),
            'videoBackgroundImageURL' => $agenda->videoBackgroundImageURL,
        ];
        //return $_agenda;
        return view('calendarAgenda', ['agenda' => $_agenda]);
    }


    public function getWeek($date){
        // INPUT : 'YYYY-MM-DD' : String
        $week = $this->_makeWeek($date);
        for($i = 0; $i < count($week); $i += 1){
            $_day = $week[$i];
            $day = \App\Day::where('date', $_day['full_date'])->first();
            if ($day != null){
                foreach($day->Agenda as $agenda){
                    $week[$i]['items'][] = [
                        'text' => $agenda->title,
                        'description' => $agenda->description,
                        'video' => $agenda->video == 1 ? true : false,
                        'videoURL' => $agenda->videoURL,
                        'videoBackgroundImage' => $agenda->videoBackgroundImage,
                        'redirectTo' => url('/agenda/'.$agenda->slug),
                        'videoBackgroundImageURL' => $agenda->videoBackgroundImageURL,
                    ];
                }
            }
        }
        return $week;
    }

    public function getMonth($date){
        // INPUT : 'YYYY-MM' : String
        $month = $this->_makeMonth($date);
        for($i = 0; $i < count($month); $i += 1){
            $_day = $month[$i];
            $day = \App\Day::where('date', $_day['full_date'])->first();
            if ($day != null){
                foreach($day->Agenda as $agenda){
                    $month[$i]['items'][] = [
                        'text' => $agenda->title,
                        'description' => $agenda->description,
                        'video' => $agenda->video == 1 ? true : false,
                        'videoURL' => $agenda->videoURL,
                        'videoBackgroundImage' => $agenda->videoBackgroundImage,
                        'redirectTo' => '#',
                    ];
                }
            }
        }
        return $month;
    }
}
