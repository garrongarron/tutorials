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
    <h1>Node</h1>

    <p>Create and go to the /node-tutorial folder.</p>
    <pre><code class="language-bash">mkdir node-tutorial && cd node-tutorial</code></pre>


    <p>Create an app.js file.</p>
    <pre><code class="language-javascript">const express = require('express');
const app = express();

app.use((req, res, next) =&gt; {
    res.status(200).json({
        message: 'It works!'
    });
});

module.exports = app;</code></pre>


    <p>Create an server.js file.</p>
    <pre><code class="language-javascript">const http = require('http');
const app = require('./app');

const port = process.env.PORT || 3000;
const server = http.createServer(app);

server.listen(port);</code></pre>

    <p>Create a folder docker-compose.yml</p>
    <pre><code class="language-css">version: &quot;2&quot;

services:
  node:
    image: &quot;node:8&quot;
    user: &quot;node&quot;
    working_dir: /home/node/app
    environment:
      - NODE_ENV=production
    volumes:
      - ./:/home/node/app
    ports:
      - &quot;3000:3000&quot;
    command: bash -c &quot;npm -y init &amp;&amp; npm install --save express&quot;</code></pre>


    <p>Run the command below in order to execute automatically 'npm init' into the container (which has node and npm installed) and setting up by default all data required using the flag '-y'.</p>
    <p>You can edit those details later.</p>
    <p>You are installing express library and all his dependencies as a part to this project too.</p>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>
   
    <p>As a result, a package.json file is created.</p>
    <pre><code class="language-javascript">{
  &quot;name&quot;: &quot;app&quot;,
  &quot;version&quot;: &quot;1.0.0&quot;,
  &quot;description&quot;: &quot;&quot;,
  &quot;main&quot;: &quot;app.js&quot;,
  &quot;scripts&quot;: {
    &quot;test&quot;: &quot;echo \&quot;Error: no test specified\&quot; &amp;&amp; exit 1&quot;,
    &quot;start&quot;: &quot;node server.js&quot;
  },
  &quot;keywords&quot;: [],
  &quot;author&quot;: &quot;&quot;,
  &quot;license&quot;: &quot;ISC&quot;,
  &quot;dependencies&quot;: {
    &quot;express&quot;: &quot;^4.17.1&quot;
  }
}</code></pre>

    <p>Change the command in docker-compose.yml file.</p>
    <pre><code class="language-bash">command: bash -c &quot;npm start&quot;</code></pre>

    <p>Now, you can run docker again.</p>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>

    <p>And test the application on your browser.</p>
    <pre><code class="language-bash">http://localhost:3000/</code></pre>


    <p>You must install a tool to make requests easily. On of the most popular now is Postman. So, just google it and install it.</p>
    <p>If everything is ok you have to get a json file.</p>
    <pre><code class="language-javascript">{
    message: 'It works!'
}</code></pre>

    <p>The console is running a web service on background. You can stop the process typing Ctrl+C.</p>
    <p>But, if you run docker-compose in detached mode...</p>
    <pre><code class="language-bash">sudo docker-compose up -d</code></pre>

    <p>...You would stop the service using down flag instead of the stop flag.</p>
    <pre><code class="language-bash">sudo docker-compose down</code></pre>

    <p>If you use stop flag.</p>
    <pre><code class="language-bash">sudo docker-compose stop</code></pre>

    <p>As you can see, the container is has not deleted. It is sleeping.</p>
    <pre><code class="language-bash">$ sudo docker container ls -a
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS                     PORTS               NAMES
5bf6d6495020        node:8              &quot;docker-entrypoint.s&#x2026;&quot;   13 seconds ago      Exited (0) 6 seconds ago                       node-tutorial_node_1</code></pre>

    <p>If you use stop flag you can remove the container using the value of the columns "CONTAINER ID" or "NAMES".</p>
    <p>Using the NAMES (the last column) it looks like:</p>
    <pre><code class="language-bash">sudo docker container rm node-tutorial_node_1</code></pre>

    <p>Using the CONTAINER ID  it looks like:</p>
    <pre><code class="language-bash">sudo docker container rm 5bf6d6495020</code></pre>
    <p>Your container can has different values. Do not copy and paste from this tutorial.</p>

    <h2>Routing</h2>
    <p>Create a /api folder and the create other one named /api/routes inside.</p>
    <p>Then, create /api/routes/orders.js file.</p>
    <pre><code class="language-javascript">const express = require('express');
