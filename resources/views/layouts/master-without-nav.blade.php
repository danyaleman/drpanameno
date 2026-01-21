<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ AppSetting('title') }} - Hospital & Clinic Management Laravel System </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Hospital Management System" name="description" />
    <meta content="Doctorly" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/')."/". AppSetting('favicon') }}">
    @include('layouts.head')
</head>

@yield('body')

<div id="preloader">
    <div id="status">
        <div class="spinner-chase">
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
        </div>
    </div>
</div>

@yield('content')

@include('layouts.footer-script')

</body>

</html>
