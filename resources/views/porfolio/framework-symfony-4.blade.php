@extends('general.layout')


@section('content')
<input type="button" value="login" onclick="login()">
<input type="button" value="listening" onclick="listening()">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<link href="tts/css.css" rel="stylesheet" type="text/css"/>
<div class="content">

<div class="center"><img id="myImg" src="images/50.png" alt="If I was working" style="width:90%;max-width:300px"></div>

    <h1>Symfony 4</h1>
    
    <h2>Introduction</h2>
    <p>Symfony is a reusable set of standalone, decoupled and cohesive PHP components that solve common web development problems.</p>
    <p>Instead of using these low-level components, you can use the ready-to-be-used Symfony full-stack web framework, which is based on these components... or you can create your very own framework. This tutorial is about the latter.</p>

    <h3>Why would you Like to Create your Own Framework?</h3>
    <p>Why would you like to create your own framework in the first place? If you look around, everybody will tell you that it's a bad thing to reinvent the wheel and that you'd better choose an existing framework and forget about creating your own altogether. Most of the time, they are right but there are a few good reasons to start creating your own framework:</p>
    <ul>
        <li>To learn more about the low level architecture of modern web frameworks in general and about the Symfony full-stack framework internals in particular;</li>
        <li>To create a framework tailored to your very specific needs (just be sure first that your needs are really specific);</li>
        <li>To experiment creating a framework for fun (in a learn-and-throw-away approach);</li>
        <li>To refactor an old/existing application that needs a good dose of recent web development best practices;</li>
        <li>To prove the world that you can actually create a framework on your own (... but with little effort).</li>
    </ul>
    <p>This tutorial will gently guide you through the creation of a web framework, one step at a time. At each step, you will have a fully-working framework that you can use as is or as a start for your very own. It will start with a simple framework and more features will be added with time. Eventually, you will have a fully-featured full-stack web framework.</p>
    <p>And each step will be the occasion to learn more about some of the Symfony Components.</p>
    <p>Many modern web frameworks advertize themselves as being MVC frameworks. This tutorial won't talk about the MVC pattern, as the Symfony Components are able to create any type of frameworks, not just the ones that follow the MVC architecture. Anyway, if you have a look at the MVC semantics, this book is about how to create the Controller part of a framework. For the Model and the View, it really depends on your personal taste and you can use any existing third-party libraries (Doctrine, Propel or plain-old PDO for the Model; PHP or Twig for the View).</p>
    <p>When creating a framework, following the MVC pattern is not the right goal. The main goal should be the Separation of Concerns; this is probably the only design pattern that you should really care about. The fundamental principles of the Symfony Components are focused on the HTTP specification. As such, the framework that you are going to create should be more accurately labelled as a HTTP framework or Request/Response framework.</p>
    
    <h3>Before You Start</h3>
    <p>Reading about how to create a framework is not enough. You will have to follow along and actually type all the examples included in this tutorial. For that, you need a recent version of PHP (5.5.9 or later is good enough), a web server (like Apache, NGinx or PHP's built-in web server), a good knowledge of PHP and an understanding of Object Oriented programming.</p>
    <p>Ready to go? Read on!</p>


    <h3>Bootstrapping</h3>
    <p>Before you can even think of creating the first framework, you need to think about some conventions: where you will store the code, how you will name the classes, how you will reference external dependencies, etc.</p>
    <p>To store your new framework, create a directory somewhere on your machine:</p>

    <pre><code class="language-bash">mkdir framework
cd framework</code></pre>


    <h3>Dependency Management</h3>
    <p>To install the Symfony Components that you need for your framework, you are going to use Composer, a project dependency manager for PHP. If you don't have it yet, download and install Composer now.</p>
    
    <h3>Our Project</h3>
    <p>Instead of creating our framework from scratch, we are going to write the same "application" over and over again, adding one abstraction at a time. Let's start with the simplest web application we can think of in PHP:</p>
    <pre><code class="language-css">// framework/index.php
$name = $_GET['name'];

printf('Hello %s', $name);</code></pre>
    
    <p>You can use the Symfony Local Web Server to test this great application in a browser</p>
    <p>(http://localhost:8000/index.php?name=Fabien):</p>
    <pre><code class="language-bash">symfony server:start</code></pre>
    <p>In the next chapter, we are going to introduce the HttpFoundation Component and see what it brings us.</p>


    <h2>The HttpFoundation Component</h2>
    <p>Before diving into the framework creation process, let's first step back and let's take a look at why you would like to use a framework instead of keeping your plain-old PHP applications as is. Why using a framework is actually a good idea, even for the simplest snippet of code and why creating your framework on top of the Symfony components is better than creating a framework from scratch.</p>
    <p>Even if the "application" we wrote in the previous chapter was simple enough, it suffers from a few problems:</p>
    <pre><code class="language-php">// framework/index.php
$name = $_GET['name'];

printf('Hello %s', $name);</code></pre>

    <p>First, if the name query parameter is not defined in the URL query string, you will get a PHP warning; so let's fix it:</p>
    <pre><code class="language-php">// framework/index.php
$name = isset($_GET['name']) ? $_GET['name'] : 'World';

printf('Hello %s', $name);</code></pre>

    <p>Then, this application is not secure. Can you believe it? Even this simple snippet of PHP code is vulnerable to one of the most widespread Internet security issue, XSS (Cross-Site Scripting). Here is a more secure version:</p>
    <pre><code class="language-php">$name = isset($_GET['name']) ? $_GET['name'] : 'World';

header('Content-Type: text/html; charset=utf-8');

printf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));</code></pre>
    
    <p>As you can see for yourself, the simple code we had written first is not that simple anymore if we want to avoid PHP warnings/notices and make the code more secure.</p>
    <p>Beyond security, this code can be complex to test. Even if there is not much to test, it strikes me that writing unit tests for the simplest possible snippet of PHP code is not natural and feels ugly. Here is a tentative PHPUnit unit test for the above code:</p>
    <pre><code class="language-php">// framework/test.php
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testHello()
    {
        $_GET[&apos;name&apos;] = &apos;Fabien&apos;;

        ob_start();
        include &apos;index.php&apos;;
        $content = ob_get_clean();

        $this-&gt;assertEquals(&apos;Hello Fabien&apos;, $content);
    }
}</code></pre>
    <p>At this point, if you are not convinced that security and testing are indeed two very good reasons to stop writing code the old way and adopt a framework instead (whatever adopting a framework means in this context), you can stop reading this book now and go back to whatever code you were working on before.</p>

    <h2>Going OOP with the HttpFoundation Component</h2>
    <p>Writing web code is about interacting with HTTP. So, the fundamental principles of our framework should be around the HTTP specification.</p>
    <p>The HTTP specification describes how a client (a browser for instance) interacts with a server (our application via a web server). The dialog between the client and the server is specified by well-defined messages, requests and responses: the client sends a request to the server and based on this request, the server returns a response.</p>
    <p>In PHP, the request is represented by global variables ($_GET, $_POST, $_FILE, $_COOKIE, $_SESSION...) and the response is generated by functions (echo, header, setcookie, ...).</p>
    <p>The first step towards better code is probably to use an Object-Oriented approach; that's the main goal of the Symfony HttpFoundation component: replacing the default PHP global variables and functions by an Object-Oriented layer.</p>
    <p>To use this component, add it as a dependency of the project:</p>
    <pre><code class="language-bash">composer require symfony/http-foundation</code></pre>

    <p>Running this command will also automatically download the Symfony HttpFoundation component and install it under the vendor/ directory. A composer.json and a composer.lock file will be generated as well, containing the new requirement.</p>
    <p>Now, let's rewrite our application by using the Request and the Response classes:</p>
    <pre><code class="language-php">// framework/index.php
require_once __DIR__.&apos;/vendor/autoload.php&apos;;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$name = $request-&gt;get(&apos;name&apos;, &apos;World&apos;);

$response = new Response(sprintf(&apos;Hello %s&apos;, htmlspecialchars($name, ENT_QUOTES, &apos;UTF-8&apos;)));

$response-&gt;send();</code></pre>

    <p>The createFromGlobals() method creates a Request object based on the current PHP global variables.</p>
    <p>The send() method sends the Response object back to the client (it first outputs the HTTP headers followed by the content).</p>
    <p>The main difference with the previous code is that you have total control of the HTTP messages. You can create whatever request you want and you are in charge of sending the response whenever you see fit.</p>
    <p>With the Request class, you have all the request information at your fingertips thanks to a nice and simple API:</p>
    
    <pre><code class="language-php">// the URI being requested (e.g. /about) minus any query parameters
$request-&gt;getPathInfo();

// retrieve GET and POST variables respectively
$request-&gt;query-&gt;get(&apos;foo&apos;);
$request-&gt;request-&gt;get(&apos;bar&apos;, &apos;default value if bar does not exist&apos;);

// retrieve SERVER variables
$request-&gt;server-&gt;get(&apos;HTTP_HOST&apos;);

