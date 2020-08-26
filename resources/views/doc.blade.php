<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ url('/css/app.css') }}">
</head>
<body>

    @foreach( $data as $func )
    <div class="jumbotron">
        <h1 class="display-4">{{ $func["url"] }}
        @switch($func["method"])
            @case("GET")
                <span class="badge badge-info">GET</span>
                @break
            @case("POST")
                <span class="badge badge-success">POST</span>
                @break
            @case("PUT")
                <span class="badge badge-warning">PUT</span>
                @break
            @case("DELETE")
                <span class="badge badge-danger">DELETE</span>
                @break
        @endswitch
        </h1>
        <p class="lead">{{ $func["description"] }}</p>
        <hr class="my-4">
        <pre>
            <div>Параметры url:      <br> <span class = "json">{{ json_encode( $func["urlParam"] ?? 'нету', JSON_UNESCAPED_UNICODE )   }}</span></div>
            <div>Параметры запроса:  <br> <span class = "json">{{ json_encode( $func["queryParam"] ?? 'нету', JSON_UNESCAPED_UNICODE ) }}</span></div>
            <div>Параметры тела:     <br> <span class = "json">{{ json_encode( $func["bodyParam"] ?? 'нету', JSON_UNESCAPED_UNICODE )  }}</span></div>
            <div>Ответ:              <br> <span class = "json">{{ json_encode( $func["response"] ?? 'нету', JSON_UNESCAPED_UNICODE )   }}</span></div>
        </pre>
    </div>
    @endforeach
    <script>
        var list = document.getElementsByClassName('json');
        for( let element of list )
        {
            element.innerHTML = 
                JSON.stringify(
                    JSON.parse(
                        element.innerHTML
                    ), undefined, 4
                );
        }
    </script>
    <script src="{{ url('/js/app.js') }}"></script>
</body>
</html>