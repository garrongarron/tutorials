@extends('general.layout')


@section('content')
<div class="content">
    <h1>PHP & Mysql</h1>
    <p>Hi there! just let's go to write a connection a procedural way.</p>
    <pre><code class="language-javascript">$servername = &quot;localhost&quot;;
$username = &quot;username&quot;;
$password = &quot;password&quot;;
$db = &quot;dbname&quot;;
// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);
// Check connection
if (!$conn) {
   die(&quot;Connection failed: &quot; . mysqli_connect_error());
}
echo &quot;Connected successfully&quot;;</code></pre>


    <p>We can use PDO too.</p>
    <pre><code class="language-javascript">$servername = &quot;localhost&quot;;
$username = &quot;username&quot;;
$password = &quot;password&quot;;
$db = &quot;dbname&quot;;
try {
   $conn = new PDO(&quot;mysql:host=$servername;dbname=myDB&quot;, $username, $password, []);
   // set the PDO error mode to exception
   $conn-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   echo &quot;Connected successfully&quot;;
} catch(PDOException $e) {
   echo &quot;Connection failed: &quot; . $e-&gt;getMessage();
}</code></pre>

<p>Of course!, you have to have installed the extensions to communicate php with mysql.</p>
<p>You can run this command line to see the extension you have installed in your server (localhost).</p>

<pre><code class="language-bash">php -m | grep mysql</code></pre>
<p>The result could be the following.</p>
<pre><code class="language-bash">mysqli
mysqlnd
pdo_mysql</code></pre>
<p><b>Tip:</b> If you do not see <i>mysqli</i> nor <i>pdo_mysql</i>, you have to install them.</p>

<p>But, let's jump into some pieces of code.</p>
<p>To prevent unexpected results we need to sanitize the input data.</p>
<pre><code class="language-javascript">$mysqli = new mysqli(&quot;localhost&quot;, &quot;my_user&quot;, &quot;my_password&quot;, &quot;world&quot;);

/* check connection */
if (mysqli_connect_errno()) {
    printf(&quot;Connect failed: %s\n&quot;, mysqli_connect_error());
    exit();
}

$mysqli-&gt;query(&quot;CREATE TEMPORARY TABLE myCity LIKE City&quot;);

$city = &quot;&apos;s Hertogenbosch&quot;;

/* this query will fail, cause we didn&apos;t escape $city */
if (!$mysqli-&gt;query(&quot;INSERT into myCity (Name) VALUES (&apos;$city&apos;)&quot;)) {
    printf(&quot;Error: %s\n&quot;, $mysqli-&gt;sqlstate);
}

$city = $mysqli-&gt;real_escape_string($city);

/* this query with escaped $city will work */
if ($mysqli-&gt;query(&quot;INSERT into myCity (Name) VALUES (&apos;$city&apos;)&quot;)) {
    printf(&quot;%d Row inserted.\n&quot;, $mysqli-&gt;affected_rows);
}

$mysqli-&gt;close();</code></pre>


<p>Other tricks we can use is call filter_var() function.</p>
<pre><code class="language-javascript">$string = &quot;&lt;h1&gt;Hello World!&amp;nbsp;&lt;/h1&gt;&quot;;
$resut; = filter_var($string, FILTER_SANITIZE_STRING);
echo $resut; // "Hello World!"
</code></pre>
<p>We can use the  filter_input() function also.</p>
<pre><code class="language-javascript">$name = filter_var($_POST[&apos;name&apos;], FILTER_SANITIZE_STRING);
...
$email = filter_var($_POST[&apos;email&apos;], FILTER_VALIDATE_EMAIL);
if ( $email === false ) {
 // Handle invalid emails here
 }</code></pre>

<p>Other way to protect the data is using filter_input() function.</p>
<pre><code class="language-javascript">$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$email= filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$search = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);</code></pre>









<p>Other good practice is bind parameters into your queries. It prevent sql injection.</p>
<pre><code class="language-javascript">$servername = &quot;localhost&quot;;
$username = &quot;username&quot;;
$password = &quot;password&quot;;
$dbname = &quot;myDB&quot;;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn-&gt;connect_error) {
    die(&quot;Connection failed: &quot; . $conn-&gt;connect_error);
}

// prepare and bind
$stmt = $conn-&gt;prepare(&quot;INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)&quot;);
$stmt-&gt;bind_param(&quot;sss&quot;, $firstname, $lastname, $email);

// set parameters and execute
$firstname = &quot;John&quot;;
$lastname = &quot;Doe&quot;;
$email = &quot;john@example.com&quot;;
$stmt-&gt;execute();

$firstname = &quot;Mary&quot;;
$lastname = &quot;Moe&quot;;
$email = &quot;mary@example.com&quot;;
$stmt-&gt;execute();

$firstname = &quot;Julie&quot;;
$lastname = &quot;Dooley&quot;;
$email = &quot;julie@example.com&quot;;
$stmt-&gt;execute();

echo &quot;New records created successfully&quot;;

$stmt-&gt;close();
$conn-&gt;close();</code></pre>
<p>This function binds the parameters to the SQL query and tells the database what the parameters are.
     The "sss" argument, in this example, lists the types of data that the parameters are.
     The s character tells mysql that the parameter is a string.</p>
<ul>
  <li>i - integer</li>
  <li>d - double</li>
  <li>s - string</li>
  <li>b - BLOB</li>
</ul>

<h2>API PHP for Mysql</h2>
<p>PHP offers three different APIs to connect to MySQL. They are the  <b>mysql</b>, <b>mysqli</b>, and <b>PDO</b> extensions.</p>
<p>The link: <a href="https://downloads.mysql.com/docs/apis-php-en.pdf">https://downloads.mysql.com/docs/apis-php-en.pdf</a></p>

<h2>PHP.net</h2>
<p>Remenber you always can count on the oficial documentation of php.</p>
<p>The Link: <a href="https://www.php.net">https://www.php.net</a></p>

<h2>Just SQL</h2>
<p>There are a lot of explanation about SQL.</p>
<p>The link: <a href="https://www.w3schools.com/sql/">https://www.w3schools.com/sql/</a></p>

<h2>Just Mysql</h2>
<p>There are a lot of explanation about Mysql.</p>
<p>The link: <a href="https://www.tutorialspoint.com/mysql/">https://www.tutorialspoint.com/mysql/</a></p>



<h2>Stored Procedures</h2>
<p>Just in case you have to handle complex logic process using data from the database. You do not have to process it with PHP.</p>
<p>You must to use Stored Procedures instead.</p>
<p>The link: <a href="http://www.mysqltutorial.org/introduction-to-sql-stored-procedures.aspx">http://www.mysqltutorial.org/introduction-to-sql-stored-procedures.aspx</a></p>

























</div>
@endsection