// retrieves an instance of UploadedFile identified by foo
$request-&gt;files-&gt;get(&apos;foo&apos;);

// retrieve a COOKIE value
$request-&gt;cookies-&gt;get(&apos;PHPSESSID&apos;);

// retrieve an HTTP request header, with normalized, lowercase keys
$request-&gt;headers-&gt;get(&apos;host&apos;);
$request-&gt;headers-&gt;get(&apos;content-type&apos;);

$request-&gt;getMethod();    // GET, POST, PUT, DELETE, HEAD
$request-&gt;getLanguages(); // an array of languages the client accepts</code></pre>
    
    <p>You can also simulate a request:</p>
    <pre><code class="language-php">$request = Request::create(&apos;/index.php?name=Fabien&apos;);</code></pre>
    <p>With the Response class, you can tweak the response:</p>
    <pre><code class="language-php">$response = new Response();

$response-&gt;setContent(&apos;Hello world!&apos;);
$response-&gt;setStatusCode(200);
$response-&gt;headers-&gt;set(&apos;Content-Type&apos;, &apos;text/html&apos;);

// configure the HTTP cache headers
$response-&gt;setMaxAge(10);</code></pre>

    <p>Last but not least, these classes, like every other class in the Symfony code, have been audited for security issues by an independent company. And being an Open-Source project also means that many other developers around the world have read the code and have already fixed potential security problems. When was the last time you ordered a professional security audit for your home-made framework?</p>
    <p>Even something as simple as getting the client IP address can be insecure:</p>
    <pre><code class="language-php">if ($myIp === $_SERVER[&apos;REMOTE_ADDR&apos;]) {
    // the client is a known one, so give it some more privilege
}</code></pre>
    
    <p>It works perfectly fine until you add a reverse proxy in front of the production servers; at this point, you will have to change your code to make it work on both your development machine (where you don't have a proxy) and your servers:</p>
    <pre><code class="language-php">if ($myIp === $_SERVER[&apos;HTTP_X_FORWARDED_FOR&apos;] || $myIp === $_SERVER[&apos;REMOTE_ADDR&apos;]) {
    // the client is a known one, so give it some more privilege
}</code></pre>


    <p>Using the Request::getClientIp() method would have given you the right behavior from day one (and it would have covered the case where you have chained proxies):</p>
    <pre><code class="language-php">$request = Request::createFromGlobals();

if ($myIp === $request-&gt;getClientIp()) {
    // the client is a known one, so give it some more privilege
}</code></pre>
    <p>And there is an added benefit: it is secure by default. What does it mean? The $_SERVER['HTTP_X_FORWARDED_FOR'] value cannot be trusted as it can be manipulated by the end user when there is no proxy. So, if you are using this code in production without a proxy, it becomes trivially easy to abuse your system. That's not the case with the getClientIp() method as you must explicitly trust your reverse proxies by calling setTrustedProxies():</p>
    <pre><code class="language-php">Request::setTrustedProxies([&apos;10.0.0.1&apos;]);

if ($myIp === $request-&gt;getClientIp()) {
    // the client is a known one, so give it some more privilege
}</code></pre>
    
    <p>So, the getClientIp() method works securely in all circumstances. You can use it in all your projects, whatever the configuration is, it will behave correctly and safely. That's one of the goals of using a framework. If you were to write a framework from scratch, you would have to think about all these cases by yourself. Why not using a technology that already works?</p>
    <p>Believe or not but we have our first framework. You can stop now if you want. Using just the Symfony HttpFoundation component already allows you to write better and more testable code. It also allows you to write code faster as many day-to-day problems have already been solved for you.</p>
    <p>As a matter of fact, projects like Drupal have adopted the HttpFoundation component; if it works for them, it will probably work for you. Don't reinvent the wheel.</p>
    <p>I've almost forgot to talk about one added benefit: using the HttpFoundation component is the start of better interoperability between all frameworks and applications using it (like Symfony, Drupal 8, phpBB 3, Laravel and ezPublish 5, and more).</p>
    
    
    <h2>The Front Controller</h2>
    
    <p>Up until now, our application is simplistic as there is only one page. To spice things up a little bit, let's go crazy and add another page that says goodbye:</p>
    <pre><code class="language-php">require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$response = new Response('Goodbye!');
$response-&gt;send();</code></pre>
    
    <p>As you can see for yourself, much of the code is exactly the same as the one we have written for the first page. Let's extract the common code that we can share between all our pages. Code sharing sounds like a good plan to create our first "real" framework!</p>
    <p>The PHP way of doing the refactoring would probably be the creation of an include file:</p>
    <pre><code class="language-php">// framework/init.php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();</code></pre>

    <p>Let's see it in action:</p>
    <pre><code class="language-php">// framework/index.php
require_once __DIR__.'/init.php';

$name = $request-&gt;get('name', 'World');

$response-&gt;setContent(sprintf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8')));
$response-&gt;send();</code></pre>

    <p>And for the "Goodbye" page:</p>
    <pre><code class="language-php">// framework/bye.php
require_once __DIR__.'/init.php';

$response-&gt;setContent('Goodbye!');
$response-&gt;send();</code></pre>


    <p>We have indeed moved most of the shared code into a central place, but it does not feel like a good abstraction, does it? We still have the send() method for all pages, our pages do not look like templates and we are still not able to test this code properly.</p>
    <p>Moreover, adding a new page means that we need to create a new PHP script, which name is exposed to the end user via the URL (http://127.0.0.1:4321/bye.php): there is a direct mapping between the PHP script name and the client URL. This is because the dispatching of the request is done by the web server directly. It might be a good idea to move this dispatching to our code for better flexibility. This can be achieved by routing all client requests to a single PHP script.</p>
    <p>Such a script might look like the following:</p>
    <pre><code class="language-php">// framework/front.php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = [
    '/hello' =&gt; __DIR__.'/hello.php',
    '/bye'   =&gt; __DIR__.'/bye.php',
];

$path = $request-&gt;getPathInfo();
if (isset($map[$path])) {
    require $map[$path];
} else {
    $response-&gt;setStatusCode(404);
    $response-&gt;setContent('Not Found');
}

$response-&gt;send();</code></pre>

    <p>And here is for instance the new hello.php script:</p>
    <pre><code class="language-php">// framework/hello.php
$name = $request-&gt;get('name', 'World');
$response-&gt;setContent(sprintf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8')));</code></pre>


    <p>In the front.php script, $map associates URL paths with their corresponding PHP script paths.</p>
    <p>As a bonus, if the client asks for a path that is not defined in the URL map, we return a custom 404 page; you are now in control of your website.</p>
    <p>To access a page, you must now use the front.php script:</p>

    <ul>
        <li><code>http://127.0.0.1:4321/front.php/hello?name=Fabien</code></li>
        <li><code>http://127.0.0.1:4321/front.php/bye</code></li>
    </ul>
    <p>/hello and /bye are the page paths.</p>
    <p>The trick is the usage of the Request::getPathInfo() method which returns the path of the Request by removing the front controller script name including its sub-directories (only if needed -- see above tip).</p>
    <p>Now that the web server always access the same script (front.php) for all pages, we can secure the code further by moving all other PHP files outside the web root directory:</p>
    <pre><code class="language-php">example.com
├── composer.json
├── composer.lock
├── src
│   └── pages
│       ├── hello.php
│       └── bye.php
├── vendor
│   └── autoload.php
└── web
    └── front.php</code></pre>


    <p>Now, configure your web server root directory to point to web/ and all other files won't be accessible from the client anymore.</p>
    <p>To test your changes in a browser (http://localhost:4321/hello?name=Fabien), run the Symfony Local Web Server:</p>
    <pre><code class="language-php">symfony server:start --port=4321 --passthru=front.php</code></pre>

    <p>The last thing that is repeated in each page is the call to setContent(). We can convert all pages to "templates" by just echoing the content and calling the setContent() directly from the front controller script:</p>
    <pre><code class="language-php">// example.com/web/front.php

// ...

$path = $request-&gt;getPathInfo();
if (isset($map[$path])) {
    ob_start();
    include $map[$path];
    $response-&gt;setContent(ob_get_clean());
} else {
    $response-&gt;setStatusCode(404);
    $response-&gt;setContent('Not Found');
}

// ...</code></pre>


    <p>And the hello.php script can now be converted to a template:</p>
    <pre><code class="language-php">&lt;!-- example.com/src/pages/hello.php --&gt;
&lt;?php $name = $request-&gt;get('name', 'World') ?&gt;

Hello &lt;?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?&gt;</code></pre>


    <p>We have the first version of our framework:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = [
    '/hello' =&gt; __DIR__.'/../src/pages/hello.php',
    '/bye'   =&gt; __DIR__.'/../src/pages/bye.php',
];

$path = $request-&gt;getPathInfo();
if (isset($map[$path])) {
    ob_start();
    include $map[$path];
    $response-&gt;setContent(ob_get_clean());
} else {
    $response-&gt;setStatusCode(404);
    $response-&gt;setContent('Not Found');
}

$response-&gt;send();</code></pre>


    <p>Adding a new page is a two-step process: add an entry in the map and create a PHP template in src/pages/. From a template, get the Request data via the $request variable and tweak the Response headers via the $response variable.</p>


    <h2>The Routing Component</h2>
    <p>Before we start diving into the Routing component, let's refactor our current framework just a little to make templates even more readable:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$map = [
    '/hello' =&gt; 'hello',
    '/bye'   =&gt; 'bye',
];

$path = $request-&gt;getPathInfo();
if (isset($map[$path])) {
    ob_start();
    extract($request-&gt;query-&gt;all(), EXTR_SKIP);
    include sprintf(__DIR__.'/../src/pages/%s.php', $map[$path]);
    $response = new Response(ob_get_clean());
} else {
    $response = new Response('Not Found', 404);
}

$response-&gt;send();</code></pre>


    <p>As we now extract the request query parameters, simplify the hello.php template as follows:</p>
    <pre><code class="language-php">&lt;!-- example.com/src/pages/hello.php --&gt;
Hello &lt;?= htmlspecialchars(isset($name) ? $name : 'World', ENT_QUOTES, 'UTF-8') ?&gt;</code></pre>
    <p>Now, we are in good shape to add new features.</p>
    <p>One very important aspect of any website is the form of its URLs. Thanks to the URL map, we have decoupled the URL from the code that generates the associated response, but it is not yet flexible enough. For instance, we might want to support dynamic paths to allow embedding data directly into the URL (e.g. /hello/Fabien) instead of relying on a query string (e.g. /hello?name=Fabien).</p>
    <p>To support this feature, add the Symfony Routing component as a dependency:</p>
    <pre><code class="language-bash"></code>composer require symfony/routing</pre>
    <p>Instead of an array for the URL map, the Routing component relies on a RouteCollection instance:</p>
    <pre><code class="language-php">use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();</code></pre>


    <p>Let's add a route that describes the /hello/SOMETHING URL and add another one for the simple /bye one:</p>
    <pre><code class="language-php">use Symfony\Component\Routing\Route;

$routes-&gt;add('hello', new Route('/hello/{name}', ['name' =&gt; 'World']));
$routes-&gt;add('bye', new Route('/bye'));</code></pre>

    <p>Each entry in the collection is defined by a name (hello) and a Route instance, which is defined by a route pattern (/hello/{name}) and an array of default values for route attributes (['name' => 'World']).</p>
    <p>Based on the information stored in the RouteCollection instance, a UrlMatcher instance can match URL paths:</p>
    <pre><code class="language-php">use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$context = new RequestContext();
$context-&gt;fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$attributes = $matcher-&gt;match($request-&gt;getPathInfo());</code></pre>


    <p>The match() method takes a request path and returns an array of attributes (notice that the matched route is automatically stored under the special _route attribute):</p>
    <pre><code class="language-php">$matcher-&gt;match('/bye');
/* Result:
[
    '_route' =&gt; 'bye',
];
*/

$matcher-&gt;match('/hello/Fabien');
/* Result:
[
    'name' =&gt; 'Fabien',
    '_route' =&gt; 'hello',
];
*/

$matcher-&gt;match('/hello');
/* Result:
[
    'name' =&gt; 'World',
    '_route' =&gt; 'hello',
];
*/</code></pre>
    <p>The URL matcher throws an exception when none of the routes match:</p>
    <pre><code class="language-php">$matcher-&gt;match('/not-found');

// throws a Symfony\Component\Routing\Exception\ResourceNotFoundException</code></pre>
    <p>With this knowledge in mind, let's write the new version of our framework:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$context-&gt;fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    extract($matcher-&gt;match($request-&gt;getPathInfo()), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response-&gt;send();</code></pre>
    
    
    
    
    
    
    
    <p>There are a few new things in the code:</p>
    <ul>
        <li>Route names are used for template names;</li>
        <li>500 errors are now managed correctly;</li>
        <li>Request attributes are extracted to keep our templates simple:</li>
    </ul>
    
    <pre><code class="language-php">// example.com/src/pages/hello.php
Hello &lt;?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?&gt;</code></pre>
    <ul>
        <li>Route configuration has been moved to its own file:</li>
    </ul>
    <pre><code class="language-php">// example.com/src/app.php
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes-&gt;add('hello', new Routing\Route('/hello/{name}', ['name' =&gt; 'World']));
$routes-&gt;add('bye', new Routing\Route('/bye'));

return $routes;</code></pre>
    
    <p>We now have a clear separation between the configuration (everything specific to our application in app.php) and the framework (the generic code that powers our application in front.php).</p>
    <p>With less than 30 lines of code, we have a new framework, more powerful and more flexible than the previous one. Enjoy!</p>
    <p>Using the Routing component has one big additional benefit: the ability to generate URLs based on Route definitions. When using both URL matching and URL generation in your code, changing the URL patterns should have no other impact. You can use the generator this way:</p>
    <pre><code class="language-php">use Symfony\Component\Routing;

$generator = new Routing\Generator\UrlGenerator($routes, $context);

echo $generator-&gt;generate('hello', ['name' =&gt; 'Fabien']);
// outputs /hello/Fabien</code></pre>


    <p>The code should be self-explanatory; and thanks to the context, you can even generate absolute URLs:</p>
    <pre><code class="language-php">use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

echo $generator-&gt;generate(
    'hello',
    ['name' =&gt; 'Fabien'],
    UrlGeneratorInterface::ABSOLUTE_URL
);
// outputs something like http://example.com/somewhere/hello/Fabien</code></pre>
    <p>Concerned about performance? Based on your route definitions, create a highly optimized URL matcher class that can replace the default UrlMatcher:</p>
    
    <pre><code class="language-php">&lt;?php
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;

// $compiledRoutes is a plain PHP array that describes all routes in a performant data format
// you can (and should) cache it, typically by exporting it to a PHP file
$compiledRoutes = (new CompiledUrlMatcherDumper($routes))-&gt;getCompiledRoutes();

$matcher = new CompiledUrlMatcher($compiledRoutes, $context);</code></pre>
   

    <h2>Templating</h2>
    <p>The astute reader has noticed that our framework hardcodes the way specific "code" (the templates) is run. For simple pages like the ones we have created so far, that's not a problem, but if you want to add more logic, you would be forced to put the logic into the template itself, which is probably not a good idea, especially if you still have the separation of concerns principle in mind.</p>
    <p>Let's separate the template code from the logic by adding a new layer: the controller: The controller's mission is to generate a Response based on the information conveyed by the client's Request.</p>
    <p>Change the template rendering part of the framework to read as follows:</p>
    <pre><code class="language-php">// example.com/web/front.php

// ...
try {
    $request-&gt;attributes-&gt;add($matcher-&gt;match($request-&gt;getPathInfo()));
    $response = call_user_func('render_template', $request);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}</code></pre>


    <p>As the rendering is now done by an external function (render_template() here), we need to pass to it the attributes extracted from the URL. We could have passed them as an additional argument to render_template(), but instead, let's use another feature of the Request class called attributes: Request attributes is a way to attach additional information about the Request that is not directly related to the HTTP Request data.</p>
    <p>You can now create the render_template() function, a generic controller that renders a template when there is no specific logic. To keep the same template as before, request attributes are extracted before the template is rendered:</p>
    <pre><code class="language-php">function render_template($request)
{
    extract($request-&gt;attributes-&gt;all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}</code></pre>

    <p>As render_template is used as an argument to the PHP call_user_func() function, we can replace it with any valid PHP callbacks. This allows us to use a function, an anonymous function or a method of a class as a controller... your choice.</p>
    <p>As a convention, for each route, the associated controller is configured via the _controller route attribute:</p>
    <pre><code class="language-php">$routes-&gt;add('hello', new Routing\Route('/hello/{name}', [
    'name' =&gt; 'World',
    '_controller' =&gt; 'render_template',
]));

try {
    $request-&gt;attributes-&gt;add($matcher-&gt;match($request-&gt;getPathInfo()));
    $response = call_user_func($request-&gt;attributes-&gt;get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}</code></pre>


    <p>A route can now be associated with any controller and within a controller, you can still use the render_template() to render a template:</p>
    <pre><code class="language-php">$routes-&gt;add('hello', new Routing\Route('/hello/{name}', [
    'name' =&gt; 'World',
    '_controller' =&gt; function ($request) {
        return render_template($request);
    }
]));</code></pre>


    <p>This is rather flexible as you can change the Response object afterwards and you can even pass additional arguments to the template:</p>
    <pre><code class="language-php">$routes-&gt;add('hello', new Routing\Route('/hello/{name}', [
    'name' =&gt; 'World',
    '_controller' =&gt; function ($request) {
        // $foo will be available in the template
        $request-&gt;attributes-&gt;set('foo', 'bar');

        $response = render_template($request);

        // change some header
        $response-&gt;headers-&gt;set('Content-Type', 'text/plain');

        return $response;
    }
]));</code></pre>

    <p>Here is the updated and improved version of our framework:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

function render_template($request)
{
    extract($request-&gt;attributes-&gt;all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$context-&gt;fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    $request-&gt;attributes-&gt;add($matcher-&gt;match($request-&gt;getPathInfo()));
    $response = call_user_func($request-&gt;attributes-&gt;get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response-&gt;send();</code></pre>
    <p>To celebrate the birth of our new framework, let's create a brand new application that needs some simple logic. Our application has one page that says whether a given year is a leap year or not. When calling /is_leap_year, you get the answer for the current year, but you can also specify a year like in /is_leap_year/2009. Being generic, the framework does not need to be modified in any way, create a new app.php file:</p>
    <pre><code class="language-php">// example.com/src/app.php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

function is_leap_year($year = null) {
    if (null === $year) {
        $year = date('Y');
    }

    return 0 === $year % 400 || (0 === $year % 4 &amp;&amp; 0 !== $year % 100);
}

$routes = new Routing\RouteCollection();
$routes-&gt;add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' =&gt; null,
    '_controller' =&gt; function ($request) {
        if (is_leap_year($request-&gt;attributes-&gt;get('year'))) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
]));

return $routes;</code></pre>

    <p>The is_leap_year() function returns true when the given year is a leap year, false otherwise. If the year is null, the current year is tested. The controller does little: it gets the year from the request attributes, pass it to the is_leap_year() function, and according to the return value it creates a new Response object.</p>
    <p>As always, you can decide to stop here and use the framework as is; it's probably all you need to create simple websites like those fancy one-page websites and hopefully a few others.</p>



    <h2>The HttpKernel Component: the Controller Resolver</h2>
    <p>You might think that our framework is already pretty solid and you are probably right. But let's see how we can improve it nonetheless.</p>
    <p>Right now, all our examples use procedural code, but remember that controllers can be any valid PHP callbacks. Let's convert our controller to a proper class:</p>
    <pre><code class="language-php">class LeapYearController
{
    public function index($request)
    {
        if (is_leap_year($request-&amp;gt;attributes-&amp;gt;get('year'))) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}</code></pre>

    <p>Update the route definition accordingly:</p>
    <pre><code class="language-php">$routes-&gt;add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' =&gt; null,
    '_controller' =&gt; [new LeapYearController(), 'index'],
]));</code></pre>
    <p>The move is pretty straightforward and makes a lot of sense as soon as you create more pages but you might have noticed a non-desirable side effect... The LeapYearController class is always instantiated, even if the requested URL does not match the leap_year route. This is bad for one main reason: performance wise, all controllers for all routes must now be instantiated for every request. It would be better if controllers were lazy-loaded so that only the controller associated with the matched route is instantiated.</p>
    <p>To solve this issue, and a bunch more, let's install and use the HttpKernel component:</p>
    <pre><code class="language-php">composer require symfony/http-kernel</code></pre>


    <p>The HttpKernel component has many interesting features, but the ones we need right now are the controller resolver and argument resolver. A controller resolver knows how to determine the controller to execute and the argument resolver determines the arguments to pass to it, based on a Request object. All controller resolvers implement the following interface:</p>
    <pre><code class="language-php">namespace Symfony\Component\HttpKernel\Controller;

// ...
interface ControllerResolverInterface
{
    public function getController(Request $request);
}</code></pre>

    <p>The getController() method relies on the same convention as the one we have defined earlier: the _controller request attribute must contain the controller associated with the Request. Besides the built-in PHP callbacks, getController() also supports strings composed of a class name followed by two colons and a method name as a valid callback, like 'class::method':</p>
    <pre><code class="language-php">$routes-&gt;add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' =&gt; null,
    '_controller' =&gt; 'LeapYearController::index',
]));</code></pre>

    <p>To make this code work, modify the framework code to use the controller resolver from HttpKernel:</p>
    <pre><code class="language-php">use Symfony\Component\HttpKernel;

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$controller = $controllerResolver-&gt;getController($request);
$arguments = $argumentResolver-&gt;getArguments($request, $controller);

$response = call_user_func_array($controller, $arguments);</code></pre>


    <p>Now, let's see how the controller arguments are guessed. getArguments() introspects the controller signature to determine which arguments to pass to it by using the native PHP reflection. This method is defined in the following interface:</p>
    <pre><code class="language-php">namespace Symfony\Component\HttpKernel\Controller;

// ...
interface ArgumentResolverInterface
{
    public function getArguments(Request $request, $controller);
}</code></pre>

    <p>The index() method needs the Request object as an argument. getArguments() knows when to inject it properly if it is type-hinted correctly:</p>
    <pre><code class="language-php">public function index(Request $request)

// won't work
public function index($request)</code></pre>


    <p>More interesting, getArguments() is also able to inject any Request attribute; if the argument has the same name as the corresponding attribute:</p>
    <pre><code class="language-php">public function index($year)</code></pre>
    
    <p>You can also inject the Request and some attributes at the same time (as the matching is done on the argument name or a type hint, the arguments order does not matter):</p>
    <pre><code class="language-php">public function index(Request $request, $year)

public function index($year, Request $request)</code></pre>

    <p>Finally, you can also define default values for any argument that matches an optional attribute of the Request:</p>
    <pre><code class="language-php">public function index($year = 2012)</code></pre>

    <p>Let's inject the $year request attribute for our controller:</p>
    <pre><code class="language-php">class LeapYearController
{
    public function index($year)
    {
        if (is_leap_year($year)) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}</code></pre>
    <p>The resolvers also take care of validating the controller callable and its arguments. In case of a problem, it throws an exception with a nice message explaining the problem (the controller class does not exist, the method is not defined, an argument has no matching attribute, ...).</p>
    <p>Let's conclude with the new version of our framework:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

function render_template(Request $request)
{
    extract($request-&gt;attributes-&gt;all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$context-&gt;fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

try {
    $request-&gt;attributes-&gt;add($matcher-&gt;match($request-&gt;getPathInfo()));

    $controller = $controllerResolver-&gt;getController($request);
    $arguments = $argumentResolver-&gt;getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response-&gt;send();</code></pre>
    <p>Think about it once more: our framework is more robust and more flexible than ever and it still has less than 50 lines of code.</p>
  
    <h2>The Separation of Concerns</h2>
    <p>One down-side of our framework right now is that we need to copy and paste the code in front.php each time we create a new website. 60 lines of code is not that much, but it would be nice if we could wrap this code into a proper class. It would bring us better reusability and easier testing to name just a few benefits.</p>
    <p>If you have a closer look at the code, front.php has one input, the Request and one output, the Response. Our framework class will follow this simple principle: the logic is about creating the Response associated with a Request.</p>
    <p>Let's create our very own namespace for our framework: Simplex. Move the request handling logic into its own Simplex\Framework class:</p>
    <pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class Framework
{
    protected $matcher;
    protected $controllerResolver;
    protected $argumentResolver;

    public function __construct(UrlMatcher $matcher, ControllerResolver $controllerResolver, ArgumentResolver $argumentResolver)
    {
        $this-&gt;matcher = $matcher;
        $this-&gt;controllerResolver = $controllerResolver;
        $this-&gt;argumentResolver = $argumentResolver;
    }

    public function handle(Request $request)
    {
        $this-&gt;matcher-&gt;getContext()-&gt;fromRequest($request);

        try {
            $request-&gt;attributes-&gt;add($this-&gt;matcher-&gt;match($request-&gt;getPathInfo()));

            $controller = $this-&gt;controllerResolver-&gt;getController($request);
            $arguments = $this-&gt;argumentResolver-&gt;getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            return new Response('Not Found', 404);
        } catch (\Exception $exception) {
            return new Response('An error occurred', 500);
        }
    }
}</code></pre>


    <p>And update example.com/web/front.php accordingly:</p>
    <pre><code class="language-php">// example.com/web/front.php

// ...
$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Simplex\Framework($matcher, $controllerResolver, $argumentResolver);
$response = $framework-&gt;handle($request);

$response-&gt;send();</code></pre>

    <p>To wrap up the refactoring, let's move everything but routes definition from example.com/src/app.php into yet another namespace: Calendar.</p>
    <p>For the classes defined under the Simplex and Calendar namespaces to be autoloaded, update the composer.json file:</p>
    <pre><code class="language-json">{
    &quot;...&quot;: &quot;...&quot;,
    &quot;autoload&quot;: {
        &quot;psr-4&quot;: { &quot;&quot;: &quot;src/&quot; }
    }
}</code></pre>


    <p>Move the controller to Calendar\Controller\LeapYearController:</p>
    <pre><code class="language-php">// example.com/src/Calendar/Controller/LeapYearController.php
namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request, $year)
    {
        $leapYear = new LeapYear();
        if ($leapYear-&gt;isLeapYear($year)) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}</code></pre>

    <p>And move the is_leap_year() function to its own class too:</p>
    <pre><code class="language-php">// example.com/src/Calendar/Model/LeapYear.php
namespace Calendar\Model;

class LeapYear
{
    public function isLeapYear($year = null)
    {
        if (null === $year) {
            $year = date('Y');
        }

        return 0 == $year % 400 || (0 == $year % 4 &amp;&amp; 0 != $year % 100);
    }
}</code></pre>


    <p>Don't forget to update the example.com/src/app.php file accordingly:</p>
    <pre><code class="language-php">$routes-&gt;add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' =&gt; null,
    '_controller' =&gt; 'Calendar\Controller\LeapYearController::index',
]));</code></pre>

    <p>To sum up, here is the new file layout:</p>
    <pre><code class="language-php">example.com
├── composer.json
├── composer.lock
├── src
│   ├── app.php
│   └── Simplex
│       └── Framework.php
│   └── Calendar
│       └── Controller
│       │   └── LeapYearController.php
│       └── Model
│           └── LeapYear.php
├── vendor
│   └── autoload.php
└── web
    └── front.php</code></pre>
    <p>That's it! Our application has now four different layers and each of them has a well-defined goal:</p>
    <ul>
        <li>web/front.php: The front controller; the only exposed PHP code that makes the interface with the client (it gets the Request and sends the Response) and provides the boiler-plate code to initialize the framework and our application;</li>
        <li>src/Simplex: The reusable framework code that abstracts the handling of incoming Requests (by the way, it makes your controllers/templates better testable -- more about that later on);</li>
        <li>src/Calendar: Our application specific code (the controllers and the model);</li>
    </ul>

    <h2>Unit Testing</h2>
    <p>You might have noticed some subtle but nonetheless important bugs in the framework we built in the previous chapter. When creating a framework, you must be sure that it behaves as advertised. If not, all the applications based on it will exhibit the same bugs. The good news is that whenever you fix a bug, you are fixing a bunch of applications too.</p>
    <p>Today's mission is to write unit tests for the framework we have created by using PHPUnit. Create a PHPUnit configuration file in example.com/phpunit.xml.dist:</p>
    <pre><code class="language-xml">&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;phpunit
    xmlns:xsi=&quot;http://www.w3.org/2001/XMLSchema-instance&quot;
    xsi:noNamespaceSchemaLocation=&quot;https://schema.phpunit.de/5.1/phpunit.xsd&quot;
    backupGlobals=&quot;false&quot;
    colors=&quot;true&quot;
    bootstrap=&quot;vendor/autoload.php&quot;&gt;
    &lt;testsuites&gt;
        &lt;testsuite name=&quot;Test Suite&quot;&gt;
            &lt;directory&gt;./tests&lt;/directory&gt;
        &lt;/testsuite&gt;
    &lt;/testsuites&gt;

    &lt;filter&gt;
        &lt;whitelist processUncoveredFilesFromWhitelist=&quot;true&quot;&gt;
            &lt;directory suffix=&quot;.php&quot;&gt;./src&lt;/directory&gt;
        &lt;/whitelist&gt;
    &lt;/filter&gt;
&lt;/phpunit&gt;</code></pre>
    
    <p>This configuration defines sensible defaults for most PHPUnit settings; more interesting, the autoloader is used to bootstrap the tests, and tests will be stored under the example.com/tests/ directory.</p>
    <p>Now, let's write a test for "not found" resources. To avoid the creation of all dependencies when writing tests and to really just unit-test what we want, we are going to use test doubles. Test doubles are easier to create when we rely on interfaces instead of concrete classes. Fortunately, Symfony provides such interfaces for core objects like the URL matcher and the controller resolver. Modify the framework to make use of them:</p>
    <pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

// ...

use Calendar\Controller\LeapYearController;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework
{
    protected $matcher;
    protected $controllerResolver;
    protected $argumentResolver;

    public function __construct(UrlMatcherInterface $matcher, ControllerResolverInterface $resolver, ArgumentResolverInterface $argumentResolver)
    {
        $this-&gt;matcher = $matcher;
        $this-&gt;controllerResolver = $resolver;
        $this-&gt;argumentResolver = $argumentResolver;
    }

    // ...
}</code></pre>


    <p>We are now ready to write our first test:</p>
    <pre><code class="language-php">// example.com/tests/Simplex/Tests/FrameworkTest.php
namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    private function getFrameworkForException($exception)
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(Routing\RequestContext::class)))
        ;
        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}</code></pre>

    <p>This test simulates a request that does not match any route. As such, the match() method returns a ResourceNotFoundException exception and we are testing that our framework converts this exception to a 404 response.</p>
    <p>Execute this test by running phpunit in the example.com directory:</p>
    <pre><code class="language-bash">./vendor/bin/phpunit</code></pre>
    
    <p>After the test ran, you should see a green bar. If not, you have a bug either in the test or in the framework code!</p>
    <p>Adding a unit test for any exception thrown in a controller:</p>
    <pre><code class="language-php">public function testErrorHandling()
{
    $framework = $this-&gt;getFrameworkForException(new \RuntimeException());

    $response = $framework-&gt;handle(new Request());

    $this-&gt;assertEquals(500, $response-&gt;getStatusCode());
}</code></pre>


    <p>Last, but not the least, let's write a test for when we actually have a proper Response:</p>
    <pre><code class="language-php">use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
