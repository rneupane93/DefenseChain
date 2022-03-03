<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" >
{{--<title>@yield('title', 'Dolus')</title>--}}
{{--<div class="header">--}}
    {{--<div class="logo">--}}
        {{--<h1>Dolus Application</h1>--}}
{{--</div>--}}

{{--</div>--}}


<header class="main-header">
    <div class="logo">
        <span class="logo-lg"><b>Dolus</b></span>
    </div>
    <nav class="navbar navbar-static-top">

        <div class="navbar-custom-menu">
            {{--<ul class="nav navbar-nav">--}}
                {{--<div class="dropdown">--}}
                    {{--<button class="dropbtn"> {{ Auth::user()->username }}--}}
                        {{--<i class="fa fa-caret-down"></i>--}}
                    {{--</button>--}}
                    {{--<div class="dropdown-content">--}}
                        {{--<a href="{{ route('logout') }}"--}}
                           {{--onclick="event.preventDefault();--}}
                            {{--document.getElementById('logout-form').submit();">--}}
                            {{--Logout--}}
                        {{--</a>--}}

                        {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
                            {{--{{ csrf_field() }}--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</ul>--}}
        </div>
    </nav>
</header>