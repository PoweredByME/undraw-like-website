@extends('Master.A')

@section('title', 'Illustrations')

@section('content')

    <div id="app" style="min-height:100vh">
        @component('Master.navbar', ['title' => "
                                    <span style='font-weight:600;letter-spacing:2px'>DESIGN.AI</span>
                                    <span> | </span>
                                    <span>Illustrations</span>
                                ",
                            'search' => true,
                            'left_menu' => false,
                            'right_menu' => false])
        @endcomponent()

        <section class="shadow-sm ill-hero-section p-3 d-flex align-items-center justify-content-center">
                <div class="ihs-container mb-1 text-white text-center">
                    <h3 style="letter-spacing: 2px">
                        <i class="fa fa-image"></i>
                        ILLUSTRATIONS
                    </h3>
                <p class="m-0 mt-4">Browse to find the images that fit your needs and click to download. Take advantage of the on-the-fly color image generation to match your brand identity.</p>
            </div>
        </section>

        <div class="d-flex align-items-center justify-content-center">
            <div class="search-container mobile mt-5 mb-5">
                    <div class="search-input-container d-flex align-items-center p-1 pl-2 pr-2">
                        <input v-model="search_in" type="text" placeholder="Search Illustrations" name="search" id="search-input" onchange=""/>
                        <button><i class="fa fa-search calendar-text-orange"></i></button>
                    </div>
            </div>
        </div>

        <illustrations-component :search_in="search_in"></illustrations-component>
    </div>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
