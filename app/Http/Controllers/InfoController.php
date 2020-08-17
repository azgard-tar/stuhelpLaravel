<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function authInfo(){
        $ret = [
            [
                "url"        => ("/api/auth/login"),
                "method"     => "GET",
                "urlParam"   => null,
                "queryParam" => [
                    "email"    => [
                        "required"    => true,
                        "description" => "The user's email",
                    ],
                    "password" => [
                        "required"    => true,
                        "description" => "The user's password",
                    ],
                ],
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "access_token"=> "token",
                        "token_type"  => "bearer",
                        "expires_in"  => 3600
                    ],
                    "401"      => [
                        "error"       => "Unauthorized"
                    ]
                ]
            ],
            [
                "url"        => ("/api/auth/login123"),
                "method"     => "GET",
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => [
                    "Authorization"   => [
                        "required"    => true,
                        "description" => "Standart auth string",
                        "example"     => "Bearer base64(email:password)"
                    ]
                ],
                "response"   => [
                    "200"      => [
                        "access_token"=> "token",
                        "token_type"  => "bearer",
                        "expires_in"  => 3600
                    ],
                    "401"      => [
                        "error"       => "Unauthorized"
                    ]
                ]
            ],
            [
                "url"        => ("/api/auth/registration"),
                "method"     => "POST",
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => [
                    "login"    => [
                        "required"    => true,
                        "description" => "Standart auth string"
                    ],
                    "email"    => [
                        "required"    => true,
                        "description" => "Standart auth string"
                    ],
                    "password" => [
                        "required"    => true,
                        "description" => "Standart auth string",
                        "validation"  => "Length: 6, must contain: lowercase letter, uppercase letter, digit, special character"
                    ]
                ],
                "response"   => [
                    "200"      => [
                        "message"     => "Successfully registration!"
                    ],
                    "401"      => [
                        "error"       => "Text of the error"
                    ]
                ]
            ],
        ];
        if( auth()->check() ){
            array_push($ret, 
                [
                    "url"        => ("/api/auth/me"),
                    "method"     => "GET",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,
                    "bodyParam"  => null,
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "Surname"   => null,
                            "Login"     => "test",
                            "name"      => "Vasya",
                            "email"     => "test@google.com",
                            "email_verified_at"=> null,
                            "created_at"=> "2020-07-31T10:51:52.000000Z",
                            "updated_at"=> "2020-07-31T10:51:52.000000Z",
                            "id_Group"  => null,
                            "LastLogin" => null,
                            "id_City"   => null,
                            "id_Country"=> null,
                            "Privilege" => 4,
                            "Avatar"    => "images/none.jpg"
                        ],
                        "401"      => [
                            "error"     => "Unauthorized"
                        ]
                    ]
                ],
                [
                    "url"        => ("/api/auth/logout"),
                    "method"     => "GET",
                    "urlParam"   => null,
                    "queryParam" => null,
                    "bodyParam"  => null,
                    "response"   => [
                        "200"      => [
                            "message"   => "Successfully logged out"
                        ],
                        "401"      => [
                            "error"     => "Unauthorized"
                        ]
                    ]
                ],
                [
                    "url"        => ("/api/auth/refresh"),
                    "method"     => "GET",
                    "urlParam"   => null,
                    "queryParam" => null,
                    "bodyParam"  => null,
                    "response"   => [
                        "200"      => [
                            "message"   => "Successfully refresh"
                        ],
                        "401"      => [
                            "error"     => "Unauthorized"
                        ]
                    ]
                ],
            );
        }
        return $ret;
    }
}
