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
            <div class="d-flex align-items-center justify-content-center">
                <div class="search-container mobile mt-5 mb-5">
                        <div class="search-input-container d-flex align-items-center p-1 pl-2 pr-2">
                            <input v-model="search_in" type="text" placeholder="Search Events" name="search" id="search-input" onchange=""/>
                            <button><i class="fa fa-search calendar-text-orange"></i></button>
                        </div>
                </div>
            </div>
        <calendar-component :search_in = 'search_in'></calendar-component>
    </div>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