// ...

public function testControllerResponse()
{
    $matcher = $this-&gt;createMock(Routing\Matcher\UrlMatcherInterface::class);
    // use getMock() on PHPUnit 5.3 or below
    // $matcher = $this-&gt;getMock(Routing\Matcher\UrlMatcherInterface::class);

    $matcher
        -&gt;expects($this-&gt;once())
        -&gt;method('match')
        -&gt;will($this-&gt;returnValue([
            '_route' =&gt; 'is_leap_year/{year}',
            'year' =&gt; '2000',
            '_controller' =&gt; [new LeapYearController(), 'index']
        ]))
    ;
    $matcher
        -&gt;expects($this-&gt;once())
        -&gt;method('getContext')
        -&gt;will($this-&gt;returnValue($this-&gt;createMock(Routing\RequestContext::class)))
    ;
    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

    $response = $framework-&gt;handle(new Request());

    $this-&gt;assertEquals(200, $response-&gt;getStatusCode());
    $this-&gt;assertContains('Hello Fabien', $response-&gt;getContent());
}</code></pre>


    <p>In this test, we simulate a route that matches and returns a simple controller. We check that the response status is 200 and that its content is the one we have set in the controller.</p>
    <p>To check that we have covered all possible use cases, run the PHPUnit test coverage feature (you need to enable XDebug first):</p>
    <pre><code class="language-bash">./vendor/bin/phpunit --coverage-html=cov/</code></pre>
    <p>After the test ran, you should see a green bar. If not, you have a bug either in the test or in the framework code!</p>
    <p>Adding a unit test for any exception thrown in a controller:</p>
    <pre><code class="language-php">public function testErrorHandling()
{
    $framework = $this-&gt;getFrameworkForException(new \RuntimeException());

    $response = $framework-&gt;handle(new Request());

    $this-&gt;assertEquals(500, $response-&gt;getStatusCode());
}</code></pre>

    <p>Last, but not the least, let's write a test for when we actually have a proper Response:</p>
    <pre><code class="language-php">use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