const router = express.Router();

router.get('/', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Orders were fetched'
    });
});

router.post('/', (req, res, next) =&gt; {
    res.status(201).json({
        message: 'Order was created'
    });
});

router.get('/:orderId', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Order details',
        orderId: req.params.orderId
    });
});

router.delete('/:orderId', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Order deleted',
        orderId: req.params.orderId
    });
});

module.exports = router;</code></pre>
    
    <p>Then, create /api/routes/products.js file.</p>
    <pre><code class="language-javascript">const express = require('express');
const router = express.Router();

router.get('/', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Handling GET requests to /products'
    });
});

router.post('/', (req, res, next) =&gt; {
    res.status(201).json({
        message: 'Handling POST requests to /products'
    });
});

router.get('/:productId', (req, res, next) =&gt; {
    const id = req.params.productId;
    if (id === 'special') {
        res.status(200).json({
            message: 'You discovered the special ID',
            id: id
        });
    } else {
        res.status(200).json({
            message: 'You passed an ID'
        });
    }
});

router.patch('/:productId', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Updated product!'
    });
});

router.delete('/:productId', (req, res, next) =&gt; {
    res.status(200).json({
        message: 'Deleted product!'
    });
});

module.exports = router;</code></pre>

    <p>In app.js replace app.use(...) by the following code.</p>
    <pre><code class="language-javascript">const productRoutes = require('./api/routes/products');
const orderRoutes = require('./api/routes/orders');

app.use('/products', productRoutes);
app.use('/orders', orderRoutes);</code></pre>

    <p>What we did is:</p>
    <ul>
        <li>We created specific folder for our routes.</li>
        <li>We got router manager express.Router() from the library.</li>
        <li>We got req.params.yourParam from the url.</li>
        <li>We setted a status res.status(200) for every route.</li>
    </ul>
    <p>We can test  the following test cases.</p>
    <pre><code class="language-javascript">GET     localhost:3000/products/
GET     localhost:3000/products/1
POST    localhost:3000/products/1
DELETE  localhost:3000/products/1
PATCH   localhost:3000/products/1

GET     localhost:3000/orders/
GET     localhost:3000/orders/1
POST    localhost:3000/orders/1
DELETE  localhost:3000/orders/1</code></pre>
    

    <h2>Handling Errors</h2>
    <p>Instead of shut down the server and run again and again. You would prefer run a tool that do this work automatically.</p>
    <p>To do that you can install nodemon.</p>
    <pre><code class="language-javascript">npm install --save-dev nodemon</code></pre>

    <p>Then you have to add some configuration in package.json. You will see "devDependencies" as a last element of this json file.</p>
    <pre><code class="language-javascript">"devDependencies": {
    "nodemon": "^1.19.2"
}</code></pre>

    <p>Then, in the same level of json, you have to write a "scripts" element.</p>
    <pre><code class="language-javascript">&quot;scripts&quot;: {
    ...
    &quot;start&quot;: &quot;nodemon server.js&quot;
},</code></pre>

    <p>And finally, to automatically stop and restart the server every time there is a change in any file you have to run.</p>
    <pre><code class="language-bash">npm start</code></pre>
    <p>But unfortunately, this procedure is not available for docker-compose environment at the moment.</p>
    <p>This procedure is useful for you if you have node and npm installed in your host machine.</p>
    <p>I have installed them in this way, but for didactic reasons we will avoid that installation in this tutorial.</p>
    <p>Therefore, you can forget all what you have seen from Handling Errors title until now, and continue from here. :)</p>


    <p>When you are developing you want to know what kind of request you receive in the console.</p>
    <p>For it, you can install morgan as a dependency of the project.</p>
    <p>Temporarily you can change docker-compose.yml.</p>
    <pre><code class="language-bash">command: bash -c &quot;npm install --save morgan&quot;</code></pre>
    <p>And then, run up docker-compose.</p>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>
    <p>After this, you will have added morgan package in you package.json file. And if you have follow this tutorial from scratch all dependencies what we have got are these.</p>
    <pre><code class="language-javascript">&quot;dependencies&quot;: {
    &quot;express&quot;: &quot;^4.17.1&quot;,
    &quot;morgan&quot;: &quot;^1.9.1&quot;
}</code></pre>
    
    
    <p>Now, we have to roll back docker-compose.yml. The shortcut CTRL+Z CTRL+S could be a good way.</p>
    <pre><code class="language-css">command: bash -c &quot;npm start&quot;</code></pre>

    <p>Before running docker-compose again. We need to add in app.js file  the morgan dependence as a first middleware.</p>
    
    <pre><code class="language-javascript">...
