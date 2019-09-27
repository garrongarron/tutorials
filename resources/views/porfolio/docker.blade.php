@extends('general.layout')


@section('content')
<link href="css/docker.css" rel="stylesheet" type="text/css"/>
<div class="content">
    <h1>Docker</h1>

    <p>If you work as developer, you need also resolve the problem of install your environments yourself.</p>
    <p>Docker help you to deploy your environment and documented in a single plain text with no more than 10 code lines</p>
    
    <p>It is really simple, but there are some concepts you have to handle properly.</p>
    <p>If you need to run an application too, you will need an Operative system, and the application on it.</p>

    <div class="app-a">
        <span>Application</span>
        <div class="os">
            <span>Operative System</span>
        </div>
    </div>

    <p>The green square is the image1.</p>
    <p>The red square is the image2.</p>
    <p>As you can see, the image 2 contain the image 1.</p>

    <p>This is one of the main concept of docker.</p>
    <p>If you need to use the Application to provide a Service. Probably, that you need is the following image3.</p>

    <div class="service">
        <span>Service-Y</span>
        <div class="app-a">
            <span>Application-A</span>
            <div class="os">    
                <span>Operative System I</span>
            </div>
        </div>
    </div>

    <p>This is great! But, what about if in your own computer you have an Application-B?</p>
    <div class="app-b">
        <span>Application-B</span>
        <div class="os">
            <span>Operative System I</span>
        </div>
    </div>
    <p>We can use the same Operative System I!</p>

    <p>Awesome, until now we know what if we need a Service, probably we need the Application-A, and the Operative System I.</p>
    <p>If this is the case, if we need also the Application-B, we only need the Application-B because we already have the Operative System I.</p>
    <p>The Operative System I, is into the image of the Service, and into the Application-A.</p>

    <p>Suppose we do not need Application-A, but Custom-Application-A.</p>
    <p>In this case we still need Application-A to make some customization on it.</p>
    <p>So the image we need is the following.</p>

    <div class="service-x">
        <span>Service-X</span>
        <div class="c-app-a">
            <span>Custom-Application-A</span>
            <div class="app-a">
                    <span>Application-A</span>
                    <div class="os">
                        <span>Operative System I</span>
                    </div>
                </div>
        </div>
    </div>

    <p>Of course, the Service-Y and the Service-X are similar, but not the same.</p>
    <p>So, if we need to use the Service-Y or the Service-X we just need to build an image.</p>
    <div class="image">
        <span>Build Image</span>
        <div class="image1">
            <span>Service-Y</span>
        </div>
    </div>
    <div class="image">
        <span>Build Image</span>
        <div class="image2">
            <span>Service-X</span>
        </div>
    </div>

    <p>Once we get the images, we can <b>run</b> the containers.</p>

    <div class="image">
        <span>Run container</span>
        <div class="circle1">
            <span>Service-Y</span>
        </div>
    </div>
    <div class="image">
        <span>Run container</span>
        <div class="circle2">
            <span>Service-X</span>
        </div>
    </div>

    <p>And then we can <b>stop</b> the containers.</p>

    <div class="image">
        <span>Run container</span>
        <div class="image1-1">
            <span>Service-Y</span>
        </div>
    </div>
    <div class="image">
        <span>Run container</span>
        <div class="image2-1">
            <span>Service-X</span>
        </div>
    </div>

    <p>So, what happen if you want to delete an image?</p>
    <p>If his container running it is not possible delete it, unless you force to delete the image and all their containers.</p>

    <p>Suppose the size of Service-Y is 75MB. (25MG OP, 25MG APP, 25MG SRV-Y).</p>
    <p>Question 1. What is the size of Service-X?</p>
    <p>Other interesting question is Question 2. How much space we need for Service-X?</p>
    <p>Answer of question 1 is 1GB. (<b>25MG OS, 25MG APP</b>, 25MG APP-CST, 25MG SRV-X)</p>
    <p>Answer of question 2 is 50GB. (25MG APP-CST, 25MG SRV-X), Because your local environment already has <b>OS</b> and <b>APP</b>.</p>
    
    
    <p>As you can see, Docker use layers to build the images.</p>

    <p>Now, to finish, what about if I tell you that you can run both systems 
        executing only one command line using just one plain text of 10 lines of configuration?</p>
    <p>This is not only possible, but also desirable!!!</p>
    <p>Now is time to code. I would like to write some examples, but I have 
        not enough time and there are bunch of tutorials and blogs for it.</p>
    <p>So, see you next.</p>


    


</div>
@endsection