// ...

public function testControllerResponse()
{
    $matcher = $this-&gt;createMock(Routing\Matcher\UrlMatcherInterface::class);
    // use getMock() on PHPUnit 5.3 or below
    // $matcher = $this-&gt;getMock(Routing\Matcher\UrlMatcherInterface::class);

    $matcher
        -&gt;expects($this-&gt;once())
        -&gt;method('match')
        -&gt;will($this-&gt;returnValue([
            '_route' =&gt; 'is_leap_year/{year}',
            'year' =&gt; '2000',
            '_controller' =&gt; [new LeapYearController(), 'index']
        ]))
    ;
    $matcher
        -&gt;expects($this-&gt;once())
        -&gt;method('getContext')
        -&gt;will($this-&gt;returnValue($this-&gt;createMock(Routing\RequestContext::class)))
    ;
    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

    $response = $framework-&gt;handle(new Request());

    $this-&gt;assertEquals(200, $response-&gt;getStatusCode());
    $this-&gt;assertContains('Hello Fabien', $response-&gt;getContent());
}</code></pre>


    <p>In this test, we simulate a route that matches and returns a simple controller. We check that the response status is 200 and that its content is the one we have set in the controller.</p>
    <p>To check that we have covered all possible use cases, run the PHPUnit test coverage feature (you need to enable XDebug first):</p>
    <pre><code class="language-bash"> ./vendor/bin/phpunit --coverage-html=cov/</code></pre>
    <p>Open example.com/cov/src/Simplex/Framework.php.html in a browser and check that all the lines for the Framework class are green (it means that they have been visited when the tests were executed).</p>
    <p>Alternatively you can output the result directly to the console:</p>
    <pre><code class="language-bash">./vendor/bin/phpunit --coverage-text</code></pre>

    <p>Thanks to the clean object-oriented code that we have written so far, we have been able to write unit-tests to cover all possible use cases of our framework; test doubles ensured that we were actually testing our code and not Symfony code.</p>
    <p>Now that we are confident (again) about the code we have written, we can safely think about the next batch of features we want to add to our framework.</p>
    
    <h2>The EventDispatcher Component</h2>
    
    <p>Our framework is still missing a major characteristic of any good framework: extensibility. Being extensible means that the developer should be able to hook into the framework life cycle to modify the way the request is handled.</p>
    <p>What kind of hooks are we talking about? Authentication or caching for instance. To be flexible, hooks must be plug-and-play; the ones you "register" for an application are different from the next one depending on your specific needs. Many software have a similar concept like Drupal or Wordpress. In some languages, there is even a standard like WSGI in Python or Rack in Ruby.</p>
    <p>As there is no standard for PHP, we are going to use a well-known design pattern, the Mediator, to allow any kind of behaviors to be attached to our framework; the Symfony EventDispatcher Component implements a lightweight version of this pattern:</p>
    <pre><code class="language-bash">composer require symfony/event-dispatcher</code></pre>
    <p>How does it work? The dispatcher, the central object of the event dispatcher system, notifies listeners of an event dispatched to it. Put another way: your code dispatches an event to the dispatcher, the dispatcher notifies all registered listeners for the event, and each listener do whatever it wants with the event.</p>
    <p>As an example, let's create a listener that transparently adds the Google Analytics code to all responses.</p>
    <p>To make it work, the framework must dispatch an event just before returning the Response instance:</p>

    <pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework
{
    private $dispatcher;
    private $matcher;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this-&gt;dispatcher = $dispatcher;
        $this-&gt;matcher = $matcher;
        $this-&gt;controllerResolver = $controllerResolver;
        $this-&gt;argumentResolver = $argumentResolver;
    }

    public function handle(Request $request)
    {
        $this-&gt;matcher-&gt;getContext()-&gt;fromRequest($request);

        try {
            $request-&gt;attributes-&gt;add($this-&gt;matcher-&gt;match($request-&gt;getPathInfo()));

            $controller = $this-&gt;controllerResolver-&gt;getController($request);
            $arguments = $this-&gt;argumentResolver-&gt;getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $exception) {
            $response = new Response('An error occurred', 500);
        }

        // dispatch a response event
        $this-&gt;dispatcher-&gt;dispatch(new ResponseEvent($response, $request), 'response');

        return $response;
    }
}</code></pre>

    <p>Each time the framework handles a Request, a ResponseEvent event is now dispatched:</p>
    <pre><code class="language-php">// example.com/src/Simplex/ResponseEvent.php
namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{
    private $request;
    private $response;

    public function __construct(Response $response, Request $request)
    {
        $this-&gt;response = $response;
        $this-&gt;request = $request;
    }

    public function getResponse()
    {
        return $this-&gt;response;
    }

    public function getRequest()
    {
        return $this-&gt;request;
    }
}</code></pre>


    <p>The last step is the creation of the dispatcher in the front controller and the registration of a listener for the response event:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

// ...

use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();
$dispatcher-&gt;addListener('response', function (Simplex\ResponseEvent $event) {
    $response = $event-&gt;getResponse();

    if ($response-&gt;isRedirection()
        || ($response-&gt;headers-&gt;has('Content-Type') &amp;&amp; false === strpos($response-&gt;headers-&gt;get('Content-Type'), 'html'))
        || 'html' !== $event-&gt;getRequest()-&gt;getRequestFormat()
    ) {
        return;
    }

    $response-&gt;setContent($response-&gt;getContent().'GA CODE');
});

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework-&gt;handle($request);

$response-&gt;send();</code></pre>
    <pAs you can see, addListener() associates a valid PHP callback to a named event (response); the event name must be the same as the one used in the dispatch() call.</p>
    <p>In the listener, we add the Google Analytics code only if the response is not a redirection, if the requested format is HTML and if the response content type is HTML (these conditions demonstrate the ease of manipulating the Request and Response data from your code).</p>
    <p>So far so good, but let's add another listener on the same event. Let's say that we want to set the Content-Length of the Response if it is not already set:</p>
    <pre><code class="language-php">$dispatcher-&gt;addListener('response', function (Simplex\ResponseEvent $event) {
    $response = $event-&gt;getResponse();
    $headers = $response-&gt;headers;

    if (!$headers-&gt;has('Content-Length') &amp;&amp; !$headers-&gt;has('Transfer-Encoding')) {
        $headers-&gt;set('Content-Length', strlen($response-&gt;getContent()));
    }
});</code></pre>
    <p>Depending on whether you have added this piece of code before the previous listener registration or after it, you will have the wrong or the right value for the Content-Length header. Sometimes, the order of the listeners matter but by default, all listeners are registered with the same priority, 0. To tell the dispatcher to run a listener early, change the priority to a positive number; negative numbers can be used for low priority listeners. Here, we want the Content-Length listener to be executed last, so change the priority to -255:</p>
    <pre><code class="language-php">$dispatcher-&gt;addListener('response', function (Simplex\ResponseEvent $event) {
    $response = $event-&gt;getResponse();
    $headers = $response-&gt;headers;

    if (!$headers-&gt;has('Content-Length') &amp;&amp; !$headers-&gt;has('Transfer-Encoding')) {
        $headers-&gt;set('Content-Length', strlen($response-&gt;getContent()));
    }
}, -255);</code></pre>


    <p>Let's refactor the code a bit by moving the Google listener to its own class:</p>
    <pre><code class="language-php">// example.com/src/Simplex/GoogleListener.php
