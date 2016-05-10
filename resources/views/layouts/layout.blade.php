<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('meta-title', 'EID LIMS')</title>

    <!-- Bootstap -->

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.dataTables.css') }}" rel="stylesheet">    
    <link href="{{ asset('/css/jquery-ui.css')}}" rel="stylesheet" >

    <link href="{{ asset('/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/twitter-bootstrap-3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/eid.css') }}" rel="stylesheet">

    <script src="{{ asset('/js/general.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery-2.1.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery-ui.js')}}" type="text/javascript"></script>

    <script src="{{ asset('/js/plugins/bootstrap-select.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/twitter-bootstrap-3.3/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/plugins/bootstrap-form-buttonset.js') }}" type="text/javascript" ></script>


</head>

<body>
    @include('layouts/partials/navbar')
    <div class="container" style="padding-top: 1em;">
        @include('flash::message')
        @yield('content')
    </div>
    @yield('content2')
    
    {!! session("auth_msge") !!}
</body>

</html>