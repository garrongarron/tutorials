@extends('general.layout')


@section('css')
<link href="{{asset('css/prism.css')}}" rel="stylesheet" type="text/css"/>
<style>
p{
    margin: 16px auto;
}
.ad{
    padding: 0px 15px 15px 15px;
    box-shadow: 5px 5px 5px #ccc;
    border: 1px solid gray;
    border-radius: 15px;
}
</style>
@endsection

@section('content')
<div class="content">
    <h1>Python</h1>

    <p>Unfortunately, the dependencies for any python project are installed globally in the Operative System.</P>
    <p>But, thanks to freeze command, we can create easily the file requirements.txt that we will need to implement this project using Docker.</p>
    <pre><code class="language-javascript">pip freeze > requirements.txt</code></pre>

    <p>The result of this command is a file named requirements.txt.</p>
    <pre><code class="language-javascript">Click==7.0
Flask==1.1.1
flask-marshmallow==0.10.1
Flask-SQLAlchemy==2.4.0
itsdangerous==1.1.0
Jinja2==2.10.1
MarkupSafe==1.1.1
marshmallow==3.2.0
marshmallow-sqlalchemy==0.19.0
six==1.12.0
SQLAlchemy==1.3.8
Werkzeug==0.16.0
</code></pre>

    <p>So, let us go straight to create a folders needed for our python project  in /python-project/docpyenv.</p>
    <pre><code class="language-bash">mkdir python-project && mkdir python-project/docpyenv && cd python-project/docpyenv</code></pre>
    <p>In the folder /python-project/docpyenv create Dockerfile file, following the demo officialy offered in https://hub.docker.com/_/python.</p>
    <pre><code class="language-bash">FROM python:3

#WORKDIR /usr/src/app

COPY requirements.txt ./
#RUN pip install --no-cache-dir -r requirements.txt
RUN pip install -r requirements.txt

#COPY . .

#CMD [ "python", "./your-daemon-or-script.py" ]

########################################################
#          sudo docker build -t pyenv:1.0 .            #
########################################################


########################################################
#          sudo docker rmi pyenv:1.0                   #
########################################################</code></pre>

    <p>We have just commented some unnecessary lines and have added two commands to build and destroy the base image which we will use as environment.</p>
    <p>In this way, we have documented how to proceed in case we need to build this image.</p>
    <p>As you can see, we tagged this image as pyenv:1.0.</p>
    <p>To build this image, just place requirements.txt in the /python-project/docpyenv folder, in the same level of Dockerfile, and run this command on that location.</p>
    <pre><code class="language-bash">sudo docker build -t pyenv:1.0 .</code></pre>


    <p>It takes a while. After pull the image python:3 and download all the dependencies on it, you can see the image created.</p>
    <pre><code class="language-bash">sudo docker images</code></pre>
    <pre><code class="language-bash">REPOSITORY           TAG                 IMAGE ID            CREATED             SIZE
pyenv                1.0                 056847527e06        18 seconds ago      937MB</code></pre>

    <p>We can destroy this image with the following command.</p>
    <pre><code class="language-bash">sudo docker rmi pyenv:1.0</code></pre>
    <p>But, we do not have to do it now. The images have to remain for a while.</p>


    <p>Now, we are able to develop our local "Hello world" API RESTful web server application on Python!</p>
    <p>In the folder /python-tutorial create app.py file.</p>
    <pre><code class="language-bash">from flask import Flask, jsonify

app = Flask(__name__)

@app.route(&apos;/&apos;, methods=[&apos;GET&apos;])
def get_products():
  return jsonify({&apos;msg&apos;:&apos;Hello world&apos;}), 200

if __name__ == &apos;__main__&apos;:
  app.run(debug=True, host=&apos;0.0.0.0&apos;)</code></pre>

    <p>In the same folder create a new Dockerfile.</p>

    <pre><code class="language-bash">FROM pyenv:1.0

WORKDIR /usr/src/app

COPY . .

ENTRYPOINT [&quot;python&quot;]

CMD [&quot;app.py&quot;]


###############   TO BUILD   ###################
# sudo docker build -t pyenv:2.0 .
# sudo docker run -p 5000:5000 --name deploywith pyenv:2.0