namespace Simplex;

class GoogleListener
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event-&gt;getResponse();

        if ($response-&gt;isRedirection()
            || ($response-&gt;headers-&gt;has('Content-Type') &amp;&amp; false === strpos($response-&gt;headers-&gt;get('Content-Type'), 'html'))
            || 'html' !== $event-&gt;getRequest()-&gt;getRequestFormat()
        ) {
            return;
        }

        $response-&gt;setContent($response-&gt;getContent().'GA CODE');
    }
}</code></pre>
    <p>And do the same with the other listener:</p>
    <pre><code class="language-php">// example.com/src/Simplex/ContentLengthListener.php
namespace Simplex;

class ContentLengthListener
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event-&gt;getResponse();
        $headers = $response-&gt;headers;

        if (!$headers-&gt;has('Content-Length') &amp;&amp; !$headers-&gt;has('Transfer-Encoding')) {
            $headers-&gt;set('Content-Length', strlen($response-&gt;getContent()));
        }
    }
}</code></pre>
    <p>Our front controller should now look like the following:</p>
    <pre><code class="language-php">$dispatcher = new EventDispatcher();
$dispatcher-&gt;addListener('response', [new Simplex\ContentLengthListener(), 'onResponse'], -255);
$dispatcher-&gt;addListener('response', [new Simplex\GoogleListener(), 'onResponse']);</code></pre>


    <p>Even if the code is now nicely wrapped in classes, there is still a slight issue: the knowledge of the priorities is "hardcoded" in the front controller, instead of being in the listeners themselves. For each application, you have to remember to set the appropriate priorities. Moreover, the listener method names are also exposed here, which means that refactoring our listeners would mean changing all the applications that rely on those listeners. The solution to this dilemma is to use subscribers instead of listeners:</p>
    <pre><code class="language-php">$dispatcher = new EventDispatcher();
$dispatcher-&gt;addSubscriber(new Simplex\ContentLengthListener());
$dispatcher-&gt;addSubscriber(new Simplex\GoogleListener());</code></pre>
    <p>A subscriber knows about all the events it is interested in and pass this information to the dispatcher via the getSubscribedEvents() method. Have a look at the new version of the GoogleListener:</p>
    <pre><code class="language-php">// example.com/src/Simplex/GoogleListener.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
    // ...

    public static function getSubscribedEvents()
    {
        return ['response' =&gt; 'onResponse'];
    }
}</code></pre>



    <p>And here is the new version of ContentLengthListener:</p>
    <pre><code class="language-php">// example.com/src/Simplex/ContentLengthListener.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface
{
    // ...

    public static function getSubscribedEvents()
    {
        return ['response' => ['onResponse', -255]];
    }
}</code></pre>


    <p>To make your framework truly flexible, don't hesitate to add more events; and to make it more awesome out of the box, add more listeners. Again, this book is not about creating a generic framework, but one that is tailored to your needs. Stop whenever you see fit, and further evolve the code from there.</p>


    <h2>The HttpKernel Component: HttpKernelInterface</h2>
    <p>In the conclusion of the second chapter of this book, I've talked about one great benefit of using the Symfony components: the interoperability between all frameworks and applications using them. Let's do a big step towards this goal by making our framework implement HttpKernelInterface:</p>
    <pre><code class="language-php">namespace Symfony\Component\HttpKernel;

