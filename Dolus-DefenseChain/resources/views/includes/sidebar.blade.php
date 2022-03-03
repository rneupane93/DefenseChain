<html>
<head>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
    <link href="http://code.jquery.com/jquery-1.11.1.js">

<body>
<nav class="navbar navbar-default sidebar" role="navigation">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="home">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-home"></span></a></li>
                <li ><a href={{ route('pages.servers')}}>All Servers<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-laptop"></span></a></li>
                <li ><a href={{ route('pages.users')}}>All Connected Users<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-user"></span></a></li>
                <li ><a href={{route('pages.usermigrations')}}>User Migrations<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-users"></span></a></li>
                <li ><a href={{route('pages.qvms')}}>Quarantined VM<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-cloud"></span></a></li>
                <li ><a href={{route('pages.blacklistips')}}>BlackListed IPs<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-ban"></span></a></li>
                <li ><a href={{route('pages.attackhistory')}}>Attack History<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-history"></span></a></li>
                <li ><a href={{route('pages.suspicious')}}>Internal Suspicious Score<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-search-plus"></span></a></li>
                <li ><a href={{route('pages.switchdevices')}}>Switches and Devices<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-feed"></span></a></li>
                <li ><a href={{route('pages.policies')}}>Policies<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-file-text"></span></a></li>
                <li ><a href="{{ route('logout')}}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Logout<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-sign-out"></span></a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

            </ul>
        </div>
    </div>
</nav>

</body>
</html>
