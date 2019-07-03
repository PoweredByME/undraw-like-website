<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | Admin | @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comfortaa:300,400,700">
    <link rel="stylesheet" href="/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body>

        <div class="d-flex justify-content-between p-3" id="admin-nav-div" style="background-color:#323232">
                <div id="admin-logo-div">
                    <h3 style="color:white">{{ config('app.name') }} | Admin Panel </h3>
                </div>
                <div id="admin-nav-control-div">
                    <a class="btn btn-primary" type="button"
                        href="{{ url('/admin/logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                    >LOG OUT</a>

                    <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>


            <section id="admin-hero-section" style="height:100vh;width:100%;">
                    <div class="row" style="margin:0px;padding:0px;">
                        <div class="col-2" style="padding:0px;">
                            <div id="admin-control-links-div" class="pt-4 pb-4" style="background-color:#323232;color:white;height:100vh;overflow-y:auto;">
                                @foreach($options as $option)
                                <a href="{{ $option['href'] }}" class="d-block p-2" style="text-align:left;color:white;">{{ $option['title'] }}</a>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-10" style="padding:0px;">
                            <div id="admin-content-display-div" style="height:100vh;width:100%;overflow-y:auto;background:white;">

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </section>




    <!-- Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="/js/app.js"></script>


</body>
</html>
