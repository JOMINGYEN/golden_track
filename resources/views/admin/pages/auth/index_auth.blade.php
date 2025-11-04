<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SHOES ADMIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('public/backend/images\favicon.png')}}">
    <!-- App css -->
    <link href="{{asset('public/backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/backend/css/icons.min.css')}}"rel="stylesheet" type="text/css">
    <link href="{{asset('public/backend/css/app.min.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
@yield('content')
    <footer class="footer footer-alt">
        2020 - 2021 &copy; SHOES theme by <a href="" class="text-muted">Coderthemes</a>
    </footer>
    <script src="{{asset('public/backend/js/vendor.min.js')}}"></script>
	<script src="{{asset('public/backend/js/app.min.js')}}"></script>
</body>

</html>
