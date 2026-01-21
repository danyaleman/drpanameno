@yield('css')
<!-- App css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/toastr/build/toastr.min.css') }}" rel="stylesheet" type="text/css">

@yield('css-bottom')