const morgan = require('morgan');
...
app.use(morgan('dev'));//this is the first middleware
app.use(...);//this is the second middleware
app.use(...);//this is the third middleware</code></pre>

    <p>If we run the server without using -d we can see the logs on the screen.</p>
    <p>Just add the middlewares after Morgan middleware. You can handle the errors placing the middlewares which handle them just at the end.</p>
    <pre><code class="language-javascript">app.use(...);//this is the second middleware
app.use(...);//this is the third middleware
...
app.use((req, res, next) =&gt; {
    const error = new Error('Not found');
    error.status = 404;
    next(error);
})

app.use((error, req, res, next) =&gt; {
    res.status(error.status || 500);
    res.json({
        error: {
            message: error.message // 'Not found'
        }
    });
});</code></pre>

    <p>As you can see, there is no res (response) sent in the return in any of both error handlers. So, the flow continue to the next middleware.</p>
    <p>The last middleware assume there is an error and set the 500 error message.</p>
    <p>Now you can run the new functionality.</p>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>

    <p>You can test the following test cases.</p>
    <pre><code class="language-javascript">GET     localhost:3000/products/
...
GET     localhost:3000/urlwhichnotexit
GET     localhost:3000/
error.status(404); /*instead of*/ error.status = 404; // restart needed
</code></pre>


    <p>To allow CORS add the following code before any widdleware which handle routes in app.js.</p>
    <pre><code class="language-javascript">app.use((req, res, next) =&gt; {
  res.header(&quot;Access-Control-Allow-Origin&quot;, &quot;*&quot;);
  res.header(
    &quot;Access-Control-Allow-Headers&quot;,
    &quot;Origin, X-Requested-With, Content-Type, Accept, Authorization&quot;
  );
  if (req.method === 'OPTIONS') {
      res.header('Access-Control-Allow-Methods', 'PUT, POST, PATCH, DELETE, GET');
      return res.status(200).json({});
  }
  next();
});</code></pre>














    <p>As this is a REST API application, we assume the requests would be json. That is why, we need to get and handle the attributes from any body requests received.</p>
    <p>We can do it installing body-parser library.</p>
    <p>I will assume you understand that to run npm commands you have to modify docker-compose and his command line.</p>
    <pre><code class="language-css">command: bash -c &quot;npm install --save body-parser&quot;</code></pre>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>
    <p>And after that, rollback.</p>
    <pre><code class="language-css">command: bash -c &quot;npm start&quot;</code></pre>
    
    <p>All that you need to do is import body-parser and add some middlewares before to handle routes in app.js.</p>
    <pre><code class="language-javascript">...
const bodyParser = require(&quot;body-parser&quot;);
...
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());
...</code></pre>
    <p>After that we can access to the params of the body of the request. Check the following hypothetical code.</p>
    <pre><code class="language-javascript">router.post(&quot;/&quot;, (req, res, next) =&gt; {
  console.log(req.body.name)
});</code></pre>

 


    <p>Now, we need to install mongoose.</p>
    <pre><code class="language-css">command: bash -c &quot;npm install --save mongoose&quot; /* in docker-compose.yml */</code></pre>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>
    <p>And after that, rollback.</p>
    <pre><code class="language-css">command: bash -c &quot;npm start&quot;</code></pre>

    <p>In app.js we need to add the connection.</p>
    <pre><code class="language-javascript">...
const mongoose = require(&quot;mongoose&quot;);
...
mongoose.connect(
    &quot;mongodb://admin:secret@mongo:27017/myDatabase&quot;,
  {
    useMongoClient: true
  }
);
mongoose.Promise = global.Promise;
...</code></pre>

    <p>You can change the connection string to make this project easier.</p>
    <pre><code class="language-javascript">"mongodb://mongo:27017/expressmongo" // instead of "mongodb://admin:secret@mongo:27017/myDatabase",</code></pre>

    <p>Then, we need to create a folder named model and an api/model/order.js file.</p>
    <pre><code class="language-javascript">const mongoose = require('mongoose');

