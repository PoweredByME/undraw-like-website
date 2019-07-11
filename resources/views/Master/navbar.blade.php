<div style="position:fixed;width:100vw;height:50px;top:0px;background:white;z-index:50">
    <nav class="navbar navbar-light navbar-expand-md" id="navbar-id">
        <div class="container-fluid d-flex align-items-center justify-content-space">
            <div class="d-flex align-items-center">
                {!! $title !!}
            </div>
            @if($search)
            <div class="search-container">
                <div class="search-input-container d-flex align-items-center p-1 pl-2 pr-2">
                    <input v-model="search_in" type="text" placeholder="Search {{ $search_title }}" name="search" id="search-input" onchange=""/>
                    <button><i class="fa fa-search calendar-text-orange"></i></button>
                </div>
            </div>
            @endif
            @if($right_menu)
            <div class="d-flex align-items-center">
                <a class="m-1" href="{{ $home_link }}" style="color:#343434;text-decoration:none;"><i class="fa fa-home mr-2"></i>Home</a>
            </div>
            @endif
        </div>
    </nav>
    <div class="nav-bottom-bar"></div>
</div>
<div style="height:55px"></div>
