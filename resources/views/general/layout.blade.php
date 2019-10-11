<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <!-- Keep this tag for relative url starting without /, for example  href="css/prism.css"-->

    @if (PEPE)
    <!-- <base href="https://federicozacayan.github.io/tutorial/"> -->
    <base href="/">
    @else
    <base href="/tutorial/">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Federico Zacayan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    @section('css')
        <link href="css/css.css" rel="stylesheet" type="text/css"/>
        <link href="css/prism.css" rel="stylesheet" type="text/css"/>
        <link href="css/sticky.css" rel="stylesheet" type="text/css"/>
        <style>
        p{
            margin: 16px auto;
        }</style>
    @show
</head>
<body>
    @section('sidebar')
        @include('general.sticky')
    @show

    <div class="container">
        @yield('content')
        
    </div>
    @section('footer')
    <div class="footer">
        <div class="about-me">
            <span>Located: Auckland</span>    
            <span>Visa Status: Work & Holiday</span>
            <span>Job Status: Available</span>
        </div>
        <div class="about-me">
            <span>Experience: 6+ years</span>
            <span>Email: federico.zacayan@gmail.com</span>
            <span>Why hire me? <a href="{{route('porfolio.whyhireme')}}"">it is answered here</a></span>
        </div>    
        <div>
            <script src='https://kit.fontawesome.com/a076d05399.js'></script>
            
            <div class="chat-container">
                <button  class="chat-buttom">Chat <i class='fas fa-comment'></i></button>
                <iframe style="display:none;" src="{{route('porfolio.chato')}}"></iframe>
                <span class="close-chat" style="display:none;">x</span>
            </div>
        </div>
    </div>
        <!--<div class="footer-mail">
            <b class="email">federico.zacayan@gmail.com</b>
            <i>0272605604</i>
        </div>-->

        
    @show

</body>
@section('js')
    <script type="text/javascript" src="js/prism.js"></script>
    <script type="text/javascript" src="js/sticky.js"></script>
    <script type="text/javascript" src="chat/js.js"></script>
@show
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
