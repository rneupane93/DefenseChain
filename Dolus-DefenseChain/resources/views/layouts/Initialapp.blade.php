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
</div>
<div id="main" class="row">

    @yield('content')

</div>

<div class="footer">

    @include('includes.footer')

</div>




</body>
</html>

