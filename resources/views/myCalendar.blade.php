@extends('Master.A')

@section('title', 'Calendar')

@section('content')



    <div id="app">
            @component('Master.navbar', ['title' => "
                    <img width='76' src='webAssets/images/logo.svg'>
                    <span class='pl-1 pr-1'> | </span>
                    <span>Calendar</span>
                ",
                'search' => true,
                'search_title' => 'Events',
                'left_menu' => false,
                'right_menu' => false])
            @endcomponent()
        <calendar-component :search_in = 'search_in'></calendar-component>
    </div>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