// ...
interface HttpKernelInterface
{
    /**
     * @return Response A Response instance
     */
    public function handle(
        Request $request,
        $type = self::MASTER_REQUEST,
        $catch = true
    );
}</code></pre>

    <p>HttpKernelInterface is probably the most important piece of code in the HttpKernel component, no kidding. Frameworks and applications that implement this interface are fully interoperable. Moreover, a lot of great features will come with it for free.</p>
    <p>Update your framework so that it implements this interface:</p>
    <pre><code class="language-php">// example.com/src/Framework.php

// ...
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Framework implements HttpKernelInterface
{
    // ...

    public function handle(
        Request $request,
        $type = HttpKernelInterface::MASTER_REQUEST,
        $catch = true
    ) {
        // ...
    }
}</code></pre>


    <p>Even if this change looks not too complex, it brings us a lot! Let's talk about one of the most impressive one: transparent HTTP caching support.</p>
    <p>The HttpCache class implements a fully-featured reverse proxy, written in PHP; it implements HttpKernelInterface and wraps another HttpKernelInterface instance:</p>
    <pre><code class="language-php">// example.com/web/front.php

// ...

$framework = new Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache')
);

$framework-&gt;handle($request)-&gt;send();</code></pre>


    <p>That's all it takes to add HTTP caching support to our framework. Isn't it amazing?</p>
    <p>Configuring the cache needs to be done via HTTP cache headers. For instance, to cache a response for 10 seconds, use the Response::setTtl() method:</p>
    <pre><code class="language-php">// example.com/src/Calendar/Controller/LeapYearController.php

// ...
public function index(Request $request, $year)
{
    $leapYear = new LeapYear();
    if ($leapYear-&gt;isLeapYear($year)) {
        $response = new Response('Yep, this is a leap year!');
    } else {
        $response = new Response('Nope, this is not a leap year.');
    }

    $response-&gt;setTtl(10);

    return $response;
}</code></pre>



    <p>To validate that it works correctly, add a random number to the response content and check that the number only changes every 10 seconds:</p>
    <pre><code class="language-php">$response = new Response('Yep, this is a leap year! '.rand());</code></pre>


    <p>Using HTTP cache headers to manage your application cache is very powerful and allows you to tune finely your caching strategy as you can use both the expiration and the validation models of the HTTP specification. If you are not comfortable with these concepts, read the HTTP caching chapter of the Symfony documentation.</p>
    <p>The Response class contains methods that let you configure the HTTP cache. One of the most powerful is setCache() as it abstracts the most frequently used caching strategies into a single array:</p>
    <pre><code class="language-php">$date = date_create_from_format('Y-m-d H:i:s', '2005-10-15 10:00:00');

$response-&gt;setCache([
    'public'        =&gt; true,
    'etag'          =&gt; 'abcde',
    'last_modified' =&gt; $date,
    'max_age'       =&gt; 10,
    's_maxage'      =&gt; 10,
]);

// it is equivalent to the following code
$response-&gt;setPublic();
$response-&gt;setEtag('abcde');
$response-&gt;setLastModified($date);
$response-&gt;setMaxAge(10);
$response-&gt;setSharedMaxAge(10);</code></pre>

    <p>When using the validation model, the isNotModified() method allows you to cut on the response time by short-circuiting the response generation as early as possible:</p>
    <pre><code class="language-php">$response-&gt;setETag('whatever_you_compute_as_an_etag');

if ($response-&gt;isNotModified($request)) {
    return $response;
}

$response-&gt;setContent('The computed content of the response');

return $response;</code></pre>
    <p>Using HTTP caching is great, but what if you cannot cache the whole page? What if you can cache everything but some sidebar that is more dynamic that the rest of the content? Edge Side Includes (ESI) to the rescue! Instead of generating the whole content in one go, ESI allows you to mark a region of a page as being the content of a sub-request call:</p>
    <pre><code class="language-html">This is the content of your page

Is 2012 a leap year? &lt;esi:include src=&quot;/leapyear/2012&quot;/&gt;

Some other content</code></pre>
    <p>For ESI tags to be supported by HttpCache, you need to pass it an instance of the ESI class. The ESI class automatically parses ESI tags and makes sub-requests to convert them to their proper content:</p>
    <pre><code class="language-php">$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache'),
    new HttpKernel\HttpCache\Esi()
);</code></pre>

    <p>When using complex HTTP caching strategies and/or many ESI include tags, it can be hard to understand why and when a resource should be cached or not. To ease debugging, you can enable the debug mode:</p>
    <pre><code class="language-php">$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache'),
    new HttpKernel\HttpCache\Esi(),
    ['debug' =&gt; true]
);</code></pre>


    <p>The debug mode adds a X-Symfony-Cache header to each response that describes what the cache layer did:</p>
    <pre><code class="language-php">X-Symfony-Cache:  GET /is_leap_year/2012: stale, invalid, store

X-Symfony-Cache:  GET /is_leap_year/2012: fresh</code></pre>


    <p>HttpCache has many features like support for the stale-while-revalidate and stale-if-error HTTP Cache-Control extensions as defined in RFC 5861.</p>
    <p>With the addition of a single interface, our framework can now benefit from the many features built into the HttpKernel component; HTTP caching being just one of them but an important one as it can make your applications fly!</p>
    
    
    <h2>The HttpKernel Component: The HttpKernel Class</h2>
    <p>If you were to use our framework right now, you would probably have to add support for custom error messages. We do have 404 and 500 error support but the responses are hardcoded in the framework itself. Making them customizable is straightforward though: dispatch a new event and listen to it. Doing it right means that the listener has to call a regular controller. But what if the error controller throws an exception? You will end up in an infinite loop. There should be an easier way, right?</p>
    <p>Enter the HttpKernel class. Instead of solving the same problem over and over again and instead of reinventing the wheel each time, the HttpKernel class is a generic, extensible and flexible implementation of HttpKernelInterface.</p>
    <p>This class is very similar to the framework class we have written so far: it dispatches events at some strategic points during the handling of the request, it uses a controller resolver to choose the controller to dispatch the request to, and as an added bonus, it takes care of edge cases and provides great feedback when a problem arises.</p>
    <p>Here is the new framework code:</p>
    <pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
}</code></pre>


    <p>And the new front controller:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));

$framework = new Simplex\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $framework-&gt;handle($request);
$response-&gt;send();</code></pre>


    <p>RouterListener is an implementation of the same logic we had in our framework: it matches the incoming request and populates the request attributes with route parameters.</p>
    <p>Our code is now much more concise and surprisingly more robust and more powerful than ever. For instance, use the built-in ExceptionListener to make your error management configurable:</p>
    <pre><code class="language-php">$errorHandler = function (Symfony\Component\Debug\Exception\FlattenException $exception) {
    $msg = 'Something went wrong! ('.$exception-&gt;getMessage().')';

    return new Response($msg, $exception-&gt;getStatusCode());
};
$dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\ExceptionListener($errorHandler));</code></pre>

    <p>ExceptionListener gives you a FlattenException instance instead of the thrown Exception or Error instance to ease exception manipulation and display. It can take any valid controller as an exception handler, so you can create an ErrorController class instead of using a Closure:</p>
    <pre><code class="language-php">$listener = new HttpKernel\EventListener\ExceptionListener(
    'Calendar\Controller\ErrorController::exception'
);
$dispatcher-&gt;addSubscriber($listener);</code></pre>


    <p>The error controller reads as follows:</p>
    <pre><code class="language-php">// example.com/src/Calendar/Controller/ErrorController.php
