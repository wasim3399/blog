@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
<script src="{{asset('card/lib/jquery.min.js')}}"></script>
<script src="{{asset('card/lib/modernizr.js')}}"></script>
<script src="{{asset('card/lib/jquery.inputmask.bundle.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('card/lib/jquery.payfield.css')}}">
<script src="{{asset('card/lib/jquery.payfield.js')}}"></script>
<script>
    $(function() {
        $(".credit-card-input").payfield();
    });
</script>
<!--<!DOCTYPE html>
<html>
<head>
    <title>Payfield.js</title>
    <script src="{{asset('card/lib/modernizr.js')}}"></script>
    <script src="{{asset('card/lib/jquery.min.js')}}"></script>
    <script src="{{asset('card/lib/jquery.inputmask.bundle.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('card/lib/jquery.payfield.css')}}">
    <script src="{{asset('card/lib/jquery.payfield.js')}}"></script>
</head>
<body>
<input style="width: 33%" class="credit-card-input"/>
<script>
    $(function() {
        $(".credit-card-input").payfield();
    });
</script>
</body>
</html>-->

{{--
    <!DOCTYPE html>
<html>

<head>
    <title>Card &ndash; the better way to collect credit cards</title>
    <meta name="viewport" content="initial-scale=1">
    <!-- CSS is included through the card.js script -->
</head>

<body>
<style>
    .demo-container {
        width: 100%;
        max-width: 350px;
        margin: 50px auto;
    }

    form {
        margin: 30px;
    }

    input {
        width: 200px;
        margin: 10px auto;
        display: block;
    }
</style>
<div class="demo-container">
    <div class="card-wrapper"></div>

    <div class="form-container active">
        <form action="">
            <input placeholder="Card number" type="tel" name="number">
            <input placeholder="Full name" type="text" name="name">
            <input placeholder="MM/YY" type="tel" name="expiry">
            <input placeholder="CVC" type="number" name="cvc">
        </form>
    </div>
</div>

<script src="{{asset('card/card.js')}}"></script>
<script>
    var c = new Card({
        form: document.querySelector('form'),
        container: '.card-wrapper'
    });
</script>
</body>

</html>
--}}
