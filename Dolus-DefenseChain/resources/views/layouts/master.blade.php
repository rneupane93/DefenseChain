<!-- master.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('bower_components/admin-lte/dist/css/AdminLTE.min.css')}}" />
    <link rel="stylesheet" href="{{asset('bower_components/admin-lte/dist/css/skins/_all-skins.min.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('stylesheets')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('includes.header')
    @include('includes.sidebar')
    @yield('content')
</div>
{{--<script src="{{ asset('bower_components/admin-lte/dist/js/jquery/dist/jquery.min.js') }}"></script>--}}
<link href="http://code.jquery.com/jquery-1.11.1.js">
{{--<script src="{{ asset('bower_components/admin-lte/dist/js/bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">

{{--<script src="{{ asset('bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>--}}
@yield('scripts')
</body>

</html>