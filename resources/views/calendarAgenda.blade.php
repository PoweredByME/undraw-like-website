@extends('Master.A')

@section('title', 'Event')

@section('content')
    <div id="app">
        <calendar-modal :show='true'>

            @if(!$agenda['video'])
            <div class="pb-3 d-flex align-items-center justify-content-center" style="height:100vh">
                <div style="
                    max-width:600px;
                    width:96vw;
                    max-height:70vh;
                    height:auto;
                    overflow-y:scroll !important;
                    background:white;
                    color: #343434;
                    border-radius:5px;
                " class="shadow p-5 pb-2">
                    <h1 class="text-center mb-4 calendar-text-dark">{{ $agenda['text'] }}</h1>
                    <p class="m-0 mb-3 text-justify calendar-text-dark">{{ $agenda['description'] }}</p>
                    <div class="mt-2 d-flex align-items-center flex-wrap justify-content-end">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $agenda["redirectTo"] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #3b5998 !important"><i class="fa fa-facebook mr-2"></i>Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ $agenda["redirectTo"] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #1DA1F2 !important"><i class="fa fa-twitter mr-2"></i>Twitter</a>
                        <a href="https://plus.google.com/share?url=={{ $agenda["redirectTo"] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #d34836 !important"><i class="fa fa-google-plus mr-2"></i>Google Plus</a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $agenda["redirectTo"] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #0077b5 !important"><i class="fa fa-linkedin mr-2"></i>Linked In</a>
                    </div>
                    <p class="mb-3"></p>
                </div>
            </div>
            @else
            <div class="row" style="height:100vh;overflow-y:scroll !important">

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" style="height:100vh;">
                    <div style="
                        height:100vh;
                        overflow-y:auto;
                        background:rgba(255,255,255,0.9);
                    " class="shadow calendar-text-dark pt-5 pl-5 pr-5 pb-3">
                        <div>
                            <h1 class="mb-4 text-dark text-center">{{ $agenda['text'] }}</h1>
                            <h4 class="m-0 calendar-text-dark text-justify">{{ $agenda['description'] }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8" style="
                    height:100vh;
                    overflow-y:auto;
                ">
                    <div class="p-5 mt-5">
                        <div class="d-flex align-items-center justify-content-center">
                            <iframe width="966" height="360" src="{{ $agenda['videoURL'] }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <h4 class="text-dark text-center mt-3" style="font-weight:600"><i class="fa fa-share-alt mr-2"></i>Share</h4>
                        <div class="mt-2 d-flex align-items-center flex-wrap justify-content-center">

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $agenda['redirectTo'] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #3b5998 !important"><i class="fa fa-facebook mr-2"></i>Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ $agenda['redirectTo'] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #1DA1F2 !important"><i class="fa fa-twitter mr-2"></i>Twitter</a>
                            <a href="https://plus.google.com/share?url=={{ $agenda['redirectTo'] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #d34836 !important"><i class="fa fa-google-plus mr-2"></i>Google Plus</a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $agenda['redirectTo'] }}" role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #0077b5 !important"><i class="fa fa-linkedin mr-2"></i>Linked In</a>
                        </div>
                        <div class=" mt-4 d-flex align-items-center justify-content-center">
                            <a href="{{ $agenda['videoBackgroundImageURL'] }}" target="_blank" class="btn btn-light text-dark" role="btn">
                                <i class="fa fa-image mr-2"></i> View Image Source
                            </a>
                        </div>
                    </div>

                </div>

            </div>
            @endif

            <template v-slot:close>
                <div style="position:absolute;top:0;right:0;font-size:28px;cursor:pointer" class="pt-4 pr-5 calendar-text-dark">
                    <a href="/calendar" style="text-decoration:none" class="calendar-text-dark"><i class="fa fa-home mr-2"></i></a>
                </div>
            </template>
        </calendar-modal>
    </div>
@endsection()
