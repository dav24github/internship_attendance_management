<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Control de Asistencia</title>
    <!-- General CSS Files -->

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/app.min.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/bootstrap-social/bootstrap-social.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/style.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/bundles/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="{{asset('backEnd')}}/assets/css/custom.css">

    <link rel='shortcut icon' type='image/x-icon' href='{{asset('backEnd')}}/assets/img/favicon.ico' />
    <style>
        .hover a:hover{
            background: #83bceb !important;
            color: white !important;
        }
    </style>
</head>

<body>
{{--<div class="loader"></div>--}}
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <punto-control></punto-control>
            </section>

        </div>
    </div>
</div>
<!-- General JS Scripts -->

<script src="{{asset('js/app.js')}}"></script>
<script>
    let token = localStorage.getItem('token');
    if(token){
        $("topnav").css('display','');
        $("sidebar").css('display','')
    }

</script>

<script src="{{asset('backEnd')}}/assets/js/app.min.js"></script>
<script src="{{asset('backEnd')}}/assets/bundles/select2/dist/js/select2.full.min.js"></script>
<script src="{{asset('backEnd')}}/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
<!-- JS Libraies -->
<script src="{{asset('backEnd')}}/assets/bundles/apexcharts/apexcharts.min.js"></script>
<!-- Page Specific JS File -->
<script src="{{asset('backEnd')}}/assets/js/page/index.js"></script>
<!-- Template JS File -->
<script src="{{asset('backEnd')}}/assets/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="{{asset('backEnd')}}/assets/js/custom.js"></script>



</body>
</html>
