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

    <h1>Vue.js</h1>

    <p>Create an index.html file and load the cdn to load Vue.js library.</p>
    <pre><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1&quot;&gt;
    &lt;title&gt;Vue - Tutorial&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div id=&quot;app&quot;&gt;
        ...
    &lt;/div&gt;
&lt;/body&gt;
&lt;script src=&quot;https://cdn.jsdelivr.net/npm/vue/dist/vue.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;usercomponent.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;index.js&quot;&gt;&lt;/script&gt;
&lt;/html&gt;</code></pre>
    
    
    


    <p>Create an index.js file where we make the request to the server.</p>
    <pre><code class="language-javascript">new Vue({
    el: '#app',
    data: {
        users: []
    },
    mounted() {
        fetch('http://jsonplaceholder.typicode.com/users')
        .then(res =&gt; res.json())
        .then((data) =&gt; {
            console.log(data);
            this.users = data;
        })
        .catch(console.log)
    }
});</code></pre>

    <p>Create an usercomponent.js to render the users.</p>
    <pre><code class="language-javascript">Vue.component('usercomponent', {
    template: '#user-template'
});</code></pre>

    <p>We need to create a template which contains 'user-template' as id. We can write it below of #app container in index.html</p>
    <pre><code class="language-html">&lt;div id=&quot;app&quot;&gt;
    ...
&lt;/div&gt;

&lt;script type=&quot;text/x-template&quot; id=&quot;user-template&quot;&gt;
    &lt;div&gt;
        &lt;span&gt;Name: @{{userprop.name}}&lt;br/&gt;
        &lt;span&gt;Email: @{{userprop.email}}&lt;/span&gt;&lt;br/&gt;
        &lt;span&gt;Phone: @{{userprop.phone}}&lt;/span&gt;
        &lt;hr/&gt;
    &lt;/div&gt;
&lt;/script&gt;</code></pre>


    <p>We can make one or more instances ot this component, but only inside of #app container in index.html</p>
    <pre><code class="language-html">&lt;div id=&quot;app&quot;&gt;
    &lt;usercomponent v-for=&quot;user in users&quot; :userprop=&quot;user&quot; v-bind:key=&quot;user.id&quot;&gt;&lt;/usercomponent&gt;
&lt;/div&gt;</code></pre>

    <p>The variable 'users' have to be into the scope of #app container.</p>
    <p>The prop 'userprop' has to be enable in the component in usercomponent.js</p>
    <pre><code class="language-javascript">Vue.component('usercomponent', {
    template: '#user-template',
    props: ['userprop'],
});</code></pre>
    <p>You can test the application now.</p>











    <p>To delete an user we can add a buttom into the template.</p>
    <pre><code class="language-html">&lt;input type=&quot;button&quot; v-on:click='drop(userprop.id)' value=&quot;Delete&quot;/&gt;</code></pre>
    
    <p>We need also to create a method drop into the usercomponent.js</p>
    <pre><code class="language-javascript">Vue.component('usercomponent', {
    template: '#user-template',
    props: ['userprop'],
    methods: {
        drop(id) {
            this.$emit('delete-user-event', id);
        }
    }
});</code></pre>

    <p>With the $emit method we can access to a method of the parent of the current component. See index.html.</p>
    <pre><code class="language-html">&lt;div id=&quot;app&quot;&gt;
    &lt;usercomponent v-for=&quot;user in users&quot; :userprop=&quot;user&quot; 
    v-bind:key=&quot;user.id&quot; @delete-user-event=&quot;deleteUser&quot; &gt;&lt;/usercomponent&gt;
