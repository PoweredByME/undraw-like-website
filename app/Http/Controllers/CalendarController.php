<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    function _makeDay($carbon){
        // Make a day in required format
        $_date = $carbon->year.'-'.$carbon->month.'-'.$carbon->day;
        $day = $carbon->englishDayOfWeek;
        return [
            'date' => $carbon->day,
            'month' => $carbon->month,
            'year' => $carbon->year,
            'english_month' => $carbon->englishMonth,
            'full_date' => $_date,
            'day' => $day,
            'items' => [],
        ];
    }

    function _makeAgenda($agenda){
        // Make agenda in required format
        return [
            'text' => $agenda->title,
            'description' => $agenda->description,
            'video' => $agenda->video == 1 ? true : false,
            'videoURL' => $agenda->videoURL,
            'videoBackgroundImage' => $agenda->videoBackgroundImage,
            'redirectTo' => url('/agenda/'.$agenda->slug),
            'videoBackgroundImageURL' => $agenda->videoBackgroundImageURL,
        ];
    }

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
            $week[] = $this->_makeDay($carbon);
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
            $month[] = $this->_makeDay($carbon);
            $start->addDay();
        }
        return $month;
    }

    public function getAgenda($slug){
        $agenda = \App\Agenda::where('slug', $slug)->first();
        abort_unless($agenda != null, 404);
        $_agenda = $this->_makeAgenda($agenda);
        return view('calendarAgenda', ['agenda' => $_agenda]);
    }

    public function getWeek($date){
        // INPUT : 'YYYY-MM-DD' : String
        // OUTPUT : JSON format of all the week according the the DB
        $week = $this->_makeWeek($date);
        for($i = 0; $i < count($week); $i += 1){
            $_day = $week[$i];
            // Get from the data base if a date exists (this means that there is somekind of agenda stored in the DB w.r.t. this day)
            $day = \App\Day::where('date', $_day['full_date'])->first();
            if ($day != null){
                foreach($day->Agenda as $agenda){
                    $week[$i]['items'][] = $this->_makeAgenda($agenda);
                }
            }
        }
        return $week;
    }

    public function getMonth($date){
        // INPUT : 'YYYY-MM' : String
        // OUTPUT : JSON format of all the month according the the DB
        $month = $this->_makeMonth($date);
        for($i = 0; $i < count($month); $i += 1){
            $_day = $month[$i];
            $day = \App\Day::where('date', $_day['full_date'])->first(); // Same as in $this->getWeek($date)
            if ($day != null){
                foreach($day->Agenda as $agenda){
                    $month[$i]['items'][] = $this->_makeAgenda($agenda);
                }
            }
        }
        return $month;
    }

    public function searchAgenda($search){
        $agendas = [];
        foreach(\App\agendaKeyword::where('name', 'Like', '%'.$search.'%')->get() as $item){
            if(!$item->Agenda) continue;
            $carbon = new Carbon($item->Agenda->Day->date);
            $agendas[] = [
                'agenda' => $this->_makeAgenda($item->Agenda),
                'date' => $carbon->day,
                'month' => $carbon->englishMonth,
                'day' => $carbon->englishDayOfWeek,
                'year' => $carbon->year,
            ];
        }
        return $agendas;
    }
}
