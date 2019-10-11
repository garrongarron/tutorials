@extends('general.layout')


@section('content')
<div class="content">

    <h1>Symfony 4</h1>

    <p>Unfortunately, Symfony is a framework flexible and it allows you to extend its functionality.</p>
    <p>That is way we can create a RESFul API almost without writing code through CLI using API-PLATFORM.</p>
    <p>But, let's go to a completely different history.</p>
    <p>There was upon a time a group of people who used to live developing software WRITING code.</p>
    <p>And that history starts like this...</p>
    <p>Assuming you have an environment ready to deploy symfony 4, pointing your server at public/index.php, 
    in the root folder of the project run the following composer commands.</p>
    <pre><code class="language-bash">#to create a basic symfony 
composer create-project symfony/skeleton .
#open the borwser at http://localhost/ it has to show you the welcome page
#to check the commands available
bin/console
#to add more functionality in the CLI
composer require symfony/maker-bundle --dev
#now you see more commands available
#to check the commands available (again)
bin/console
#to (try) create an entity
bin/console make:entity 
#to install orm (required)
composer require orm 
#to (finally) create an entity
bin/console make:entity 
</code></pre>

    <p>Following the instructions you can create an entity named Product with `name` and `description`.</p>
    <p>At this point, two files were created in /src/Entity folder and in /src/Repository folder.</p>
    <p>Let's go to configure the database.</p>
    <p>In the .env  file just tweak the configuration you need.</p>
    <pre><code class="language-bash">DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_name</code></pre>

    <p>Probably you can have not the database fresh.</p>
    <p>Let's clean and generate the database and create the tables.</p>
    <pre><code class="language-bash">#to try to drop the database
bin/console doctrine:database:drop 
#to drop the database (again)
bin/console doctrine:database:drop --force
#to create the database
bin/console doctrine:database:create
#to create the table
bin/console doctrine:schema:create</code></pre>

    <p>Now you are able to create a controller</p>
    <pre><code class="language-html">bin/console make:controller --no-template</code></pre>

    <p>You can named it ProductApiController. We think it is a good practice isolate 
    the API controllers and URL from the rest of potential web project.</p>
    <p>If everythin was good you can test the application in:</p>
    <pre><code class="language-html">http://localhost/product/api</code></pre>
    <p>The resutl is:</p>
    <pre><code  class="language-html">{&quot;message&quot;:&quot;Welcome to your new controller!&quot;,&quot;path&quot;:&quot;src\/Controller\/ProductApiController.php&quot;}</code></pre>
    <p>We propose make some validations to the data to be triggered when we update or create a product.</p>
    <p>So, we need to install validator.</p>
    <pre><code class="language-html">composer require validator</code></pre>

    <p>Then we need to generate a folder named `validator` into the `config` fodler. (yes! it is weird. Why in `config` folder?)</p>
    <p>Then, write the following validations in /config/validator/validation.yaml file.</p>
    <pre><code class="language-html">#/config/validator/validation.yaml
App\Entity\Product:
    properties:
        name:
            - NotBlank: ~
        description:
            - NotBlank: ~
            - Email:
                message: The email "@{{ value }}" is not a valid email.</code></pre>

    <p>As we will receive json into the request body, we will need to serialize it.</p>
    <p>So, let't install serializer</p>
    <pre><code class="language-bash">#to install serializer
composer require serializer</code></pre>


    <p>As RESTFul API we need the same url to create, read, update and delete a resource.</p>
    <p>So, we need to install rest-bundle.</p>
    <pre><code class="language-bash">#to install resbundle 
composer require friendsofsymfony/rest-bundle</code></pre>
    <p>This bundle include no official bundles, install them anyway.</p>

    <p>Now, we can configure the routes mapping `product` with our controller and adding a prefix `api`:</p>
    <pre><code class="language-bash">#/config/routes.yaml
product:
    type      : rest
    resource  : App\Controller\ProductApiController
    prefix    : api</code></pre>

    <p>And we have to set some variables in /config/packages/fos_rest.yaml bundle.</p>
    <pre><code class="language-bash"># /config/packages/fos_rest.yaml
fos_rest:
    routing_loader:
        default_format: json
        include_format: false
    format_listener:
        rules:
            - { path: '^/api', priorities: ['json'], fallback_format: json}
            - { path: '^/', priorities: ['text/html', '*/*'], fallback_format: html, prefer_extension: true }
    
    body_converter:
        enabled: true
        validate: true
        validation_errors_argument: validationErrors

    exception:
        enabled: true
        exception_controller: 'fos_rest.exception.controller:showAction'
    
    view:
        view_response_listener: 'force'
        formats:
            json: true</code></pre>

    <p>If you test, at `http://localhost/api/products` you will see options-resolver is needed.</p>
    <pre><code  class="language-bash">&apos;body_converter.validate: true&apos; requires OptionsResolver component installation ( composer require symfony/options-resolver )</code></pre>
    <p>So, we have to install it.</p>
    <pre><code class="language-bash"># to install options-resolver