###############   TO CLEAN   ###################
# sudo docker rm deploywith
# sudo docker rmi pyenv:2.0
</code></pre>

    <p>To run the web server execute the following commands in the same /python-tutorial folder.</p>
    <pre><code class="language-bash">sudo docker build -t pyenv:2.0 . && sudo docker run -p 5000:5000 --name deploywith pyenv:2.0</code></pre>


    <p>Now you can test this server application with a API REST consumer application. The most popular at the moment is Postman.</p>
    <pre><code class="language-javascript">GET http://localhost:5000</code></pre>

    <p>The answer will be...</p>
    <pre><code class="language-javascript">{
  "msg": "Hello world"
}</code></pre>

    <p>If you make any change in your code you have to re-build the image pyenv:2.0.</p>
    <p>To re-build after a modification in your code destroy the container (named deploywith) and his image (tagged pyenv:2.0)</p>
    <pre><code class="language-bash">sudo docker rm deploywith && sudo docker rmi pyenv:2.0</code></pre>

    <p>After that, you can run the server, as we saw few steps before, building the image and his container, just in one line.</p>
    <pre><code class="language-bash">sudo docker build -t pyenv:2.0 . && sudo docker run -p 5000:5000 --name deploywith pyenv:2.0</code></pre>

    <p>We are assuming you are developing, and that is why we did not mention you can run the server in detached mode.</p>
    <p>To do that just add "-d" flag to the container command.</p>
    <pre><code class="language-bash">sudo docker build -t pyenv:2.0 . && sudo docker run -d -p 5000:5000 --name deploywith pyenv:2.0</code></pre>
    <p>In this way, the local web server can run in background and the terminal is released to allow you keep working.</p>
    <p>If you run docker in detached mode you can stop its process with the following command in the /python-project.</p>
    <pre><code class="language-bash">sudo docker stop deploywith</code></pre>
    <p>And after, destroy the container and his image as you already know, in case you need it.</p>


    <p>By the way, you can see the running and sleeping containers and his corresponded images. Just, look the columns IMAGE and STATUS.</p>
    <pre><code class="language-bash">sudo docker container ls -a</code></pre>
    <pre><code class="language-bash">CONTAINER ID    IMAGE           COMMAND                 CREATED         STATUS                     PORTS        NAMES
bd8399c28da2    pyenv:2.0       &quot;python app.py&quot;         19 minutes ago  Exited (0) 18 minutes ago               deploywith</code></pre>


    <p>There is a chance you can use with this project as part of a bigger project which include this one as a microservice.</p>
    <p>If that is the case, you could need to run this web server as a service using docker-compose.</p>
    <p>You, just would create a /python-tutorial/docker-compose.yml file.</p>
    <pre><code class="language-css">version : &apos;3.4&apos;

services:
  api:
    build: .
    volumes:
      - ./usr/src/app
    ports:
      - 5000:5000</code></pre>

    <p>And you would use the following command to deploy your whole project including this REST API .</p>
    <pre><code class="language-bash">sudo docker-compose up # or sudo docker-compose up -d </code></pre>

    <p>And then, remove container and image with...</p>
    <pre><code class="language-bash">sudo docker rm python-project_api_1 &amp;&amp; sudo docker rmi python-project_api</code></pre>
    <p>Waring!: the container and images names could change depending on Docker or docker-compose version.</p>



    <div class="ad">
        <p>You can find the source until now in github repository.</p>
        <pre><code class="language-bash">git clone https://github.com/federicozacayan/restful-api-python.git python-project</code></pre>

        <p>The you can list the commits.</p>
        <pre><code class="language-bash">git log --oneline</code></pre>

        <p>The you can go to the commit 'Hello World'.</p>
        <pre><code class="language-bash">git checkout 76eefc3</code></pre>
    </div>



    <p>Now, it is moment to improve our Hello World application.</p>
    <p>To begin from the beginning, we will create a package named flaskapi (lowercase).</p>
    <p>This package will be called by our main file /python-project/app.py</p>
    <pre><code class="language-javascript">from flaskapi import app

