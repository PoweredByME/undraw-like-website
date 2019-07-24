@extends('Master.A')

@section('title', 'Illustrations')

@section('content')

    <div id="app" style="min-height:100vh">
        @component('Master.navbar', ['title' => "
                                    <img width='76' src='/webAssets/images/logo.svg'>
                                    <span class='pl-1 pr-1'> | </span>
                                    <span>Illustrations</span>
                                ",
                            'search' => false,
                            'search_title' => 'Illustrations',
                            'left_menu' => false,
                            'right_menu' => false])
        @endcomponent()

        <section class=" ill-hero-section p-3 d-flex align-items-center justify-content-center" style="background:white !important">
                <div class="ihs-container mb-1 text-black text-center">
                    <h3 style="letter-spacing: 2px">
                        <i class="fa fa-image"></i>
                        ILLUSTRATIONS
                    </h3>
                <p class="m-0 mt-4">Browse to find the images that fit your needs and click to download. Take advantage of the on-the-fly color image generation to match your brand identity.</p>

            </div>
        </section>

        <div class="d-flex align-items-center justify-content-center">
            <div class="search-container all mt-5 mb-5">
                    <div class="search-input-container d-flex align-items-center p-1 pl-2 pr-2">
                        <input autocomplete="off" v-model="search_in" type="text" placeholder="Search Illustrations" name="search" id="search-input" onchange=""/>
                        <button><i class="fa fa-search calendar-text-orange"></i></button>
                    </div>
                    <div class="search-dropdown mt-1 py-3 shadow">
                        @foreach(\App\IllustrationCatagory::all() as $item)
                        <p class="py-1 px-3 text-wrap" @click="search_in=`{{ $item->name }}`">{{ $item->name }}</p>
                        @endforeach
                    </div>
            </div>
        </div>

        <illustrations-component :search_in="search_in"></illustrations-component>
    </div>

    <script src="/js/saveSvgAsPng.js"></script>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