composer require symfony/options-resolver
# to install framework-extra-bundle (required)
composer require sensio/framework-extra-bundle
# to install twig (required)
composer require twig</code></pre>

    <p>Probably, you will find errors related with twig and your /config/bundle.yaml looks like this.</p>
    <pre><code class="language-javascript">FOS\RestBundle\FOSRestBundle::class =&gt; [&apos;all&apos; =&gt; true],
Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class =&gt; [&apos;all&apos; =&gt; true],
Symfony\Bundle\TwigBundle\TwigBundle::class =&gt; [&apos;all&apos; =&gt; true],</code></pre>

    <p>But they have to look like this, in diferent order, placing twig before it is needed.</p>
    <pre><code class="language-javascript">Symfony\Bundle\TwigBundle\TwigBundle::class =&gt; [&apos;all&apos; =&gt; true],
FOS\RestBundle\FOSRestBundle::class =&gt; [&apos;all&apos; =&gt; true],
Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class =&gt; [&apos;all&apos; =&gt; true],</code></pre>

    <p>Now, you can write php in /src/Controller/ProductApiController.php</p>
    <pre><code class="language-javascript">&lt;?php # /src/Controller/ProductApiController.php
namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductApiController extends AbstractFOSRestController
{
    
    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManagerInterface
    ){
            $this-&gt;productRepository = $productRepository;
            $this-&gt;entityManager = $entityManagerInterface;
    }
    
    //Get all
    public function productsAction(){
        $data = $this-&gt;productRepository-&gt;findAll();
        return $this-&gt;view($data, Response::HTTP_OK);
    }
    
    //Get by ID
    public function getProductsAction(Product $product){
        return $this-&gt;view($product, Response::HTTP_OK);
    }
    
    //Create
    /**
     * @ParamConverter(&quot;product&quot;, converter=&quot;fos_rest.request_body&quot;)
     */
    public function postProductsAction(
    	Product $product, 
    	ConstraintViolationListInterface $validationErrors
    ){
        
        if (count($validationErrors) &gt; 0) {
            return $this-&gt;view($validationErrors , Response::HTTP_BAD_REQUEST);
        }
        $this-&gt;entityManager-&gt;persist($product);
        $this-&gt;entityManager-&gt;flush();
        return $this-&gt;view($product , Response::HTTP_CREATED);
    }
    
    //Update some fields
    public function patchProductsAction(
    	Product $product, 
    	Request $request, 
    	ValidatorInterface $validator
    ){
        return $this-&gt;update($product, $request, $validator);
    }
    
    //Update all fields
    public function putProductsAction(
    	Product $product, 
    	Request $request, 
    	ValidatorInterface $validator
    ){
        return $this-&gt;update($product, $request, $validator);
    }
    
    //update
    private function update($product, $request, $validator){
        $json = $request-&gt;getContent();
        $newData = json_decode($json, true);
        
        foreach($newData as $propertyName =&gt; $value){
            $method = &apos;set&apos;.ucfirst($propertyName);
            if(method_exists($product,$method)){
                $product-&gt;$method($value);
            }
        }
        
        $validationErrors = $validator-&gt;validate($product);
        if (count($validationErrors) &gt; 0) {
            return $this-&gt;view($validationErrors , Response::HTTP_BAD_REQUEST);
        }
        
        $this-&gt;entityManager-&gt;persist($product);
        $this-&gt;entityManager-&gt;flush();
        return $this-&gt;view($product , Response::HTTP_OK);
    }
    
    //Delete
    public function deleteProductsAction(Product $product){
        $this-&gt;entityManager-&gt;remove($product);
        $this-&gt;entityManager-&gt;flush();
        return $this-&gt;view($product , Response::HTTP_OK);
    }
}</code></pre>

    <p>And that is all. The code is simple.</p>
    <p>The complex part could be the update when the entity is populated generating the setters dinamically.</p>
    <p>This was the way developers used create rest api applications before api-platform appear.</p>
    <p>You can test the application. But check the routes availables.</p>
    <pre><code class="language-javascript">bin/console debug:router</code></pre>
    <p>You can see:</p>
    <pre><code class="language-javascript"> ------------------ -------- -------- ------ -------------------------- 
  Name               Method   Scheme   Host   Path                      
 ------------------ -------- -------- ------ -------------------------- 
  _twig_error_test   ANY      ANY      ANY    /_error/{code}.{_format}  
  products           GET      ANY      ANY    /api/products             
  get_products       GET      ANY      ANY    /api/products/{product}   
  post_products      POST     ANY      ANY    /api/products             
  patch_products     PATCH    ANY      ANY    /api/products/{product}   
  put_products       PUT      ANY      ANY    /api/products/{product}   
  delete_products    DELETE   ANY      ANY    /api/products/{product}   
 ------------------ -------- -------- ------ -------------------------- </code></pre>
 <p>So, you can test on:</p>
 <pre><code class="language-javascript">http://localhost/api/products</code></pre>

</div>
@endsection