if __name__ == &apos;__main__&apos;:
  app.run(debug=True, host=&apos;0.0.0.0&apos;)</code></pre>


    <p>The packages are basically folders. But, their names will be the names of the packages what we will use in our source code.</p>
    <p>Into the folder we have the special file name __init__.py which is the main file in the package..</p>
    <p>Let us go to create the /python-project/flaskapi folder.</p>
    <pre><code class="language-bash">mkdir flaskapi</code></pre>

    <p>Now, the the /python-project/flaskapi/__init__.py file.</p>
    <pre><code class="language-bash">from flask import Flask
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config[&apos;SQLALCHEMY_DATABASE_URI&apos;] = &apos;sqlite:////tmp/test.db&apos;
app.config[&apos;SQLALCHEMY_TRACK_MODIFICATIONS&apos;] = False
db = SQLAlchemy(app)

from flaskapi import routes, errors</code></pre>

    <p>As you can see, at the end of the file we need to import and create routes and errors.</p>
    <p>We can start by /python-tutorial/flaskapi/errors.py.</p>
    <pre><code class="language-bash">from flask import jsonify
from flaskapi import app

@app.errorhandler(404)
def not_found(e):
    return jsonify({&apos;error&apos;:&apos;Not Data found&apos;}), 404

@app.errorhandler(405)
def not_found(e):
    return jsonify({&apos;error&apos;:&apos;Method Not Allowed&apos;}), 405

@app.errorhandler(400)
def not_found(e):
    return jsonify({&apos;error&apos;:&apos;Bad request&apos;}), 400
</code></pre>



    <p>And continuing creating the /python-project/flaskapi/routes.py file.</p>
    <pre><code class="language-bash">from flask import request, jsonify
from flaskapi.models import Product
from flaskapi.schemas import ProductSchema, product_schema, products_schema
from flaskapi import app, db

# Get a Product
@app.route('/product/&lt;id&gt;', methods=['GET'])
def get_product(id):
  product = Product.query.get(id)
  product_schema = ProductSchema()
  return product_schema.jsonify(product), 200

# Save a Product
@app.route('/product', methods=['POST'])
def addProduct():
    name = request.json['name']
    admin = Product(name=name)
    db.session.add(admin)
    db.session.commit()
    return jsonify({'status':201}), 201


# Get All Products
@app.route('/product', methods=['GET'])
def getProducts():
    products = Product.query.all()
    n = db.session.query(Product.name).count()
    output = products_schema.dump(products);
    return jsonify({
        'q': n,
        'product' : output
    }), 200

# Delete Product
@app.route('/product/&lt;id&gt;', methods=['DELETE'])
def deleteProduct(id):
    try:
        product = Product.query.get(id)
        db.session.delete(product)
        db.session.commit()
        return jsonify({'status':'200'}), 200
    except:
        return jsonify({'status':'500'}), 500



# Update Product
@app.route('/product/&lt;id&gt;', methods=['PUT'])
def updateProduct(id):
  product = Product.query.get(id)
  name = request.json['name']
  product.name = name
  db.session.commit()

  return product_schema.jsonify(product), 200
0</code></pre>

    <p>The approach we have to handle databases is  setting up the data structure through Models.</p>
    <p>Have a look to the /python-project/flaskapi/models.py file.</p>
    <pre><code class="language-bash">from flaskapi import db

class Product(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(80), nullable=False)

    def __repr__(self):
        return '&lt;Product %r&gt;' % self.name</code></pre>


  <p>In order to convert the database results in json we need to use schemas.</p>
  <p>That is way we need the following /python-project/flaskapi/schemas.py file.</p>
  <pre><code class="language-javascript">from flask_marshmallow import Marshmallow
from flaskapi.models import Product
from flaskapi import app, db

ma = Marshmallow(app)
class ProductSchema(ma.ModelSchema):
    class Meta:
        model = Product

product_schema = ProductSchema()
products_schema = ProductSchema(many=True)

#create database after define schemas
db.create_all()</code></pre>


    <p>All the magic happen, when every code line is placed in the properly place.</p>
    <p>For instance, when every file is imported or just one element is imported, the whole file is loaded and executed.</p>
    <p>There is no problem to over import one file several times, but you must to have a good eye to avoid circular loading.</p>
    <p>On the other hand, we are creating the database after every Model is loaded and not before.</p>
    <p>Otherwise, the database could be created with models missing or even worse, with no models.</p>

    <p>Now, is time to test the application!</p>





</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
