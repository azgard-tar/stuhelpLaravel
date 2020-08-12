---
title: API Reference

language_tabs:
- bash
- javascript
- php

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](stuhelp.site/docs/collection.json)

<!-- END_INFO -->

#User management


APIs for managing users
<!-- START_7fd06262162ae5814517807941ee6940 -->
## Login with email and password

> Example request:

```bash
curl -X GET \
    -G "stuhelp.site/api/auth/login?email=labore&password=quisquam" 
```

```javascript
const url = new URL(
    "stuhelp.site/api/auth/login"
);

let params = {
    "email": "labore",
    "password": "quisquam",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'stuhelp.site/api/auth/login',
    [
        'query' => [
            'email'=> 'labore',
            'password'=> 'quisquam',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (401):

```json
{
    "error": "Unauthorized"
}
```
> Example response (200):

```json
{
    "access_token": "token",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### HTTP Request
`GET api/auth/login`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `email` |  required  | The email of the user
    `password` |  required  | The password of the user

<!-- END_7fd06262162ae5814517807941ee6940 -->

<!-- START_c0db2b8b57bbadad9ffd18107c81e5d5 -->
## Login with authorization header

> Example request:

```bash
curl -X GET \
    -G "stuhelp.site/api/auth/login123" \
    -H "Content-Type: application/json" \
    -d '{"Authorization":"Bearer asdasd"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'stuhelp.site/api/auth/login123',
    [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'Authorization' => 'Bearer asdasd',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (401):

```json
{
    "error": "Unauthorized"
}
```
> Example response (200):

```json
{
    "access_token": "token",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### HTTP Request
`GET api/auth/login123`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `Authorization` | required |  optional  | Basic base64_encode.
    
<!-- END_c0db2b8b57bbadad9ffd18107c81e5d5 -->

<!-- START_ade88ee476755a9706337cdabd78339b -->
## Registration user

> Example request:

```bash
curl -X POST \
    "stuhelp.site/api/auth/registration?login=et&email=enim&password=repellendus" 
```

```javascript
const url = new URL(
    "stuhelp.site/api/auth/registration"
);

let params = {
    "login": "et",
    "email": "enim",
    "password": "repellendus",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'stuhelp.site/api/auth/registration',
    [
        'query' => [
            'login'=> 'et',
            'email'=> 'enim',
            'password'=> 'repellendus',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "message": "Successfully registration!"
}
```
> Example response (401):

```json
{
    "error": "Text of the error"
}
```

### HTTP Request
`POST api/auth/registration`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `login` |  required  | The login of the user. Max length: 64, must be unique
    `email` |  required  | The email of the user
    `password` |  required  | The password of the user. Rules: min 6 in length, must contain at least one lowercase letter, at least one uppercase letter, at least one digit, a special character

<!-- END_ade88ee476755a9706337cdabd78339b -->

#general


<!-- START_180a782ae430dbfaee2698fcec558d04 -->
## api/.json
> Example request:

```bash
curl -X GET \
    -G "stuhelp.site/api/.json" 
```

```javascript
const url = new URL(
    "stuhelp.site/api/.json"
);


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get('stuhelp.site/api/.json');
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET api/.json`


<!-- END_180a782ae430dbfaee2698fcec558d04 -->


