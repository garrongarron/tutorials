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
    <h1>React</h1>

    <p>Wellcome to React Section</p>
    <p>There was upnon a time an api RESTful built with React.
    This aplication consisted in two functions wrote in TypeScript, she was App.js and he was User.js.
    She was very happy. Every response she got was shared with her husband.
    He, as a good man, always rendered the data received from his wife.</p>
    <p>App.js had a lover, but User.js never realize that. 
    The third part was an elegant server who serve all the request he got of every single innocent App.js he met.</p>
    <p>The poor User.js was a good man. But he was not smart. 
    He never handled the status of his relationship and always obey the instructions App.js gave to him.</p>
    <p>This story start in the following way...</p>








    <h2>Chapter 1: Getting fake users from server.</h2>
    <p>Create react project</p>
    <pre><code class="language-css">npx create-react-app react-tutorial</code></pre>
    <p>Go to the folder</p>
    <pre><code class="language-css">cd react-tutorial</code></pre>
    <p>Overwrite the src/App.js file</p>

    <pre><code class="language-typescript">import React, { useState, useEffect } from &quot;react&quot;;
import User from './User'

function App() {

    const [info, setInfo] = useState();

    useEffect(() =&gt; { //same functionality of componentDidMount()
        fetch('http://jsonplaceholder.typicode.com/users')
        .then(res =&gt; res.json())
        .then((data) =&gt; {
            console.log(data);
            setInfo(data)
        })
        .catch(console.log)
    }, []);

    if(typeof info === 'undefined'){
        return (&lt;div&gt;Loading...&lt;/div&gt;)
    } else {
        return (&lt;div&gt;
            {info.map((contact) =&gt; (
                &lt;User key={contact.id} contact={contact}&gt;&lt;/User&gt;
            ))}
            &lt;/div&gt;)
    }
}

export default App;</code></pre>

    
    <p>Create src/User.js</p>
    <pre><code class="language-typescript">import React from &quot;react&quot;;

const User = ({contact}) =&gt; {
    return (&lt;div&gt;
                &lt;span&gt;Email: {contact.email}&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Name: {contact.name}&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Phone: {contact.phone}&lt;/span&gt;
                &lt;hr/&gt;
            &lt;/div&gt;);
}

export default User;</code></pre>
    
    <p>Run local server</p>
    <pre><code class="language-css">npm start</code></pre>

    <p>Check it out in the browser</p>
    <pre><code class="language-url">http://localhost:3000/</code></pre>







    <br/>
    <h2>Chapter 2: Delete Users.</h2>
    <p>Add deleting and deleteUser inner functions to src/App.js</p>
    <pre><code class="language-typescript">    ...
    const deleting = (id) =&gt;{
        let tmp  = info.filter((a) =&gt; {
            return a.id !== id;
        })
        setInfo(tmp);
    }

    const deleleUser = (id) =&gt; {
        fetch('https://jsonplaceholder.typicode.com/posts/'+id, {
          method: 'DELETE'
        })
        .then(() =&gt; {
            deleting(id);
        })
    }
    ...</code></pre>


    <p>Modify the return of src/App.js adding prop deleleUser.</p>
    <pre><code class="language-typescript">&lt;User key={contact.id} contact={contact} deleleUser={deleleUser} &gt;&lt;/User&gt;</code></pre>


    <p>Add a button and an inner function to add the id as a parameter in deleleUser method in src/User.js</p>
    <pre><code class="language-typescript">import React from &quot;react&quot;;

const User = ({contact, deleleUser}) =&gt; {
    const drop = () =&gt; {
        deleleUser(contact.id)
    }
    return (&lt;div&gt;
                &lt;span&gt;Email: {contact.email}&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Name: {contact.name}&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Phone: {contact.phone}&lt;/span&gt;
                &lt;input type=&quot;button&quot; onClick={drop} value=&quot;Delete&quot;/&gt;
                &lt;hr/&gt;
            &lt;/div&gt;);
}

export default User;</code></pre>

<p>Test the application.</p> 












<br/>
<h2>Chapter 3: Update Users.</h2>
<p>Add the following methods for update in src/App.js and send updateUser function as a prop.</p>
<pre><code class="language-typescript">    ...
    const updating = (post) =&gt;{
        console.log(&quot;Updating &quot;+post.id+&quot; done!&quot;);
    }
    
    const updateUser = (post) =&gt; {
        console.log(&quot;Updating &quot;+post.id);
        fetch('https://jsonplaceholder.typicode.com/posts/'+post.id, {
        method: 'PUT',
        body: JSON.stringify(post),
            headers: {
              &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
            }
        })
        .then(response =&gt; response.json())
        .then(json =&gt; updating(json))
    }
    ...

    &lt;User key={contact.id} contact={contact} deleleUser={deleleUser} updateUser={updateUser}&gt;&lt;/User&gt;</code></pre>

    <p>In order to avoid UX complexity required we will implement the editable attribute in src/User.js and we will set some methods for data updating when blur happen.</p>
    <pre><code class="language-typescript">import React from &quot;react&quot;;

