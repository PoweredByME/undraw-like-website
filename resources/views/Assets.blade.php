@extends('Master.A')

@section('title', 'Illustrations')

@section('content')

    @component('Master.navbar', ['title' => "
                                        <span style='font-weight:600;letter-spacing:2px'>DESIGN.AI</span>
                                        <span> | </span>
                                        <span>Illustrations</span>
                                    ",
                                'search' => false,
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

    <div id="app" style="min-height:100vh">
        <illustrations-component></illustrations-component>
    </div>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
