@extends('Master.A')

@section('title', 'Calendar')

@section('content')

    @component('Master.navbar', ['title' => "
                                        <span style='font-weight:600;letter-spacing:2px'>DESIGN.AI</span>
                                        <span> | </span>
                                        <span>Calendar</span>
                                    ",
                                'search' => false,
                                'left_menu' => false,
                                'right_menu' => false])
    @endcomponent()

    <div id="app">
        <calendar-component></calendar-component>
    </div>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