const orderSchema = mongoose.Schema({
    _id: mongoose.Schema.Types.ObjectId,
    product: { type: mongoose.Schema.Types.ObjectId, ref: 'Product', required: true },
    quantity: { type: Number, default: 1 }
});

module.exports = mongoose.model('Order', orderSchema);</code></pre>



    <p>We need also, an api/model/product.js file.</p>
    <pre><code class="language-javascript">const mongoose = require('mongoose');

const productSchema = mongoose.Schema({
    _id: mongoose.Schema.Types.ObjectId,
    name: { type: String, required: true },
    price: { type: Number, required: true }
});

module.exports = mongoose.model('Product', productSchema);</code></pre>

    <p>Afther the models, we need to add functionality to the routes in api/routes/orders.js.</p>
    <pre><code class="language-javascript">const express = require(&quot;express&quot;);
const router = express.Router();
const mongoose = require(&quot;mongoose&quot;);

const Order = require(&quot;../models/order&quot;);
const Product = require(&quot;../models/product&quot;);

// Handle incoming GET requests to /orders
router.get(&quot;/&quot;, (req, res, next) =&gt; {
  Order.find()
    .select(&quot;product quantity _id&quot;)
    .exec()
    .then(docs =&gt; {
      res.status(200).json({
        count: docs.length,
        orders: docs.map(doc =&gt; {
          return {
            _id: doc._id,
            product: doc.product,
            quantity: doc.quantity,
            request: {
              type: &quot;GET&quot;,
              url: &quot;http://localhost:3000/orders/&quot; + doc._id
            }
          };
        })
      });
    })
    .catch(err =&gt; {
      res.status(500).json({
        error: err
      });
    });
});

