<div style="position:fixed;width:100vw;height:50px;top:0px;background:white;z-index:50">
    <nav class="navbar navbar-light navbar-expand-md" id="navbar-id">
        <div class="container-fluid" style="margin-top:0px;"><a href="#" class="navbar-brand">
            {!! $title !!}
        </a><button data-toggle="collapse" data-target="#navcol-1" class="navbar-toggler"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon" style="width:20px;height:20px;"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav mr-auto">
                    <li role="presentation" class="nav-item"><a href="#" class="nav-link active">Home</a></li>
                </ul>
                @if($search)
                <form target="_self" class="form-inline mr-auto mx-auto pl-2 pr-2 mt-1" style="border:1px solid #dce8f2;border-radius:6px;">
                    <div class="form-group nav-search-field"><label for="search-field" class="nav-search-field"></label><input type="search" name="search" placeholder="Search" class=" search-field m-0 pb-0 pt-0 nav-search-field" id="search-field" style="  width:84%;" /><i class="fa fa-search p-2"></i></div>
                </form>
                @endif
                <ul class="nav navbar-nav ml-auto">
                    <li class="dropdown"><a data-toggle="dropdown" aria-expanded="false" href="#" class="dropdown-toggle nav-link dropdown-toggle dropdown-toggle-link">•••</a>
                        <div role="menu" class="dropdown-menu dropdown-menu-right"><a role="presentation" href="#" class="dropdown-item">First Item</a><a role="presentation" href="#" class="dropdown-item">Second Item</a><a role="presentation" href="#" class="dropdown-item">Third Item</a></div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="nav-bottom-bar"></div>
</div>
<div style="height:55px"></div>
