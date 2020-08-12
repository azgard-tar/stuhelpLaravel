<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Reference</title>

    <link rel="stylesheet" href="{{ asset('/docs/css/style.css') }}" />
    <script src="{{ asset('/docs/js/all.js') }}"></script>


          <script>
        $(function() {
            setupLanguages(["bash","javascript","php"]);
        });
      </script>
      </head>

  <body class="">
    <a href="#" id="nav-button">
      <span>
        NAV
        <img src="/docs/images/navbar.png" />
      </span>
    </a>
    <div class="tocify-wrapper">
        <img src="/docs/images/logo.png" />
                    <div class="lang-selector">
                                  <a href="#" data-language-name="bash">bash</a>
                                  <a href="#" data-language-name="javascript">javascript</a>
                                  <a href="#" data-language-name="php">php</a>
                            </div>
                            <div class="search">
              <input type="text" class="search" id="input-search" placeholder="Search">
            </div>
            <ul class="search-results"></ul>
              <div id="toc">
      </div>
                    <ul class="toc-footer">
                                  <li><a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a></li>
                            </ul>
            </div>
    <div class="page-wrapper">
      <div class="dark-box"></div>
      <div class="content">
          <!-- START_INFO -->
<h1>Info</h1>
<p>Welcome to the generated API reference.
<a href="stuhelp.site/docs/collection.json">Get Postman Collection</a></p>
<!-- END_INFO -->
<h1>User management</h1>
<p>APIs for managing users</p>
<!-- START_7fd06262162ae5814517807941ee6940 -->
<h2>Login with email and password</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "stuhelp.site/api/auth/login?email=labore&amp;password=quisquam" </code></pre>
<pre><code class="language-javascript">const url = new URL(
    "stuhelp.site/api/auth/login"
);

let params = {
    "email": "labore",
    "password": "quisquam",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

fetch(url, {
    method: "GET",
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'stuhelp.site/api/auth/login',
    [
        'query' =&gt; [
            'email'=&gt; 'labore',
            'password'=&gt; 'quisquam',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "error": "Unauthorized"
}</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "access_token": "token",
    "token_type": "bearer",
    "expires_in": 3600
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/auth/login</code></p>
<h4>Query Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>email</code></td>
<td>required</td>
<td>The email of the user</td>
</tr>
<tr>
<td><code>password</code></td>
<td>required</td>
<td>The password of the user</td>
</tr>
</tbody>
</table>
<!-- END_7fd06262162ae5814517807941ee6940 -->
<!-- START_c0db2b8b57bbadad9ffd18107c81e5d5 -->
<h2>Login with authorization header</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "stuhelp.site/api/auth/login123" \
    -H "Content-Type: application/json" \
    -d '{"Authorization":"Bearer asdasd"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "stuhelp.site/api/auth/login123"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "Authorization": "Bearer asdasd"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'stuhelp.site/api/auth/login123',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'Authorization' =&gt; 'Bearer asdasd',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "error": "Unauthorized"
}</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "access_token": "token",
    "token_type": "bearer",
    "expires_in": 3600
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/auth/login123</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>Authorization</code></td>
<td>required</td>
<td>optional</td>
<td>Basic base64_encode.</td>
</tr>
</tbody>
</table>
<!-- END_c0db2b8b57bbadad9ffd18107c81e5d5 -->
<!-- START_ade88ee476755a9706337cdabd78339b -->
<h2>Registration user</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "stuhelp.site/api/auth/registration?login=et&amp;email=enim&amp;password=repellendus" </code></pre>
<pre><code class="language-javascript">const url = new URL(
    "stuhelp.site/api/auth/registration"
);

let params = {
    "login": "et",
    "email": "enim",
    "password": "repellendus",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

fetch(url, {
    method: "POST",
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'stuhelp.site/api/auth/registration',
    [
        'query' =&gt; [
            'login'=&gt; 'et',
            'email'=&gt; 'enim',
            'password'=&gt; 'repellendus',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Successfully registration!"
}</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "error": "Text of the error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/registration</code></p>
<h4>Query Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>login</code></td>
<td>required</td>
<td>The login of the user. Max length: 64, must be unique</td>
</tr>
<tr>
<td><code>email</code></td>
<td>required</td>
<td>The email of the user</td>
</tr>
<tr>
<td><code>password</code></td>
<td>required</td>
<td>The password of the user. Rules: min 6 in length, must contain at least one lowercase letter, at least one uppercase letter, at least one digit, a special character</td>
</tr>
</tbody>
</table>
<!-- END_ade88ee476755a9706337cdabd78339b -->
<h1>general</h1>
<!-- START_180a782ae430dbfaee2698fcec558d04 -->
<h2>api/.json</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "stuhelp.site/api/.json" </code></pre>
<pre><code class="language-javascript">const url = new URL(
    "stuhelp.site/api/.json"
);

fetch(url, {
    method: "GET",
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get('stuhelp.site/api/.json');
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/.json</code></p>
<!-- END_180a782ae430dbfaee2698fcec558d04 -->
      </div>
      <div class="dark-box">
                        <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                                    <a href="#" data-language-name="javascript">javascript</a>
                                    <a href="#" data-language-name="php">php</a>
                              </div>
                </div>
    </div>
  </body>
</html>