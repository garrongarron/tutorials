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
    <h1>Angular</h1>

    <p>Install the Angular CLI</p>
    <pre><code class="language-css">npm install -g @angular/cli</code></pre>
    
    <p>Create a project</p>
    <pre><code class="language-css">ng new angular-tutorial</code></pre>

    <p>Say "no routing"</p>
    <pre><code class="language-css">Would you like to add Angular routing? (y/N)</code></pre>

    <p>Choose "CSS"</p>
    <pre><code class="language-css">❯ CSS 
  SCSS   [ https://sass-lang.com/documentation/syntax#scss                ] 
  Sass   [ https://sass-lang.com/documentation/syntax#the-indented-syntax ] 
  Less   [ http://lesscss.org                                             ] 
  Stylus [ http://stylus-lang.com                                         ] </code></pre>

    <p>Go to the project folder</p>
    <pre><code class="language-css">cd angular-tutorial</code></pre>

    <p>Run the local server</p>
    <pre><code class="language-css">ng serve -o</code></pre>

    <p>Create a service named Api</p>
    <pre><code class="language-typescript">ng generate service Api</code></pre>

    <p>Import the service ApiService created in src/app/app.modules.js and add it as a provider.</p>
    <pre><code class="language-typescript">...
import { ApiService } from './api.service';
...
@NgModule({
  ...
  providers: [ApiService],
  ...
})</code></pre>

    <p>Import HttpClient in src/app/api.service.js</p>
    <pre><code class="language-typescript">...
import { HttpClient }    from '@angular/common/http';</code></pre>

    <p>Import the module HttpClientModule in src/app/app.modules.js</p>
    <pre><code class="language-typescript">...
import { HttpClientModule }    from '@angular/common/http';
...
@NgModule({
  ...
  imports: [
    ...
    HttpClientModule
  ],
  ...
})</code></pre>
  
    <p>Create a private attribute in the constructor method in src/app/app.modules.js and a new method named getUsers.</p>
    <pre><code class="language-typescript">constructor(private http: HttpClient ) { }
    
getUsers(){
    return this.http.get('https://jsonplaceholder.typicode.com/users/');
}</code></pre>

    <p>The method get of the object HttpClient return an observable object. This observable is waiting for an asynchronous response.</p>
    <p>When the answer arrive, it execute one or several callback functions. These callbacks are created calling the subscribe method.</p>
    <p>We will do it in the component src/app/app.component.js, but first, we  have to  import the service.</p>
    <pre><code class="language-typescript">import { ApiService } from './api.service';</code></pre>    

    <p>We need a property which contain the data to be rendered into the template.</p>
    <pre><code class="language-typescript">contacts:any = [];</code></pre>    


    <p>We create a private attribute which will contain the service.</p>
    <pre><code class="language-typescript">constructor(private api: ApiService) { }</code></pre>

    <p>And finally, implement the method ngOnInit with the subscribe method from the observable mentioned before. In this way we creating our own callback function.</p>
    <pre><code class="language-typescript">ngOnInit() {
    this.api.getUsers()
    .subscribe(data => {
        this.contacts = data;
        console.log(data)
    });
}</code></pre>


    <p>Now, we are ready to implement our template in src/app/app.component.html</p>
    <pre><code class="language-html">&lt;div *ngFor=&quot;let contact of contacts&quot;&gt;
    &lt;span&gt;Name: @{{contact.name}}&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Email: @{{contact.email}}&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Phone: @{{contact.phone}}&lt;/span&gt;
    &lt;hr/&gt;
&lt;/div&gt;</code></pre>

    <p>You can test the application now.</p>






    <p>To delete a user we need to add a buttom in the template src/app/app.component.html.</p>
    <pre><code class="language-html">&lt;input type=&quot;button&quot; (click)=&quot;drop(contact.id)&quot; value=&quot;Delete&quot;/&gt;</code></pre>

    <p>We have to implement drop method in the component src/app/app.component.js.</p>
    <pre><code class="language-typescript">...
deleting = (id) =&gt;{
    let tmp  = this.contacts.filter((a) =&gt; {
        return a.id !== id;
    })
    this.contacts = tmp;
}

drop = (id) =&gt; {
    this.api.deleteUsers(id)
    .subscribe(data =&gt; {
        this.deleting(id)
    });
}
...</code></pre>

    <p>When the property this.contacts is updated the template is rendered again automatically.</p>
    <p>We need to implement the request deleteUser method into the service  in src/app/api.service.js</p>
    <pre><code class="language-typescript">...
deleteUsers(id){
    return this.http.delete('https://jsonplaceholder.typicode.com/users/'+id);
}
...</code></pre>

    <p>You can test deleting functionality now.</p>
    







    <p>For update we will avoid UX requirements and we will make it easy using the attribute named contenteditable.</p>
    <p>We also will set id (template reference) to some elements of template adding the sign # which is something that angular allow us to do.</p>
    <p>They are #name, #email and #phone and we used them as first parameter in update methods.</p>
    <pre><code class="language-html">&lt;div *ngFor=&quot;let contact of contacts&quot;&gt;
    &lt;span&gt;Name: &lt;span contenteditable=&quot;true&quot; 
    #name (blur)='update(name, &quot;name&quot;, contact)'&gt;@{{contact.name}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot; 
    #mail (blur)='update(mail, &quot;mail&quot;, contact)'&gt;@{{contact.email}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Phone: &lt;span contenteditable=&quot;true&quot; 
    #phone (blur)='update(phone, &quot;phone&quot;, contact)'&gt;@{{contact.phone}}&lt;/span&gt;&lt;/span&gt;
    &lt;input type=&quot;button&quot; (click)=&quot;drop(contact.id)&quot; value=&quot;Delete&quot;/&gt;
    &lt;hr/&gt;
&lt;/div&gt;</code></pre>

    <p>Then, we have to implement the update method in the component src/app/app.component.js.</p>
    <pre><code class="language-typescript">...
update = (element, field, post) =&gt; {
    post[field] = element.innerHTML;
    this.api.updateUser(post)
    .subscribe(data =&gt; {
        console.log(data)
    });
}
...</code></pre>

    <p>We can receive directly the value instead of element.innerHTML, but in this way you can realize that here element is and DOM object.</p>
    <p>Now, in the service (src/app/api.service.js) we need to import HttpHeaders class to add headers to our http request.</p>
    <pre><code class="language-typescript">import { HttpClient, HttpHeaders } from '@angular/common/http';</code></pre>

    <p>And Implement the request into updateUser method.</p>
    <pre><code class="language-typescript">...
httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json; charset=UTF-8' })
};

updateUser(post) {
    return this.http.put(
        'https://jsonplaceholder.typicode.com/posts/'+post.id,
        post,
        this.httpOptions
    );
}
...</code></pre>

    <p>You can test update functionality now.</p>
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    <p>Finally, we implement create method adding it in a bottom of the template.</p>
    <pre><code class="language-typescript">&lt;input type=&quot;button&quot; (click)='create()' value=&quot;Create new  User&quot;/&gt;</code></pre>
    
    <p>We need to implement the cerate method in the component src/app/app.component.js.</p>
    <pre><code class="language-typescript">...
create = () =&gt; {
    console.log('creating');
    this.api.createUser({
            name: 'Jhon Doe',
            email: 'bar@email.com',
            phone: '444-555'
        })
    .subscribe(data =&gt; {
        console.log(data)
        this.contacts.push(data);
    });
}
...</code></pre>


    <p>We need to implement the createUser method in the serive src/app/api.service.js.</p>
    <p>We assume we already set up the property this.httpOptions in previous steps.</p>
    <pre><code class="language-typescript">...
createUser(post) {
    return this.http.post(
        'https://jsonplaceholder.typicode.com/posts/',
        post,
        this.httpOptions
    );
}
...</code></pre>


    <p>You can test create functionality now.</p>



    <p>Final code you have to have is the following:</p>
    <p>src/app/app.module.ts</p>
    <pre><code class="language-typescript">import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { ApiService } from './api.service';
import { HttpClientModule }    from '@angular/common/http';

@NgModule({
declarations: [
    AppComponent
],
imports: [
    BrowserModule,
    HttpClientModule
],
providers: [ApiService],
bootstrap: [AppComponent]
})
export class AppModule { }</code></pre>

    <p>src/app/app.component.ts</p>
    <pre><code class="language-typescript">import { Component } from '@angular/core';
import { ApiService } from './api.service';

@C{{''}}omponent({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})

export class AppComponent {

    title = 'angular-tutorial';
    contacts:any = [];

    constructor(private api: ApiService) { }

    deleting = (id) =&gt;{
        let tmp  = this.contacts.filter((a) =&gt; {
            return a.id !== id;
        })
        this.contacts = tmp;
    }

    drop = (id) =&gt; {
        this.api.deleteUsers(id)
        .subscribe(data =&gt; {
            this.deleting(id)
        });
    }

    update = (element, field, post) =&gt; {
        post[field] = element.innerHTML;
        this.api.updateUser(post)
        .subscribe(data =&gt; {
            console.log(data)
        });
    }

    create = () =&gt; {
        console.log('creating');
        this.api.createUser({
                name: 'Jhon Doe',
                email: 'bar@email.com',
                phone: '444-555'
            })
        .subscribe(data =&gt; {
            console.log(data)
            this.contacts.push(data);
        });
    }
    
    ngOnInit() {
        this.api.getUsers()
        .subscribe(data =&gt; {
            this.contacts = data;
            console.log(data)
        });
    }
}</code></pre>

    <p>src/app/app.component.html</p>
    <pre><code class="language-html">&lt;div *ngFor=&quot;let contact of contacts&quot;&gt;
    &lt;span&gt;Name: &lt;span contenteditable=&quot;true&quot;
    #name (blur)='update(name, &quot;name&quot;, contact)'&gt;@{{contact.name}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Email: &lt;span contenteditable=&quot;true&quot;
    #mail (blur)='update(mail, &quot;mail&quot;, contact)'&gt;@{{contact.email}}&lt;/span&gt;&lt;/span&gt;&lt;br/&gt;
    &lt;span&gt;Phone: &lt;span contenteditable=&quot;true&quot;
    #phone (blur)='update(phone, &quot;phone&quot;, contact)'&gt;@{{contact.phone}}&lt;/span&gt;&lt;/span&gt;
    &lt;input type=&quot;button&quot; (click)=&quot;drop(contact.id)&quot; value=&quot;Delete&quot;/&gt;
    &lt;hr/&gt;
&lt;/div&gt;
&lt;input type=&quot;button&quot; (click)='create()' value=&quot;Create new  User&quot;/&gt;</code></pre>













</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/prism.js')}}"></script>
@endsection
