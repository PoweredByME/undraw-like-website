@extends('Master.A')

@section('title',$item['title'])

@section('content')
    @component('Master.navbar', ['title' => "
                                        <img width='76' src='/webAssets/images/logo.svg'>
                                        <span class='pl-1 pr-1'> | </span>
                                        <span>Illustrations</span>
                                    ",
                                'search' => false,
                                'left_menu' => false,
                                'right_menu' => true,
                                'home_link' => "/assets"])
    @endcomponent()

    <div style="min-height:100vh" id="app" class="ill-editor-root">
        <illustration-editor
            redirect_to="{{ $item['redirectTo'] }}"
        ></illustration-editor>
    </div>

    <script src="/js/saveSvgAsPng.js"></script>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
