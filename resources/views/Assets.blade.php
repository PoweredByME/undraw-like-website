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
                <p class="m-0 mt-4">Browse to find SVG images suitable for your design needs. Change the colors easily to show your brand identity.</p>

                <div class="d-flex align-items-center justify-content-center">
                    <div class="search-container all mt-5">
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
                <div class="p-2 mt-2">
                    <div class="d-flex align-items-center justify-content-center">
                            <button
                            role="btn"
                            v-for="cs in [
                                {
                                    id: 0,
                                    color: '#BD281E F99B1D, 000000, 6EB3E7, F3C0C0'
                                },
                                {
                                    id: 1,
                                    color: '#F99B1D'
                                },
                                {
                                    id: 2,
                                    color: '#000000'
                                },
                                {
                                    id: 3,
                                    color: '#6EB3E7'
                                },
                                {
                                    id: 4,
                                    color: '#F3C0C0'
                                }
                            ]"
                            :key="cs.id"
                            :class='["color-btn mx-1 p-0", "search-edit-color-btn-" + cs.id]'
                            @click="editColor(cs.color)"
                            title=' Select this to edit the referenced elements of the Images'
                        >
                            <i  :class='["fa fa-circle"]' :style="{'color' : cs.color}"></i>
                        </button>
                    <div>
                </div>

            </div>
        </section>
        <illustrations-component :search_in="search_in"></illustrations-component>
    </div>

    <script src="/js/saveSvgAsPng.js"></script>




    @component('Master.footerSmall')
    @endcomponent()
@endsection()
