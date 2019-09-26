@extends('general.layout')
@section('sidebar')
    @include('general.header')
    <div id="navbar">
        <div class="container">
            <!-- <a href="javascript:void(0)">News</a>
            <a href="javascript:void(0)">Contact</a> -->
            <a href="https://github.com/federicozacayan/" target="_blank">GitHub</a>
        </div>
    </div>
@endsection

@section('content')

<style media="screen">
    .links{
        display: flex;
        justify-content: space-around;
        width: 50%;
        margin: auto;
        font-size: 28px;
    }
    .title{
        font-size: 28px;
    }
    #navbar{
        text-align: center;

    }
    #navbar a {
        float: none;
        display: inline-block;
    }

</style>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <p>Hi there! I am software developer and I was thinking how to
                show my skills in a wonderful way.</p>
            <p>If there is something that I know to do well that is building applications,
                that is why I developed this platform on github.</p>
            <p>I invested $0 on this but I applied all my background knowledge,
                like SSH key, Bash scripting, Docker, Git to offer you a great experience
                reading tutorials about RESTful API. This allowme increase my productivity a 50% in RESTful API's.</p>

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

            <p>Most of the projects are "Hello World" applications.
                They are web service consumers, and server API's.
                The best part is that they are documented and have a respective tutorial to implement step by step.
                They are titled in the same way.</p>
            <div class="center">
                <b>RESTful API web &lt;Server|Client&gt; Dockerized on &lt;Languague|Framework&gt;</b>
            </div>
            <p>By the way, this site is build on Laravel. It is exported as
                    static site with a library and automatically deploy in github page.</p>
            <p>This CD/CI is a clock system where every single element has a function of the remain pieces depend on.</p>
            <p>At the moment, I am hearing offers to work, so if you have interes in contract me do not hesitate to get in touch.</p>

            

        </div>
    </div>
@endsection