namespace Calendar\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function exception(FlattenException $exception)
    {
        $msg = 'Something went wrong! ('.$exception-&gt;getMessage().')';

        return new Response($msg, $exception-&gt;getStatusCode());
    }
}</code></pre>


    <p>Voilà! Clean and customizable error management without efforts. And if your ErrorController throws an exception, HttpKernel will handle it nicely.</p>
    <p>In chapter two, we talked about the Response::prepare() method, which ensures that a Response is compliant with the HTTP specification. It is probably a good idea to always call it just before sending the Response to the client; that's what the ResponseListener does:</p>
    <pre><code class="language-php">$dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));</code></pre>


    <p>If you want out of the box support for streamed responses, subscribe to StreamedResponseListener:</p>
    <pre><code class="language-php">$dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\StreamedResponseListener());</code></pre>

    <p>And in your controller, return a StreamedResponse instance instead of a Response instance.</p>
    <p>Now, let's create a listener, one that allows a controller to return a string instead of a full Response object:</p>
    <pre><code class="language-php">class LeapYearController
{
    public function index(Request $request, $year)
    {
        $leapYear = new LeapYear();
        if ($leapYear-&gt;isLeapYear($year)) {
            return 'Yep, this is a leap year! ';
        }

        return 'Nope, this is not a leap year.';
    }
}</code></pre>


    <p>To implement this feature, we are going to listen to the kernel.view event, which is triggered just after the controller has been called. Its goal is to convert the controller return value to a proper Response instance, but only if needed:</p>
    <pre><code class="language-php">// example.com/src/Simplex/StringResponseListener.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class StringResponseListener implements EventSubscriberInterface
{
    public function onView(ViewEvent $event)
    {
        $response = $event-&gt;getControllerResult();

        if (is_string($response)) {
            $event-&gt;setResponse(new Response($response));
        }
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.view' =&gt; 'onView'];
    }
}</code></pre>


    <p>The code is simple because the kernel.view event is only triggered when the controller return value is not a Response and because setting the response on the event stops the event propagation (our listener cannot interfere with other view listeners).</p>
    <p>Don't forget to register it in the front controller:</p>
    <pre><code class="language-php">$dispatcher-&gt;addSubscriber(new Simplex\StringResponseListener());</code></pre>
    <p>At this point, our whole framework code is as compact as possible and it is mainly composed of an assembly of existing libraries. Extending is a matter of registering event listeners/subscribers.</p>
    <p>Hopefully, you now have a better understanding of why the simple looking HttpKernelInterface is so powerful. Its default implementation, HttpKernel, gives you access to a lot of cool features, ready to be used out of the box, with no efforts. And because HttpKernel is actually the code that powers the Symfony framework, you have the best of both worlds: a custom framework, tailored to your needs, but based on a rock-solid and well maintained low-level architecture that has been proven to work for many websites; a code that has been audited for security issues and that has proven to scale well.</p>
    


    <h2>The DependencyInjection Component</h2>
    <p>In the previous chapter, we emptied the Simplex\Framework class by extending the HttpKernel class from the eponymous component. Seeing this empty class, you might be tempted to move some code from the front controller to it:</p>
<pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

class Framework extends HttpKernel\HttpKernel
{
    public function __construct($routes)
    {
        $context = new Routing\RequestContext();
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
        $requestStack = new RequestStack();

        $controllerResolver = new HttpKernel\Controller\ControllerResolver();
        $argumentResolver = new HttpKernel\Controller\ArgumentResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
        $dispatcher-&gt;addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));

        parent::__construct($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
    }
}</code></pre>
    <p>The front controller code would become more concise:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$framework = new Simplex\Framework($routes);

$framework-&gt;handle($request)-&gt;send();</code></pre>

    <p>Having a concise front controller allows you to have several front controllers for a single application. Why would it be useful? To allow having different configuration for the development environment and the production one for instance. In the development environment, you might want to have error reporting turned on and errors displayed in the browser to ease debugging:</p>
    <pre><code class="language-php">ini_set('display_errors', 1);
error_reporting(-1);</code></pre>


    <p>... but you certainly won't want that same configuration on the production environment. Having two different front controllers gives you the opportunity to have a slightly different configuration for each of them.</p>
    <p>So, moving code from the front controller to the framework class makes our framework more configurable, but at the same time, it introduces a lot of issues:</p>
    <ul>
        <li>We are not able to register custom listeners anymore as the dispatcher is not available outside the Framework class (a workaround could be the adding of a Framework::getEventDispatcher() method);</li>
        <li>We have lost the flexibility we had before; you cannot change the implementation of the UrlMatcher or of the ControllerResolver anymore;</li>
        <li>Related to the previous point, we cannot test our framework without much effort anymore as it's impossible to mock internal objects;</li>
        <li>We cannot change the charset passed to ResponseListener anymore (a workaround could be to pass it as a constructor argument).</li>
    </ul>
    <p>The previous code did not exhibit the same issues because we used dependency injection; all dependencies of our objects were injected into their constructors (for instance, the event dispatchers were injected into the framework so that we had total control of its creation and configuration).</p>
    <p>Does it mean that we have to make a choice between flexibility, customization, ease of testing and not to copy and paste the same code into each application front controller? As you might expect, there is a solution. We can solve all these issues and some more by using the Symfony dependency injection container:</p>
    <pre><code class="language-php">composer require symfony/dependency-injection</code></pre>

    <p>Create a new file to host the dependency injection container configuration:</p>

    <pre><code class="language-php">// example.com/src/container.php
use Simplex\Framework;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

$containerBuilder = new DependencyInjection\ContainerBuilder();
$containerBuilder-&gt;register('context', Routing\RequestContext::class);
$containerBuilder-&gt;register('matcher', Routing\Matcher\UrlMatcher::class)
    -&gt;setArguments([$routes, new Reference('context')])
;
$containerBuilder-&gt;register('request_stack', HttpFoundation\RequestStack::class);
$containerBuilder-&gt;register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
$containerBuilder-&gt;register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$containerBuilder-&gt;register('listener.router', HttpKernel\EventListener\RouterListener::class)
    -&gt;setArguments([new Reference('matcher'), new Reference('request_stack')])
;
$containerBuilder-&gt;register('listener.response', HttpKernel\EventListener\ResponseListener::class)
    -&gt;setArguments(['UTF-8'])
;
$containerBuilder-&gt;register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
    -&gt;setArguments(['Calendar\Controller\ErrorController::exception'])
;
$containerBuilder-&gt;register('dispatcher', EventDispatcher\EventDispatcher::class)
    -&gt;addMethodCall('addSubscriber', [new Reference('listener.router')])
    -&gt;addMethodCall('addSubscriber', [new Reference('listener.response')])
    -&gt;addMethodCall('addSubscriber', [new Reference('listener.exception')])
;
$containerBuilder-&gt;register('framework', Framework::class)
    -&gt;setArguments([
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ])
;

return $containerBuilder;</code></pre>


    <p>The goal of this file is to configure your objects and their dependencies. Nothing is instantiated during this configuration step. This is purely a static description of the objects you need to manipulate and how to create them. Objects will be created on-demand when you access them from the container or when the container needs them to create other objects.</p>
    <p>For instance, to create the router listener, we tell Symfony that its class name is Symfony\Component\HttpKernel\EventListener\RouterListener and that its constructor takes a matcher object (new Reference('matcher')). As you can see, each object is referenced by a name, a string that uniquely identifies each object. The name allows us to get an object and to reference it in other object definitions.</p>
    <p>The front controller is now only about wiring everything together:</p>
    <pre><code class="language-php">// example.com/web/front.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$routes = include __DIR__.'/../src/app.php';
$container = include __DIR__.'/../src/container.php';

$request = Request::createFromGlobals();

$response = $container-&gt;get('framework')-&gt;handle($request);

$response-&gt;send();</code></pre>
    <p>As all the objects are now created in the dependency injection container, the framework code should be the previous simple version:</p>
    <pre><code class="language-php">// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
}</code></pre>


    <p>Now, here is how you can register a custom listener in the front controller:</p>
    <pre><code class="language-php">// ...
use Simplex\StringResponseListener;

$container-&gt;register('listener.string_response', StringResponseListener::class);
$container-&gt;getDefinition('dispatcher')
    -&gt;addMethodCall('addSubscriber', [new Reference('listener.string_response')])
;</code></pre>


    <p>Beside describing your objects, the dependency injection container can also be configured via parameters. Let's create one that defines if we are in debug mode or not:</p>
    <pre><code class="language-php">$container->setParameter('debug', true);

echo $container->getParameter('debug');</code></pre>

    <p>These parameters can be used when defining object definitions. Let's make the charset configurable:</p>
    <pre><code class="language-php">// ...
use Simplex\StringResponseListener;

$container-&gt;register('listener.string_response', StringResponseListener::class);
$container-&gt;getDefinition('dispatcher')
    -&gt;addMethodCall('addSubscriber', [new Reference('listener.string_response')])
;</code></pre>
    
    <p>After this change, you must set the charset before using the response listener object:</p>
    <pre><code class="language-php">$container-&gt;setParameter('charset', 'UTF-8');</code></pre>


    <p>Instead of relying on the convention that the routes are defined by the $routes variables, let's use a parameter again:</p>
    <pre><code class="language-php">// ...
$container-&gt;register('matcher', Routing\Matcher\UrlMatcher::class)
    -&gt;setArguments(['%routes%', new Reference('context')])
;</code></pre>


    <p>And the related change in the front controller:</p>
    <pre><code class="language-php">$container-&gt;setParameter('routes', include __DIR__.'/../src/app.php');</code></pre>

    <p>We have barely scratched the surface of what you can do with the container: from class names as parameters, to overriding existing object definitions, from shared service support to dumping a container to a plain PHP class, and much more. The Symfony dependency injection container is really powerful and is able to manage any kind of PHP class.</p>
    <p>Don't yell at me if you don't want to use a dependency injection container in your framework. If you don't like it, don't use it. It's your framework, not mine.</p>
    <p>This is (already) the last chapter of this book on creating a framework on top of the Symfony components. I'm aware that many topics have not been covered in great details, but hopefully it gives you enough information to get started on your own and to better understand how the Symfony framework works internally.</p>
    <p>Have fun!</p>
   
    
    
</div>
<script type="text/javascript" src="tts/js.js"></script>
@include('general.modal')
@endsection
