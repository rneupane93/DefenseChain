<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Dolus" />

    @include('includes.head')
    @section('title', 'Dolus')


</head>
<body>
  <div class="header">

        @include('includes.header')
         @include('includes.sidebar')
       <div class="dropdown">
                <button class="dropbtn">{{ Auth::user()->username }}
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
        </div>

  </div>

  @yield('content')

    <div class="footer">

        @include('includes.footer')

    </div>




</body>
<script src="{{ asset('js/app.js') }}"></script>

</html>

