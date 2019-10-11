@extends('general.layout')


@section('content')
<div class="content">
    <h1>SSH Key</h1>

    <p>If you have unlees two external repositories, you may want to have two easy ways to autenticate yourself using SSH-key.</p>
    <p>First of all, check the folder .ssh located in your user folder (/home/user_name/.ssh).</p>
    <pre><code class="language-bash">$ ls ~/.ssh
id_rsa id_rsa.pub  known_hosts</code></pre>

<p>You can create a ssh-key with the followin command.</p>
<pre><code class="language-bash">$ ssh-keygen -t rsa -C "your_name@home_email.com"</code></pre>

<p>Enter unique name, for example "id_rsa_home". Your passphrase can be empty.</p>

<p>Now, create a "config" file for organise these keys in that folder.</p>
<pre><code class="language-bash"># old account
Host github.com
HostName github.com
PreferredAuthentications publickey
IdentityFile ~/.ssh/id_rsa

# new account
Host home.github.com
HostName github.com
PreferredAuthentications publickey
IdentityFile ~/.ssh/id_rsa_home</code></pre>

<p>Next you'll delete cached keys.</p>
<pre><code class="language-bash">$ ssh-add -D</code></pre>

<p>Then run the ssh-agent.</p>
<pre><code class="language-bash">$ eval `ssh-agent -s`</code></pre>


<p>Then, add yours key.</p>
<pre><code class="language-bash">$ ssh-add ~/.ssh/id_rsa
$ ssh-add ~/.ssh/id_rsa_home</code></pre>

<p>Then, list your keys.</p>
<pre><code class="language-bash">$ ssh-add -l
2048 7a:32:06:3f:3d:6c:f4:a1:d4:65:13:64:a4:ed:1d:63 /home/user/.ssh/id_rsa (RSA)
2048 d4:e0:39:e1:bf:6f:e3:26:14:6b:26:73:4e:b4:53:83 /home/user/.ssh/id_rsa_home (RSA)</code></pre>

<p>If you are conected in github, you can test the connection.</p>
<pre><code  class="language-bash">ssh -T git@home.github.com
Hi home_user! You've successfully authenticated, but GitHub does not provide shell access.</code></pre>

<p>By the way. In yout github account in sentings you can find the way to add the key you created to your github account.</p>

<p>To copy the public key you can use</p>
<pre><code class="language-bash">$ cat ~/.ssh/id_rsa_home.pub</code></pre>







</div>
@endsection
