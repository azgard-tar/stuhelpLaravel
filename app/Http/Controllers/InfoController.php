<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    protected $consts = [
        "password_valid" => [
            "Минимум 6 символов.", 
            "Минимум 1 символ в нижнем регистре",
            "Минимум 1 символ в верхнем регистре",
            "Минимум одно число",
            "Минимум 1 спец-символ",
        ]
    ];
    public function authInfo(){
        $ret = [
            [
                "url"        => ("/api/auth/login"),
                "description"=> "Обычная авторизация через ?key=value",
                "method"     => "GET",
                "urlParam"   => null,
                "queryParam" => [
                    "email"    => [
                        "required"    => true,
                        "description" => "Почта юзера",
                    ],
                    "password" => [
                        "required"    => true,
                        "description" => "Пароль юзера",
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
            ], // /api/auth/login
            [
                "url"        => ("/api/auth/login123"),
                "description"=> "Защищенная авторизация через параметр тела запроса Authorization",
                "method"     => "GET",
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => [
                    "Authorization"   => [
                        "required"    => true,
                        "description" => "Строка авторизации в стандартном формате",
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
            ], // /api/auth/login123
            [
                "url"        => ("/api/auth/registration"),
                "description"=> "Регистрация нового пользователя",
                "method"     => "POST",
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => [
                    "login"    => [
                        "required"    => true,
                        "description" => "Логин юзера"
                    ],
                    "email"    => [
                        "required"    => true,
                        "description" => "Почта юзера"
                    ],
                    "password" => [
                        "required"    => true,
                        "description" => "Пароль юзера",
                        "validation"  => $this->consts["password_valid"],
                    ]
                ],
                "response"   => [
                    "200"      => [
                        "message"     => "Successfully registration!"
                    ],
                    "401"      => [
                        "error"       => "Текст ошибки"
                    ]
                ]
            ], // /api/auth/registration
        ];
        if( auth()->check() ){
            array_push($ret, 
                [
                    "url"        => ("/api/auth/me"),
                    "description"=> "Посмотреть инфу про себя",
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
                ], // /api/auth/me
                [
                    "url"        => ("/api/auth/logout"),
                    "description"=> "Выйти из системы",
                    "method"     => "GET",
                    "authReq"    => true,
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
                ], // /api/auth/logout
                [
                    "url"        => ("/api/auth/refresh"),
                    "description"=> "Возобновить токен авторизации( +1 час )",
                    "method"     => "GET",
                    "authReq"    => true,
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
                ], // /api/auth/refresh
            );
        }
        return $ret;
    }
    public function userInfo(){
        $ret = [
            [
                "url"        => ("/api/user/update"),
                "description"=> "Обновить инфу о себе",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => [
                    "name"    => [
                        "required"    => false,
                        "description" => "Имя юзера",
                        "type"        => "string",
                    ],
                    "Surname"    => [
                        "required"    => false,
                        "description" => "Фамилия юзера",
                        "type"        => "string",
                    ],
                    "email"    => [
                        "required"    => false,
                        "description" => "Почта юзера",
                        "type"        => "string",
                    ],
                    "password" => [
                        "required"    => false,
                        "description" => "Пароль юзера",
                        "validation"  => $this->consts["password_valid"],
                        "type"        => "string",
                    ],
                    "id_City"    => [
                        "required"    => false,
                        "description" => "Id города из базе данных",
                        "type"        => "int",
                    ],
                    "id_Country"    => [
                        "required"    => false,
                        "description" => "Id страны из базе данных",
                        "type"        => "int",
                    ],
                ],
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                            "Surname"   => null,
                            "Login"     => "test",
                            "name"      => "Vasya",
                            "email"     => "test@google.com",
                            "email_verified_at"=> null,
                            "id_Group"  => null,
                            "LastLogin" => "2020-08-19 16:51:20",
                            "id_City"   => null,
                            "id_Country"=> null,
                            "Privilege" => 4,
                            "Avatar"    => "images/none.jpg"
                    ],
                    "401"      => [
                        "error"       => "Unauthorized"
                    ]
                ]
            ], // /api/user/update
            [
                "url"        => ("/api/user/delete"),
                "description"=> "Удалить себя",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "204"      => null,
                    "401"      => [
                        "error"       => "Unauthorized"
                    ]
                ]
            ], // /api/user/delete
            [
                "url"        => ("/api/user/image"),
                "description"=> "Получить аватарку",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => null,
                "response"   => null
            ], // /api/user/image - get
            [
                "url"        => ("/api/user/image"),
                "description"=> "Загрузить аватарку",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => [
                    "Avatar" => [
                        "required"    => true,
                        "description" => "Файл с вашей аватаркой",
                        "type"        => "file",
                        "validation"  => [
                            "Расширение: jpeg,png,jpg,gif,svg",
                            "Размер: 2MB",
                        ]
                    ]
                ],
                "response"   => [
                    "401"      => [
                        "error"       => "Unauthorized"
                    ],
                    "404"      => [
                        "error"       => "Data not found"
                    ]
                ]
            ], // /api/user/image - post
            [
                "url"        => ("/api/user/privilege"),
                "description"=> "Получить информацию про свой статус аккаунта",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"      => 1,
                        "ru_Name" => "Пользователь",
                        "eng_Name"=> "User"
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/privilege
            [
                "url"        => ("/api/user/event"),
                "description"=> "Создать событие",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    'Name'          => [
                        "required"    => true,
                        "description" => "Название события",
                        "type"        => "string"
                    ],
                    'Description'   => [
                        "required"    => true,
                        "description" => "Описание события",
                        "type"        => "string"
                    ],
                    'EvWhen'        => [
                        "required"    => true,
                        "description" => "Время начала события",
                        "type"        => "date",
                        "validation"  => [
                            "Формат: Y-m-d H:m:s",
                            "Дата должна стартовать с текущего дня"
                        ]
                    ],
                    'EvWhenEnd'     => [
                        "required"    => true,
                        "description" => "Время окончания события",
                        "type"        => "date",
                        "validation"  => [
                            "Формат: Y-m-d H:m:s",
                            "Дата должна идти после EvWhen"
                        ]
                    ],
                    'EvType'        => [
                        "required"    => false,
                        "description" => "Тип события. 0 - обычная, 1 - пара в универе. По умолчанию 0.",
                        "type"        => "int"
                    ],
                    'EvWhere'       => [
                        "required"    => false,
                        "description" => "Место проведения события",
                        "type"        => "string"
                    ],
                    'id_Subject'    => [
                        "required"    => false,
                        "description" => "(EvType == 1 )Id предмета из базы данных. Посмотреть свои предметы: /api/user/subject",
                        "type"        => "int"
                    ],
                    'id_Theme'      => [
                        "required"    => false,
                        "description" => "(EvType == 1 )Id темы из базы данных. Посмотреть свои темы: /api/user/theme",
                        "type"        => "int"
                    ],
                    'Keywords'      => [
                        "required"    => false,
                        "description" => "(EvType == 1 ) Ключевые слова с пары",
                        "type"        => "string",
                        "example"     => "key, word"
                    ],
                    'Questions'     => [
                        "required"    => false,
                        "description" => "(EvType == 1 ) Вопросы с пары для самопроверки",
                        "type"        => "string",
                        "example"     => "What if...? How it...?"
                    ],
                    'Homework'      => [
                        "required"    => false,
                        "description" => "(EvType == 1 ) Домашнее задание",
                        "type"        => "string"
                    ],
                    'WhenDoHW'      => [
                        "required"    => false,
                        "description" => "(EvType == 1 ) Время выполнения домашнего задания",
                        "type"        => "date",
                        "validation"  => [
                            "Формат: Y-m-d H:m:s",
                            "Дата должна стартовать с текущего дня"
                        ]
                    ],
                    'Color'         => [
                        "required"    => false,
                        "description" => "Цвет отображения события в календаре",
                        "type"        => "string"
                    ],
                    'withGroup'      => [
                        "required"    => false,
                        "description" => "(EvType == 1 ) Это событие для группы?(true/false) Это поле для старосты группы. ",
                        "type"        => "bool"
                    ],
                ],
                "response"   => [
                    "200"      => [
                        "id"            => 1,
                        "Name"          => "Встреча на совке",
                        "Description"   => "В пятницу нужно встретится в друзьями",
                        "EvWhen"        => "2020-08-21 18:00:00",
                        "EvWhenEnd"     => "2020-08-21 20:00:00",
                        "id_User"       => 2,
                        "EvType"        => 0,
                        "EvWhere"       => null,
                        "id_Subject"    => null,
                        "id_Theme"      => null,
                        "Keywords"      => null,
                        "Questions"     => null,
                        "Homework"      => null,
                        "WhenDoHW"      => null,
                        "Color"         => "#ff0000",
                        "id_Group"      => null
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/event - POST
            [
                "url"        => ("/api/user/event"),
                "description"=> "Посмотреть все события",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/event - GET
            [
                "url"        => ("/api/user/event/{id}"),
                "description"=> "Посмотреть одно событие",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id события из базы данных",
                        "example"    => "/api/user/event/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"            => 1,
                        "Name"          => "Встреча на совке",
                        "Description"   => "В пятницу нужно встретится в друзьями",
                        "EvWhen"        => "2020-08-21 18:00:00",
                        "EvWhenEnd"     => "2020-08-21 20:00:00",
                        "id_User"       => 2,
                        "EvType"        => 0,
                        "EvWhere"       => null,
                        "id_Subject"    => null,
                        "id_Theme"      => null,
                        "Keywords"      => null,
                        "Questions"     => null,
                        "Homework"      => null,
                        "WhenDoHW"      => null,
                        "Color"         => "#ff0000",
                        "id_Group"      => null
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/event - GET
            [
                "url"        => ("/api/user/event/{id}"),
                "description"=> "Обновить информацию о событии. Смотрите параметры в описании создания события",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id события из базы данных",
                        "example"    => "/api/user/event/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"            => 1,
                        "Name"          => "Встреча на совке",
                        "Description"   => "В пятницу нужно встретится в друзьями",
                        "EvWhen"        => "2020-08-21 18:00:00",
                        "EvWhenEnd"     => "2020-08-21 20:00:00",
                        "id_User"       => 2,
                        "EvType"        => 0,
                        "EvWhere"       => null,
                        "id_Subject"    => null,
                        "id_Theme"      => null,
                        "Keywords"      => null,
                        "Questions"     => null,
                        "Homework"      => null,
                        "WhenDoHW"      => null,
                        "Color"         => "#ff0000",
                        "id_Group"      => null
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/event - PUT
            [
                "url"        => ("/api/user/event/{id}"),
                "description"=> "Удалить событие",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id события из базы данных",
                        "example"    => "/api/user/event/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"   => "Unauthorized"
                    ],
                    "403"      => [
                        "error" =>  "Это не ваше событие"
                    ]
                ]
            ], // /api/user/event - DELETE
        ];
        return [
            "User's info coming soon..." => true,
        ];
    }
    public function headmanInfo(){
        return [
            "Headman's info coming soon..." => true,
        ];
    }
    public function moderInfo(){
        return [
            "Moder's info coming soon..." => true,
        ];
    }
    public function adminInfo(){
        return [
            "Admin's info coming soon..." => true,
        ];
    }
}
