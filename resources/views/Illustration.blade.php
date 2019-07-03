@extends('Master.A')

@section('title',$item['title'])

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

    <div style="min-height:100vh" id="app" class="ill-editor-root">
        <illustration-editor
            redirect_to="{{ $item['redirectTo'] }}"
        ></illustration-editor>
    </div>

    <script src="/js/saveSvgAsPng.js"></script>

    @component('Master.footerSmall')
    @endcomponent()
@endsection()