&lt;/div&gt;</code></pre>

    <p>Now, we have to add deleteUser method in the #app container.</p>
    <pre><code class="language-javascript">new Vue({
    el: '#app',
    data: {
        users: []
    },
    mounted() {
        ...
    },
    methods: {
        deleteUser(id) {
            fetch('https://jsonplaceholder.typicode.com/users/' + id, {
                method: 'DELETE'
            })
            .then(() =&gt; {
                console.log('deleted');
                let tmp = this.users.filter((a) =&gt; {
                    return a.id !== id;
                })
                this.users = tmp;
            })
        }
    }
});</code></pre>


    <p>Now you can test the removal functionality.</p>



    <p>To update the user we will avoid UX requirements and we will use the attribute contenteditable with true value.</p>
    <p>And we implement blur event to trigger the updates.</p>
    <pre><code class="language-html">&lt;script type=&quot;text/x-template&quot; id=&quot;user-template&quot;&gt;
    &lt;div&gt;
        &lt;span&gt;Name:  &lt;span contenteditable=&quot;true&quot;
        v-on:blur=&quot;update($event, 'name', userprop)&quot;&gt;@{{userprop.name}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
        &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot;
        v-on:blur=&quot;update($event, 'email', userprop)&quot;&gt;@{{userprop.email}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
        &lt;span&gt;Phone:  &lt;span contenteditable=&quot;true&quot;
        v-on:blur=&quot;update($event, 'phone', userprop)&quot;&gt;@{{userprop.phone}}&lt;/span&gt;&lt;/span&gt;
        &lt;input type=&quot;button&quot; v-on:click='drop(userprop.id)' value=&quot;Delete&quot;/&gt;
        &lt;hr/&gt;
    &lt;/div&gt;
&lt;/script&gt;</code></pre>


    <p>Now we have to implement the method update in the component.</p>
    <pre><code class="language-javascript">Vue.component('usercomponent', {
    template: '#user-template',
    props: ['userprop'],
    methods: {
        ...
        update(obj, field, user) {
            console.log(obj.target.innerHTML);
            user[field] = obj.target.innerHTML;
            this.$emit('update-user-event', user);
        }
    }
});</code></pre>

<p>We have to use $emit method to achieve the method of the parent #app</p>
<pre><code class="language-html">&lt;usercomponent 
    v-for=&quot;user in users&quot;
    :userprop=&quot;user&quot;
    v-bind:key=&quot;user.id&quot;
    @delete-user-event=&quot;deleleUser&quot;
    @update-user-event=&quot;updateUser&quot;&gt;
&lt;/usercomponent&gt;</code></pre>


    <p>Then, we need to make a request in the main component into #app in the method updateUser.</p>
    <pre><code class="language-javascript">new Vue({
    el: '#app',
    data: {
        users: []
    },
    mounted() {
        ...
    },
    methods: {
        ...
        updateUser(post) {
            console.log(&quot;Updating &quot; + post.id);
            fetch('https://jsonplaceholder.typicode.com/users/' + post.id, {
                method: 'PUT',
                body: JSON.stringify(post),
                headers: {
                    &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
                }
            })
            .then(response =&gt; response.json())
            .then(json =&gt; console.log(json))
        }
    }
});</code></pre>

    <p>You can test the update functionality now.</p>



















    <p>Finaly, we can add a button to create a new user placing it just below the usercomponent, but into #app container.</p>
    <pre><code class="language-html">&lt;input type=&quot;button&quot; v-on:click='create()' value=&quot;Create new  User&quot;/&gt;</code></pre>

    <p>And implement the method to make a request which generate a new user.</p>
    <pre><code class="language-javascript">new Vue({
    el: '#app',
    data: {
        users: []
    },
    mounted() {
        ...
    },
    methods: {
        ...
        create() {
            console.log('creating user');
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
                console.log(json)
                this.users.push(json);
            })
        }
    }
});</code></pre>













    <p>The final source are the following.</p>
    <p>index.html.</p>
    <pre><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;

&lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1&quot;&gt;
    &lt;title&gt;Vue -Tutorial&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;div id=&quot;app&quot;&gt;
        &lt;usercomponent 
            v-for=&quot;user in users&quot; 
            :userprop=&quot;user&quot; 
            v-bind:key=&quot;user.id&quot; 
            @delete-user-event=&quot;deleleUser&quot; 
            @update-user-event=&quot;updateUser&quot;&gt;
        &lt;/usercomponent&gt;
        &lt;input type=&quot;button&quot; v-on:click='create()' value=&quot;Create new  User&quot; /&gt;
    &lt;/div&gt;


    &lt;script type=&quot;text/x-template&quot; id=&quot;user-template&quot;&gt;
        &lt;div&gt;
            &lt;span&gt;Name:  &lt;span contenteditable=&quot;true&quot;
            v-on:blur=&quot;update($event, 'name', userprop)&quot;&gt;@{{userprop.name}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
            &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot;
            v-on:blur=&quot;update($event, 'email', userprop)&quot;&gt;@{{userprop.email}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
            &lt;span&gt;Phone:  &lt;span contenteditable=&quot;true&quot;
            v-on:blur=&quot;update($event, 'phone', userprop)&quot;&gt;@{{userprop.phone}}&lt;/span&gt;&lt;/span&gt;
            &lt;input type=&quot;button&quot; v-on:click='drop(userprop.id)' value=&quot;Delete&quot;/&gt;
            &lt;hr/&gt;
        &lt;/div&gt;
    &lt;/script&gt;

&lt;/body&gt;
&lt;script src=&quot;https://cdn.jsdelivr.net/npm/vue/dist/vue.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;usercomponent.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;index.js&quot;&gt;&lt;/script&gt;

&lt;/html&gt;</code></pre>

    <p>usercomponent.js.</p>
    <pre><code class="language-javascript">Vue.component('usercomponent', {
    template: '#user-template',
    props: ['userprop'],
    methods: {
        drop(id) {
            this.$emit('delete-user-event', id);
        },
        update(obj, field, user) {
            console.log(obj.target.innerHTML);
            user[field] = obj.target.innerHTML;
            this.$emit('update-user-event', user);
        }
    }
});</code></pre>


    <p>index.js.</p>
    <pre><code class="language-javascript">new Vue({
    el: '#app',
    data: {
        users: []
    },
    mounted() {
        fetch('http://jsonplaceholder.typicode.com/users')
        .then(res =&gt; res.json())
        .then((data) =&gt; {
            console.log(data);
            this.users = data;
        })
        .catch(console.log)
    },
    methods: {
        deleleUser(id) {
            fetch('https://jsonplaceholder.typicode.com/users/' + id, {
                method: 'DELETE'
            })
            .then(() =&gt; {
                console.log('deleted');
                let tmp = this.users.filter((a) =&gt; {
                    return a.id !== id;
                })
                this.users = tmp;
            })
        },
        updateUser(post) {
            console.log(&quot;Updating &quot; + post.id);
            fetch('https://jsonplaceholder.typicode.com/users/' + post.id, {
                method: 'PUT',
                body: JSON.stringify(post),
                headers: {
                    &quot;Content-type&quot;: &quot;application/json; charset=UTF-8&quot;
                }
            })
            .then(response =&gt; response.json())
            .then(json =&gt; console.log(json))
        },
        create() {
            console.log('creating user');
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
                console.log(json)
                this.users.push(json);
            })
        }
    }
});</code></pre>








</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