const User = ({contact, deleleUser, updateUser}) =&gt; {

    const drop = () =&gt; {
        deleleUser(contact.id)
    }

    const updateMail = (e) =&gt; {
        contact.email = e.target.innerHTML;
        updateUser(contact);
    }

    const updateName = (e) =&gt; {
        contact.name = e.target.innerHTML;
        updateUser(contact);
    }

    const updatePhone = (e) =&gt; {
        contact.phone = e.target.innerHTML;
        updateUser(contact);
    }

    return (&lt;div&gt;
                &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot; onBlur={updateMail}&gt;{contact.email}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Name: &lt;span contenteditable=&quot;true&quot; onBlur={updateName}&gt;{contact.name}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Phone: &lt;span contenteditable=&quot;true&quot; onBlur={updatePhone}&gt;{contact.phone}&lt;/span&gt;&lt;/span&gt;
                &lt;input type=&quot;button&quot; onClick={drop} value=&quot;Delete&quot;/&gt;
                &lt;hr/&gt;
            &lt;/div&gt;);
}

export default User;</code></pre>


<p>Test the application again.</p>







<br/>
<h2>Chapter 4: Add new User.</h2>
<p>Finally, we implement create method adding a button in src\App.js.</p>
<pre><code class="language-typescript">    if(typeof info === 'undefined'){
        return (&lt;div&gt;Loading...&lt;/div&gt;)
    } else {
        return (&lt;div&gt;
            {info.map((contact) =&gt; (
                &lt;User key={contact.id} contact={contact} deleleUser={deleleUser} updateUser={updateUser}&gt;&lt;/User&gt;
            ))}
            &lt;input type=&quot;button&quot; onClick={create} value=&quot;Create new  User&quot;/&gt;
            &lt;/div&gt;)
    }</code></pre>


    <p>And set the method create.</p>
    <pre><code class="language-typescript">    const create = () =&gt; {
        fetch('https://jsonplaceholder.typicode.com/posts', {
            method: 'POST',
            body: JSON.stringify({
                name: 'Jhon Doe',
                email: 'bar@email.com',
                phone: '444-555'
            }),
            headers: {
                &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
            }
        })
        .then(response =&gt; response.json())
        .then(json =&gt; {
            console.log(json);
            setInfo([...info, json]);
        })
    }</code></pre>


    <p>The final result is src\App.js.</p>
    <pre><code class="language-typescript">import React, { useState, useEffect } from &quot;react&quot;;
import User from './User'

function App() {

    const [info, setInfo] = useState();

    useEffect(() =&gt; { //same functionality of componentDidMount()
        fetch('http://jsonplaceholder.typicode.com/users')
        .then(res =&gt; res.json())
        .then((data) =&gt; {
            console.log(data);
            setInfo(data)
        })
        .catch(console.log)
    }, []);

    const deleting = (id) =&gt;{
        let tmp  = info.filter((a) =&gt; {
            return a.id !== id;
        })
        setInfo(tmp);
    }

    const deleleUser = (id) =&gt; {
        console.log(&quot;deleting &quot;+id);
            fetch('https://jsonplaceholder.typicode.com/posts/'+id, {
          method: 'DELETE'
        })
        .then(() =&gt; {
            deleting(id);
        })
    }

    const updating = (post) =&gt;{
        console.log(&quot;Updating &quot;+post.id+&quot; done!&quot;);
    }

    const updateUser = (post) =&gt; {
        console.log(&quot;Updating &quot;+post.id);
        fetch('https://jsonplaceholder.typicode.com/posts/'+post.id, {
        method: 'PUT',
        body: JSON.stringify(post),
            headers: {
              &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
            }
        })
        .then(response =&gt; response.json())
        .then(json =&gt; updating(json))
    }

    const create = () =&gt; {
        fetch('https://jsonplaceholder.typicode.com/posts', {
            method: 'POST',
            body: JSON.stringify({
                name: 'Jhon Doe',
                email: 'bar@email.com',
                phone: '444-555'
            }),
            headers: {
                &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
            }
        })
        .then(response =&gt; response.json())
        .then(json =&gt; {
            setInfo([...info, json]);
        })
    }

    if(typeof info === 'undefined'){
        return (&lt;div&gt;Loading...&lt;/div&gt;)
    } else {
        return (&lt;div&gt;
            {info.map((contact) =&gt; (
                &lt;User key={contact.id} contact={contact} deleleUser={deleleUser} updateUser={updateUser}&gt;&lt;/User&gt;
            ))}
            &lt;input type=&quot;button&quot; onClick={create} value=&quot;Create new  User&quot;/&gt;
            &lt;/div&gt;)
    }
}

export default App;
</code></pre>

    <p>And src\User.js.</p>
    <pre><code class="language-typescript">import React from &quot;react&quot;;

const User = ({contact, deleleUser, updateUser}) =&gt; {

    const drop = () =&gt; {
        deleleUser(contact.id)
    }

    const updateName = (e) =&gt; {
        contact.name = e.target.innerHTML
        updateUser(contact)
    }
    
    const updateMail = (e) =&gt; {
        contact.email = e.target.innerHTML
        updateUser(contact)
    }

    const updatePhone = (e) =&gt; {
        contact.phone = e.target.innerHTML
        updateUser(contact)
    }

    return (&lt;div&gt;
                &lt;span&gt;Name: &lt;span contenteditable=&quot;true&quot; onBlur={updateName}&gt;{contact.name}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot; onBlur={updateMail}&gt;{contact.email}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
                &lt;span&gt;Phone: &lt;span contenteditable=&quot;true&quot; onBlur={updatePhone}&gt;{contact.phone}&lt;/span&gt;&lt;/span&gt;
                &lt;input type=&quot;button&quot; onClick={drop} value=&quot;Delete&quot;/&gt;
                &lt;hr/&gt;
            &lt;/div&gt;);
}

export default User;
</code></pre>





</div>    
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
