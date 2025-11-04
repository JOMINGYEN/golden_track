<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown-Time</title>
       <link rel="shortcut icon" type="image/x-icon" href="{{asset('public/frontend/img/favicon.png')}}">

        <!-- all css here -->
        <link rel="stylesheet" href="{{asset('public/frontend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('public/frontend/css/countdown.css')}}">

</head>

<body>
         <a type="button" class="btn btn-danger mt-5" href="{{ URL::to('/')}}" >GOLDEN TRACK</a>
    <h1>New Years</h1>

    <div class="countdown-container">
        <div class="countdown-el days-c">
            <p class="big-text" id="days">0</p>
            <span>days</span>
        </div>
        <div class="countdown-el hours-c">
            <p class="big-text" id="hours">0</p>
            <span>hours</span>
        </div>
        <div class="countdown-el mins-c">
            <p class="big-text" id="mins">0</p>
            <span>mins</span>
        </div>
        <div class="countdown-el seconds-c">
            <p class="big-text" id="seconds">0</p>
            <span>seconds</span>
        </div>
    </div>
</body>
 <script src="{{asset('public/frontend/js/countdown.js')}}"></script>
</html>