@extends('general.layout')


@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Porfolio
            </div>

            <div class="links">
                <a href="{{route('porfolio.react')}}">React</a>
                <a href="{{route('porfolio.angular')}}">Angular</a>
                <a href="{{route('porfolio.vue')}}">Vue</a>
            </div>
            <hr>
            <div class="links">
                <a href="{{route('porfolio.php')}}">PHP</a>
                <a href="{{route('porfolio.node')}}">Node</a>
                <a href="{{route('porfolio.python')}}">Python</a>
            </div>
            <hr>
            <div class="links">
                <a href="#">Android</a>
                <a href="#">Webpack</a>
            </div>
            <hr>
            <div class="links">
                <a href="#">Docker</a>
                <a href="#">Git</a>
                <a href="#">Selenium</a>
                <a href="#">TDD</a>
            </div>
            <hr>
            <div class="links">
                <a href="#">SOAP</a>
                <a href="#">RESTful</a>
                <a href="#">Jwt</a>
            </div>
        </div>
    </div>
@endsection
