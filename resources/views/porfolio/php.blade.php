@extends('general.layout')


@section('css')
<link href="{{asset('css/prism.css')}}" rel="stylesheet" type="text/css"/>
<style>
p{
    margin: 16px auto;
}</style>
@endsection

@section('content')
<div class="content">
    <h1>PHP</h1>

    <p>Create folder and go to the folder.</p>
    <pre><code class="language-bash">mkdir laradock-tutorial && cd laradock-tutorial</code></pre>

    <p>Clone laradock project.</p>
    <pre><code class="language-bash">git clone https://github.com/laradock/laradock.git</code></pre>

    <p>Create project folder.</p>
    <pre><code class="language-bash">mkdir project-folder</code></pre>

    <p>Go to laradock folder. Take note this is not the folder we made abouve.</p>
    <pre><code class="language-bash">cd laradock</code></pre>

    <p>Copy environment configuration.</p>
    <pre><code class="language-bash">cp env-example .env</code></pre>

    <p>Set project folder into /laradock-tutorial/laradock/.env file.</p>
    <pre><code class="language-bash">APP_CODE_PATH_HOST=../project-folder/</code></pre>





    <p>Run docker-compose in /laradock-tutorial/laradock folder.</p>
    <pre><code class="language-bash">docker-compose up -d nginx mysql</code></pre>

    <p>Go into the container.</p>
    <pre><code class="language-bash">sudo docker-compose exec workspace bash</code></pre>

    <p>Clone laravel project into the container. Be sure write dot at the end pointing out run the command in the current folder.</p>
    <pre><code class="language-bash">composer create-project --prefer-dist laravel/laravel .</code></pre>

    <p>Set up laravel database environment configuration according to laradock environment configuration.</p>
    <p>Take the values from /laradock-tutorial/laradock/.env.</p>
    <pre><code class="language-bash">...
MYSQL_DATABASE=default
MYSQL_USER=default
MYSQL_PASSWORD=secret
...
</code></pre>

    <p>And set the values in /laradock-tutorial/project-folder/.env 
    (or in /var/www/.env if you are into the container).</p>
    <pre><code class="language-bash">...
DB_HOST=mysql
DB_DATABASE=default
DB_USER=default
DB_PASSWORD=secret
...
</code></pre>

    <p>Set permission in the project folder (/laradock-tutorial/project-folder/ or /var/www/).</p>
    <pre><code class="language-bash">sudo chmod -R 777  .</code></pre>

    <p>Check laravel application.</p>
    <pre><code class="language-bash">http://localhost</code></pre>

   











    <p>You can go out of the container just writing exit.</p>
    <pre><code class="language-bash">root@bb281fc97634:/var/www# exit</code></pre>
    
    <p>You can stop the container from /laradock-tutorial/laradock/</p>
    <pre><code class="language-bash">sudo docker-compose stop</code></pre>

    <p> Or you can down the container</p>
    <pre><code class="language-bash">sudo docker-compose down</code></pre>

    <p>So, if you stop or down the container run it again and go into the container as we mentioned before.</p>
    <p>Then, create a module named Product.</p>
    <pre><code class="language-bash">php artisan make:model Product --all</code></pre>




    <p>Add some fields into the /database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php.</p>
    <pre><code class="language-javascript">public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table-&gt;bigIncrements('id');
        $table-&gt;string('name');
        $table-&gt;string('description');
        $table-&gt;timestamps();
    });
}</code></pre>


    <p>Enable fillable fields into the Model in app/Product.php</p>
    <pre><code class="language-javascript">class Product extends Model
{
    protected $fillable =['name','description'];
}</code></pre>

    <p>Set the factory fields in the factory file in database/factories/ProductFactory.php using faker.</p>
    <pre><code class="language-javascript">$factory-&gt;define(Product::class, function (Faker $faker) {
    return [
        'name' =&gt; $faker-&gt;name,
        'description' =&gt; $faker-&gt;unique()-&gt;email
    ];
});</code></pre>

    <p>Create a seeder by console.</p>
    <pre><code class="language-javascript">php artisan make:seeder ProductSeeder</code></pre>

    <p>Call the factory in database/seeds/ProductSeeder.php.</p>
    <pre><code class="language-javascript">public function run()
{
    factory(App\Product::class,2)-&gt;create();
}</code></pre>

    <p>Set the general database seeder in database/seeds/DatabaseSeeder.php</p>
    <pre><code class="language-javascript">public function run()
{
    $this-&gt;call(ProductSeeder::class);
}</code></pre>

    <p>Run the migration and the seeder.</p>
    <pre><code class="language-javascript">php artisan migrate:refresh --seed</code></pre>


    <p>It will appear the following error.</p>
    <pre><code class="language-javascript"> Illuminate\Database\QueryException  : SQLSTATE[HY000] [2054] The server requested authentication method unknown to the client (SQL: select * from information_schema.tables where table_schema = default and table_name = migrations and table_type = 'BASE TABLE')</code></pre>

    <p>To solve this issue you have to go into mysql container and alter the way the user are IDENTIFIED and configure mysql_native_password way.</p>
    
    
    <p>Check the mysql container name (probably 'laradock_mysql_1'), and go into it.</p>
    <pre><code class="language-bash">sudo docker exec -it laradock_mysql_1 bash</code></pre>

    <p>Once inside, connect the databse using mysql client.</p>
    <pre><code class="language-null">root@randon123:/# mysql -p root -u</code></pre>

    <p>Use the password in located in /laradock-tutorial/laradock/.env, which is root.</p>
    <pre><code class="language-bash">MYSQL_ROOT_PASSWORD=root</code></pre>

    <p>Then, you have to alter the IDENTIFIED way the user use.</p>
    <pre><code class="language-bash">mysql&gt; ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
Query OK, 0 rows affected (0.08 sec)

mysql&gt; ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';
Query OK, 0 rows affected (0.11 sec)

mysql&gt; ALTER USER 'default'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
Query OK, 0 rows affected (0.14 sec)</code></pre>

    <p>In this way you solve this issue.</p>
    <p> But, if the table 'products' already exist and appear other issue with that. Run tinker and then delete the table.</p>
    <pre><code class="language-javascript">php artisan tinker</code></pre>
    <pre><code class="language-javascript">Schema::drop('products');</code></pre>


    <p>To generate routes go to routes/api.php and write the following.</p>
    <pre><code class="language-javascript">Route::apiResource('Product', 'ProductController');</code></pre> 

    <p>Then, in app/Providers/RouteServiceProvider.php write the following.</p>
    <pre><code class="language-javascript">public function boot()
{
    ...
    Route::model('Product', \App\Product::class);
    ...
}</code></pre>

    <p>With this step, we mapping the url http://localhost/api/Product with the Product model.</p>
    <p>That enable to inject an instance of the model in the methods of the ProductController, as we will see in the next steps.</p>
    
    <p>In app/Http/Controllers/ProductController.php we have to write the following methods.</p>
    <pre><code class="language-javascript">public function index() {
    return Product::all();
}

public function show(Product $product) {
    return $product;
}

public function store(Request $request) {
    $product = Product::create($request-&gt;all());
    return response()-&gt;json($product, 201);
}

public function update(Request $request, Product $product) {
    $product-&gt;update($request-&gt;all());
    return response()-&gt;json($product, 200);
}

public function destroy(Product $product) {
    $product-&gt;delete();
    return response()-&gt;json(null, 204);
}</code></pre>






    <p>Now, you cant test the aplication with any API Rest consumer</p>
    

</div>    
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
