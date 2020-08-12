<?php

return [
    /*
     * The type of documentation output to generate.
     * - "static" will generate a static HTMl page in the /public/docs folder,
     * - "laravel" will generate the documentation as a Blade view,
     * so you can add routing and authentication.
     */
    'type' => 'laravel', // --

    /*
     * Static output folder: HTML documentation and assets will be generated in this folder.
     */
   'output_folder' => 'public/docs',

    /*
     * Settings for `laravel` type output.
     */
    'laravel' => [
        /*
         * Whether to automatically create a docs endpoint for you to view your generated docs.
         * If this is false, you can still set up routing manually.
         */
        'autoload' => true, // --

        /*
         * URL path to use for the docs endpoint (if `autoload` is true).
         *
         * By default, `/doc` opens the HTML page, and `/doc.json` downloads the Postman collection.
         */
        'docs_url' => '/api/',

        /*
         * Middleware to attach to the docs endpoint (if `autoload` is true).
         */
        'middleware' => [],
    ],

    /*
     * The router to be used (Laravel or Dingo).
     */
    'router' => 'laravel',

    /*
     * The storage to be used when generating assets.
     * By default, uses 'local'. If you are using Laravel Vapor, please use S3 and make sure
     * the correct bucket is correctly configured in the .env file
     */
    'storage' => 'local',

    /*
     * The base URL to be used in examples and the Postman collection.
     * By default, this will be the value of config('app.url').
     */
    'base_url' => 'stuhelp.site',

    /*
     * Generate a Postman collection in addition to HTML docs.
     * For 'static' docs, the collection will be generated to public/docs/collection.json.
     * For 'laravel' docs, it will be generated to storage/app/apidoc/collection.json.
     * The `ApiDoc::routes()` helper will add routes for both the HTML and the Postman collection.
     */
    'postman' => [
        /*
         * Specify whether the Postman collection should be generated.
         */
        'enabled' => true,

        /*
         * The name for the exported Postman collection. Default: config('app.name')." API"
         */
        'name' => 'Stuhelp API', // --

        /*
         * The description for the exported Postman collection.
         */
        'description' => null,

        /*
         * The "Auth" section that should appear in the postman collection. See the schema docs for more information:
         * https://schema.getpostman.com/json/collection/v2.0.0/docs/index.html
         */
        'auth' => null,
    ],

    /*
     * The routes for which documentation should be generated.
     * Each group contains rules defining which routes should be included ('match', 'include' and 'exclude' sections)
     * and rules which should be applied to them ('apply' section).
     */
    'routes' => [
        [
            'match' => [
                'domains' => [
                    '*',
                ],

                'prefixes' => [
                    '*',
                ],
            ],

            'include' => [

            ],

            'apply' => [
                'headers' => [
                    // 'Authorization' => 'Bearer {token}',
                ],
            ],
        ],
    ],

    'strategies' => [
        'metadata' => [
            \Mpociot\ApiDoc\Extracting\Strategies\Metadata\GetFromDocBlocks::class,
        ],
        'urlParameters' => [
            \Mpociot\ApiDoc\Extracting\Strategies\UrlParameters\GetFromUrlParamTag::class,
        ],
        'queryParameters' => [
            \Mpociot\ApiDoc\Extracting\Strategies\QueryParameters\GetFromQueryParamTag::class,
        ],
        'headers' => [
            \Mpociot\ApiDoc\Extracting\Strategies\RequestHeaders\GetFromRouteRules::class,
        ],
        'bodyParameters' => [
            \Mpociot\ApiDoc\Extracting\Strategies\BodyParameters\GetFromBodyParamTag::class,
        ],
        'responses' => [
            \Mpociot\ApiDoc\Extracting\Strategies\Responses\UseTransformerTags::class,
            \Mpociot\ApiDoc\Extracting\Strategies\Responses\UseResponseTag::class,
            \Mpociot\ApiDoc\Extracting\Strategies\Responses\UseResponseFileTag::class,
            \Mpociot\ApiDoc\Extracting\Strategies\Responses\UseApiResourceTags::class,
            \Mpociot\ApiDoc\Extracting\Strategies\Responses\ResponseCalls::class,
        ],
    ],

    /*
     * Custom logo path. The logo will be copied from this location
     * during the generate process. Set this to false to use the default logo.
     *
     * Change to an absolute path to use your custom logo. For example:
     * 'logo' => resource_path('views') . '/api/logo.png'
     *
     * If you want to use this, please be aware of the following rules:
     * - the image size must be 230 x 52
     */
    'logo' => false,

    /*
     * Name for the group of routes which do not have a @group set.
     */
    'default_group' => 'general',

    /*
     * Example requests for each endpoint will be shown in each of these languages.
     * Supported options are: bash, javascript, php, python
     * You can add a language of your own, but you must publish the package's views
     * and define a corresponding view for it in the partials/example-requests directory.
     * See https://laravel-apidoc-generator.readthedocs.io/en/latest/generating-documentation.html
     *
     */
    'example_languages' => [
        'bash',
        'javascript',
        'php',
    ],

    /*
     * Configure how responses are transformed using @transformer and @transformerCollection
     * Requires league/fractal package: composer require league/fractal
     *
     */
    'fractal' => [
        /* If you are using a custom serializer with league/fractal,
         * you can specify it here.
         *
         * Serializers included with league/fractal:
         * - \League\Fractal\Serializer\ArraySerializer::class
         * - \League\Fractal\Serializer\DataArraySerializer::class
         * - \League\Fractal\Serializer\JsonApiSerializer::class
         *
         * Leave as null to use no serializer or return a simple JSON.
         */
        'serializer' => null,
    ],

    /*
     * If you would like the package to generate the same example values for parameters on each run,
     * set this to any number (eg. 1234)
     *
     */
    'faker_seed' => null,

    /*
     * If you would like to customize how routes are matched beyond the route configuration you may
     * declare your own implementation of RouteMatcherInterface
     *
     */
    'routeMatcher' => \Mpociot\ApiDoc\Matching\RouteMatcher::class,
];
