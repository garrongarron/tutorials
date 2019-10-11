@extends('general.layout')
@section('sidebar')
    @include('general.header')
    <div id="navbar">
        <div class="container">
            <a href="{{route('porfolio.home')}}">Home</a>
        </div>
    </div>
@endsection

@section('content')

<div class="flex-center position-ref full-height">
        <div class="content">
            <p>I have already applied for <a href="https://www.immigration.govt.nz/new-zealand-visas/apply-for-a-visa/about-visa/skilled-migrant-category-resident-visa" target="_blank">Skilled Migrant Category Resident Visa</a>.</p>
            <p>Immigration says.</p>
            <cite>We invite people who have the skills to contribute to New Zealand’s economic growth to apply for this visa. Before we can invite you to apply, we’ll first need you to send us an Expression of Interest (EOI) telling us about your employment in New Zealand, work experience, and qualifications. If your Expression of Interest is successful <b>we’ll offer you the opportunity to apply to live and work in New Zealand indefinitely</b>.</cite>
            <p>There is a points-based system with is a minimum amount of point to be selected.</p>
            <cite>Currently, we are only selecting EOIs with <b>160 points or above</b>.</cite>
            <p>I currently I have 125 point.</p>


            <div class="center"><img id="myImg" src="images/125.png" alt="125 Points" style="width:90%;max-width:300px"></div>





            <p>Immigration site also informs<a href="https://www.immigration.govt.nz/new-zealand-visas/apply-for-a-visa/tools-and-information/tools/points-indicator-smc-28aug">how many points I can claim</a>. And I can get 50 point getting a job.</p>

            <div class="center"><img id="myImg" src="images/50.png" alt="If I was working" style="width:90%;max-width:300px"></div>

            <p>Or getting a job offer too.</p>
            <div class="center"><img id="myImg" src="images/50.b.png" alt="If I get a Job offer" style="width:90%;max-width:300px"></div>

            <p>So, If I get a job, I can live and work in New Zealand indefinitely.</p>




        </div>
    </div>

@include('general.imgmodal')
@endsection