router.post(&quot;/&quot;, (req, res, next) =&gt; {
  Product.findById(req.body.productId)
    .then(product =&gt; {
      if (!product) {
        return res.status(404).json({
          message: &quot;Product not found&quot;
        });
      }
      const order = new Order({
        _id: mongoose.Types.ObjectId(),
        quantity: req.body.quantity,
        product: req.body.productId
      });
      return order.save();
    })
    .then(result =&gt; {
      console.log(result);
      res.status(201).json({
        message: &quot;Order stored&quot;,
        createdOrder: {
          _id: result._id,
          product: result.product,
          quantity: result.quantity
        },
        request: {
          type: &quot;GET&quot;,
          url: &quot;http://localhost:3000/orders/&quot; + result._id
        }
      });
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.get(&quot;/:orderId&quot;, (req, res, next) =&gt; {
  Order.findById(req.params.orderId)
    .exec()
    .then(order =&gt; {
      if (!order) {
        return res.status(404).json({
          message: &quot;Order not found&quot;
        });
      }
      res.status(200).json({
        order: order,
        request: {
          type: &quot;GET&quot;,
          url: &quot;http://localhost:3000/orders&quot;
        }
      });
    })
    .catch(err =&gt; {
      res.status(500).json({
        error: err
      });
    });
});

router.delete(&quot;/:orderId&quot;, (req, res, next) =&gt; {
  Order.remove({ _id: req.params.orderId })
    .exec()
    .then(result =&gt; {
      res.status(200).json({
        message: &quot;Order deleted&quot;,
        request: {
          type: &quot;POST&quot;,
          url: &quot;http://localhost:3000/orders&quot;,
          body: { productId: &quot;ID&quot;, quantity: &quot;Number&quot; }
        }
      });
    })
    .catch(err =&gt; {
      res.status(500).json({
        error: err
      });
    });
});

module.exports = router;
</code></pre>


<p>And the same with api/model/product.js file.</p>
    <pre><code class="language-javascript">const express = require(&quot;express&quot;);
const router = express.Router();
const mongoose = require(&quot;mongoose&quot;);

const Product = require(&quot;../models/product&quot;);

router.get(&quot;/&quot;, (req, res, next) =&gt; {
  Product.find()
    .select(&quot;name price _id&quot;)
    .exec()
    .then(docs =&gt; {
      const response = {
        count: docs.length,
        products: docs.map(doc =&gt; {
          return {
            name: doc.name,
            price: doc.price,
            _id: doc._id,
            request: {
              type: &quot;GET&quot;,
              url: &quot;http://localhost:3000/products/&quot; + doc._id
            }
          };
        })
      };
      //   if (docs.length &gt;= 0) {
      res.status(200).json(response);
      //   } else {
      //       res.status(404).json({
      //           message: 'No entries found'
      //       });
      //   }
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.post(&quot;/&quot;, (req, res, next) =&gt; {
  const product = new Product({
    _id: new mongoose.Types.ObjectId(),
    name: req.body.name,
    price: req.body.price
  });
  product
    .save()
    .then(result =&gt; {
      console.log(result);
      res.status(201).json({
        message: &quot;Created product successfully&quot;,
        createdProduct: {
            name: result.name,
            price: result.price,
            _id: result._id,
            request: {
                type: 'GET',
                url: &quot;http://localhost:3000/products/&quot; + result._id
            }
        }
      });
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.get(&quot;/:productId&quot;, (req, res, next) =&gt; {
  const id = req.params.productId;
  Product.findById(id)
    .select('name price _id')
    .exec()
    .then(doc =&gt; {
      console.log(&quot;From database&quot;, doc);
      if (doc) {
        res.status(200).json({
            product: doc,
            request: {
                type: 'GET',
                url: 'http://localhost:3000/products'
            }
        });
      } else {
        res
          .status(404)
          .json({ message: &quot;No valid entry found for provided ID&quot; });
      }
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({ error: err });
    });
});

router.patch(&quot;/:productId&quot;, (req, res, next) =&gt; {
  const id = req.params.productId;
  const updateOps = {};
  for (const ops of req.body) {
    updateOps[ops.propName] = ops.value;
  }
  Product.update({ _id: id }, { $set: updateOps })
    .exec()
    .then(result =&gt; {
      res.status(200).json({
          message: 'Product updated',
          request: {
              type: 'GET',
              url: 'http://localhost:3000/products/' + id
          }
      });
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.delete(&quot;/:productId&quot;, (req, res, next) =&gt; {
  const id = req.params.productId;
  Product.remove({ _id: id })
    .exec()
    .then(result =&gt; {
      res.status(200).json({
          message: 'Product deleted',
          request: {
              type: 'POST',
              url: 'http://localhost:3000/products',
              body: { name: 'String', price: 'Number' }
          }
      });
    })
    .catch(err =&gt; {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

module.exports = router;
</code></pre>


<p>Finally, add mongodb service to docker-compose.yml</p>
<pre><code class="language-css">version: &quot;2&quot;

services:
  node:
    image: &quot;node:8&quot;
    user: &quot;node&quot;
    working_dir: /home/node/app
    environment:
      - NODE_ENV=production
    volumes:
      - ./:/home/node/app
    ports:
      - &quot;3000:3000&quot;
    command: bash -c &quot;npm start&quot;
    links:
      - mongo
  mongo:
    container_name: mongo
    image: mongo
    ports:
      - &quot;27017:27017&quot;</code></pre>

    <p>Hopefully, we have our Api finished.</p>
    <pre><code class="language-bash">sudo docker-compose up</code></pre>



    <p>Just in case you need to create custom user and database, we present you a simple and easy approach to do it.</p>

    <div style="border: 1px solid gray; padding: 2vw; border-radius: 10px; box-shadow: 10px 10px 5px grey;">
      <h3>Create custom users, passwords and databases</h3>
      <p>While the docker-compose is running, we will use other console to access to the mongo container.</p>
      <pre><code class="language-bash">sudo docker exec -it mongo bash</code></pre>

      <p>Into the container go to the mongo client.</p>
      <pre><code class="language-bash">mongo</code></pre>

      <p>Create a database named myDatabase and move into it with the following command.</p>
      <pre><code class="language-bash">use myDatabase</code></pre>


      <p>Create a user and password.</p>
      <pre><code class="language-bash">db.createUser({ user:'admin', pwd:'secret', roles:['readWrite','dbAdmin'] });</code></pre>
      <p>Of course, we do not want to grant it 'dbAdmin' role!</p>

      <p>Then, you can finish the mongo client typing 'exit'. Mongo will tell you 'bye'.</p>
      <pre><code class="language-html">&gt; exit
bye
root@c0NtAiN3RiDp:/# </code></pre>


      <p>Then, you can go out from the mongo container typing 'exit' again. The container will tell you 'exit'.</p>
      <pre><code class="language-html">/# exit
exit
api_rest@_with_node:~$ </code></pre>

      <p>But, if you do not want to do all these steps every 
      time you deploy this project the easy way is just use the default mongo docker configuration.</p>
    </div>









</div>    
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
