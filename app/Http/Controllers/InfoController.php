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
                "group"      => "user",
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
                "group"      => "user",
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
                "group"      => "user",
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
                    "group"      => "user",
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
                    "group"      => "user",
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
                    "group"      => "user",
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
                "group"      => "user",
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
            ], // /api/user/update PUT
            [
                "url"        => ("/api/user/delete"),
                "description"=> "Удалить себя",
                "group"      => "user",
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
            ], // /api/user/delete DELETE
            [
                "url"        => ("/api/user/image"),
                "description"=> "Получить аватарку",
                "group"      => "user",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => null,
                "response"   => null
            ], // /api/user/image - GET
            [
                "url"        => ("/api/user/image"),
                "description"=> "Загрузить аватарку",
                "group"      => "user",
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
            ], // /api/user/image - POST
            [
                "url"        => ("/api/user/privilege"),
                "description"=> "Получить информацию про свой статус аккаунта",
                "group"      => "user",
                "method"     => "GET",
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
            ], // /api/user/privilege GET
            [
                "url"        => ("/api/user/event"),
                "description"=> "Создать событие",
                "group"      => "event",
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
                "group"      => "event",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/event - GET
            [
                "url"        => ("/api/user/event/{id}"),
                "description"=> "Посмотреть одно событие",
                "group"      => "event",
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
                "group"      => "event",
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
                "group"      => "event",
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
            [
                "url"        => ("/api/user/discipline"),
                "description"=> "Посмотреть все доступные дисциплина(глобальные, групповые, личные)",
                "group"      => "discipline",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/discipline - GET
            [
                "url"        => ("/api/user/discipline/{id}"),
                "description"=> "Обновить информацию о дисциплине. Смотрите параметры в описании создания дисциплины.",
                "group"      => "discipline",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id дисциплины из базы данных",
                        "example"    => "/api/user/discipline/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "Инженерия программного обеспечения",
                        "eng_Name"  => "Software engineering",
                        "id_Group"  => null,
                        "global"    => 0
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/discipline - PUT
            [
                "url"        => ("/api/user/discipline/{id}"),
                "description"=> "Удалить дисциплину( если вы владелец )",
                "group"      => "discipline",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id дисциплины из базы данных",
                        "example"    => "/api/user/discipline/1"
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
                        "error" =>  "Это не ваша дисциплина"
                    ]
                ]
            ], // /api/user/discipline - DELETE
            [
                "url"        => ("/api/user/subject"),
                "description"=> "Посмотреть все доступные предметы(глобальные, групповые, личные)",
                "group"      => "subject",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/subject - GET
            [
                "url"        => ("/api/user/subject/{id}"),
                "description"=> "Обновить информацию о предмете. Смотрите параметры в описании создания предмета.",
                "group"      => "subject",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id предмета из базы данных",
                        "example"    => "/api/user/subject/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "Дискретная математика",
                        "eng_Name"  => "Discrete Math",
                        "id_Group"  => null,
                        "id_Discipline" => 2,
                        "global"    => 0
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/subject - PUT
            [
                "url"        => ("/api/user/subject/{id}"),
                "description"=> "Удалить предмет( если вы владелец )",
                "group"      => "subject",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id предмета из базы данных",
                        "example"    => "/api/user/subject/1"
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
                        "error" =>  "Это не ваш предмет"
                    ]
                ]
            ], // /api/user/subject - DELETE
            [
                "url"        => ("/api/user/theme"),
                "description"=> "Посмотреть все доступные темы(глобальные, групповые, личные)",
                "group"      => "theme",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/theme - GET
            [
                "url"        => ("/api/user/theme/{id}"),
                "description"=> "Обновить информацию о теме. Смотрите параметры в описании создания темы.",
                "group"      => "theme",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id темы из базы данных",
                        "example"    => "/api/user/theme/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "Бинарный код",
                        "eng_Name"  => "Binary code",
                        "id_Group"  => null,
                        "id_Subject"=> 2,
                        "global"    => 0
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/theme - PUT
            [
                "url"        => ("/api/user/theme/{id}"),
                "description"=> "Удалить тему( если вы владелец )",
                "group"      => "theme",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id темы из базы данных",
                        "example"    => "/api/user/theme/1"
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
                        "error" =>  "Это не ваша тема"
                    ]
                ]
            ], // /api/user/theme - DELETE
            [
                "url"        => ("/api/user/group/students"),
                "description"=> "Посмотреть всех одногруппников",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/group/students - GET
            [
                "url"        => ("/api/user/group"),
                "description"=> "Посмотреть информацию о своей группе",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"  =>  [
                        "id"        => 1,
                        "Name"      => "171",
                        "university"=> "ЧНУ",
                        "headman"   => "Test2"
                    ],
                    "400" =>   [ 
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/group - GET
            [
                "url"        => "/api/user/grouprequests/{id}",
                "description"=> "Подать заявку на вступление в группу( только один )",
                "group"      => "group",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id группы из базы данных",
                        "example"    => "/api/user/grouprequests/1"
                    ]
                ],
                "queryParam" => null,
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                            "id_User" => 3,
                            "id_Group"=> 1,
                            "id"      => 1
                    ],
                    "401"      => [
                        "error"       => "Unauthorized"
                    ],
                    "403"      => [
                        "error"       => "Вы уже состоите в группе"
                    ],
                    "403"      => [
                        "error"       => "Вы уже подали запрос( больше одного нельзя )"
                    ]
                ]
            ], // /api/user/grouprequests/{id} - POST
            [
                "url"        => ("/api/user/grouprequests"),
                "description"=> "Удалить запрос на вступление в группу",
                "group"      => "group",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"   => "Unauthorized"
                    ],
                    "404"      => [
                        "error" =>  "У вас нет запроса"
                    ]
                ]
            ], // /api/user/grouprequests - DELETE
            [
                "url"        => ("/api/user/searchgroup"),
                "description"=> "Найти группу по названию или(и) университету",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "id_University"     => [
                        "required"   => false,
                        "type"       => "int",
                        "description"=> "id университета из базы данных"
                    ],
                    "Name"     => [
                        "required"   => false,
                        "type"       => "string",
                        "description"=> "Название группы"
                    ]
                ],
                "response"   => [
                    "200"  =>  [
                        "id"        => 1,
                        "Name"      => "171",
                        "university"=> "ЧНУ",
                        "headman"   => "Test2"
                    ],
                    "400" =>   [ 
                        "error"   => "Unauthorized"
                    ],
                    "403" =>   [
                        "error"   => "Нет данных"
                    ]
                ]
            ], // /api/user/searchgroup - GET
            [
                "url"        => ("/api/user/university"),
                "description"=> "Посмотреть все университеты",
                "group"      => "university",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/university/{id} - GET
            [
                "url"        => ("/api/user/university/{id}"),
                "description"=> "Посмотреть один университет",
                "group"      => "university",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id университета из базы данных",
                        "example"    => "/api/user/university/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "ЧНУ",
                        "eng_Name"  =>"CHMNU",
                        "id_Country"=> 1,
                        "id_City"   => 1
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/university - GET
            [
                "url"        => ("/api/user/country"),
                "description"=> "Посмотреть все страны",
                "group"      => "country",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/country/{id} - GET
            [
                "url"        => ("/api/user/country/{id}"),
                "description"=> "Посмотреть одну страну",
                "group"      => "country",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id страны из базы данных",
                        "example"    => "/api/user/country/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "Украина",
                        "eng_Name"  => "Ukraine"
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/country - GET
            [
                "url"        => ("/api/user/city"),
                "description"=> "Посмотреть все города",
                "group"      => "city",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/user/city - GET
            [
                "url"        => ("/api/user/city/{id}"),
                "description"=> "Посмотреть один город",
                "group"      => "city",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id города из базы данных",
                        "example"    => "/api/user/city/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "id"        => 1,
                        "ru_Name"   => "Киев",
                        "eng_Name"  => "Kiev",
                        "id_Country"=> 1
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/city/{id} - GET
            [
                "url"        => ("/api/user/group/leave"),
                "description"=> "Покинуть группу",
                "group"      => "group",
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
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/user/group/leave - GET
        ];
        switch( auth()->user()->Privilege ){
            case 1:
                array_push($ret,
                [
                    "url"        => ("/api/user/discipline"),
                    "description"=> "Создать дисциплину",
                    "group"      => "discipline",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на английском",
                            "type"        => "string"
                        ]
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Инженерия программного обеспечения",
                            "eng_Name"  => "Software engineering",
                            "id_Group"  => null,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/discipline - POST
                [
                    "url"        => ("/api/user/subject"),
                    "description"=> "Создать предмет",
                    "group"      => "subject",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на английском",
                            "type"        => "string"
                        ],
                        'id_Discipline'          => [
                            "required"    => false,
                            "description" => "id дисциплины из базы данных",
                            "type"        => "int"
                        ]
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Дискретная математика",
                            "eng_Name"  => "Discrete Math",
                            "id_Group"  => null,
                            "id_Discipline" => 2,
                            "global"    => 0
                            
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/subject - POST
                [
                    "url"        => ("/api/user/theme"),
                    "description"=> "Создать тему",
                    "group"      => "theme",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на английском",
                            "type"        => "string"
                        ],
                        'id_Subject'          => [
                            "required"    => false,
                            "description" => "id предмета из базы данных",
                            "type"        => "int"
                        ]
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Двоичный код",
                            "eng_Name"  => "Binary code",
                            "id_Group"  => null,
                            'id_Subject' => 1,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/theme - POST
            );
            break;
            case 2:
                array_push($ret,
                [
                    "url"        => ("/api/user/discipline"),
                    "description"=> "Создать дисциплину",
                    "group"      => "discipline",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на английском",
                            "type"        => "string"
                        ],
                        'withGroup'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для группы",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Инженерия программного обеспечения",
                            "eng_Name"  => "Software engineering",
                            "id_Group"  => null,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/discipline - POST (+ withGroup)
                [
                    "url"        => ("/api/user/subject"),
                    "description"=> "Создать предмет",
                    "group"      => "subject",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на английском",
                            "type"        => "string"
                        ],
                        'id_Discipline'          => [
                            "required"    => false,
                            "description" => "id дисциплины из базы данных",
                            "type"        => "int"
                        ],
                        'withGroup'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для группы",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Дискретная математика",
                            "eng_Name"  => "Discrete Math",
                            "id_Group"  => null,
                            "id_Discipline" => 2,
                            "global"    => 0
                            
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/subject - POST (+ withGroup)
                [
                    "url"        => ("/api/user/theme"),
                    "description"=> "Создать тему",
                    "group"      => "theme",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на английском",
                            "type"        => "string"
                        ],
                        'id_Subject'          => [
                            "required"    => false,
                            "description" => "id предмета из базы данных",
                            "type"        => "int"
                        ],
                        'withGroup'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для группы",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Двоичный код",
                            "eng_Name"  => "Binary code",
                            "id_Group"  => null,
                            'id_Subject' => 1,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/theme - POST (+ withGroup)
            );
            break;
            case 3:
            case 4:
                array_push($ret,
                [
                    "url"        => ("/api/user/discipline"),
                    "description"=> "Создать дисциплину",
                    "group"      => "discipline",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название дисциплины на английском",
                            "type"        => "string"
                        ],
                        'global'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для всех",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Инженерия программного обеспечения",
                            "eng_Name"  => "Software engineering",
                            "id_Group"  => null,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/discipline - POST (+ global)
                [
                    "url"        => ("/api/user/subject"),
                    "description"=> "Создать предмет",
                    "group"      => "subject",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название предмета на английском",
                            "type"        => "string"
                        ],
                        'id_Discipline'          => [
                            "required"    => false,
                            "description" => "id дисциплины из базы данных",
                            "type"        => "int"
                        ],
                        'global'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для всех",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Дискретная математика",
                            "eng_Name"  => "Discrete Math",
                            "id_Group"  => null,
                            "id_Discipline" => 2,
                            "global"    => 0
                            
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/subject - POST (+ global)
                [
                    "url"        => ("/api/user/theme"),
                    "description"=> "Создать тему",
                    "group"      => "theme",
                    "method"     => "POST",
                    "authReq"    => true,
                    "urlParam"   => null,
                    "queryParam" => null,                    
                    "bodyParam"  => [
                        'ru_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на русском",
                            "type"        => "string"
                        ],
                        'eng_Name'          => [
                            "required"    => false,
                            "description" => "Название темы на английском",
                            "type"        => "string"
                        ],
                        'id_Subject'          => [
                            "required"    => false,
                            "description" => "id предмета из базы данных",
                            "type"        => "int"
                        ],
                        'global'          => [
                            "required"    => false,
                            "description" => "false - личная, true - для всех",
                            "type"        => "bool"
                        ],
                    ],
                    "response"   => [
                        "200"      => [
                            "id"        => 1,
                            "ru_Name"   => "Двоичный код",
                            "eng_Name"  => "Binary code",
                            "id_Group"  => null,
                            'id_Subject' => 1,
                            "global"    => 0
                        ],
                        "401"      => [
                            "error"   => "Unauthorized"
                        ]
                    ]
                ], // /api/user/theme - POST (+ global)
            );
            break;
        }


        return $ret;
    }
    public function headmanInfo(){
        $ret = [
            [
                "url"        => ("/api/headman/group"),
                "description"=> "Изменить информацию о своей группе",
                "group"      => "group",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "id_Headman" => [
                        "required" => false,
                        "type" => "int",
                        "description" => "id нового старосты группы"
                    ],
                    "id_University" => [
                        "required" => false,
                        "type" => "int",
                        "description" => "id нового университета группы"
                    ]
                ],
                "response"   => [
                    "200"  =>  [
                        "id"        => 1,
                        "Name"      => "271",
                        "university"=> "ЧНУ",
                        "headman"   => "Petya"
                    ],
                    "400" =>   [ 
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/headman/group - PUT
            [
                "url"        => "/api/headman/grouprequests/{id}",
                "description"=> "Принять заявку на вступление",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id запроса из /api/headman/grouprequests",
                        "example"    => "/api/headman/grouprequests/1"
                    ]
                ],
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
                        "error"       => "Unauthorized"
                    ],
                    "403"      => [
                        "error"       => "Вы не староста этой группы"
                    ],
                    "403"      => [
                        "error"       => "Запроса от этого пользователя нет"
                    ]
                ]
            ], // /api/headman/grouprequests/{id} - GET
            [
                "url"        => ("/api/headman/grouprequests"),
                "description"=> "Посмотреть все запросы на вступление в группу",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        [
                            "id"      => 1,
                            "id_User" => 3
                        ],
                        [
                            "id"      => 2,
                            "id_User" => 5
                        ],
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ],
                    "403"      => [
                        "error"   => "Вы не староста этой группы"
                    ]
                ]
            ], // /api/headman/grouprequests - GET
        ];
        return $ret;
    }
    public function moderInfo(){
        $ret = [
            [
                "url"        => ("/api/moder/group/students/{id}"),
                "description"=> "Посмотреть всех студентов определенной группы",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    'id' => [
                        'required'    => true,
                        'description' => 'id группы'
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/moder/group/students/{id} - GET
            [
                "url"        => ("/api/moder/group"),
                "description"=> "Посмотреть все группы",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
            ], // /api/moder/group - GET
            [
                "url"        => ("/api/moder/group"),
                "description"=> "Создать группу",
                "group"      => "group",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "Name" => [
                        "required" => false,
                        "type" => "string",
                        "description" => "название группы"
                    ],
                    "id_Headman" => [
                        "required" => false,
                        "type" => "int",
                        "description" => "id старосты группы"
                    ],
                    "id_University" => [
                        "required" => false,
                        "type" => "int",
                        "description" => "id университета группы"
                    ]
                ],
                "response"   => [
                    "200"  =>  [
                        "id"        => 1,
                        "Name"      => "271",
                        "university"=> "ЧНУ",
                        "headman"   => "Petya"
                    ],
                    "400" =>   [ 
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/group - POST
            [
                "url"        => ("/api/moder/group/{id}"),
                "description"=> "Изменить информацию о группе",
                "group"      => "group",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"  =>  [
                        "id"        => 1,
                        "Name"      => "271",
                        "university"=> "ЧНУ",
                        "headman"   => "Petya"
                    ],
                    "400" =>   [ 
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/group/{id} - PUT
            [
                "url"        => ("/api/moder/group/{id}"),
                "description"=> "Удалить группу",
                "group"      => "group",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id группы"
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
                        "error" =>  "Это не ваша группа"
                    ]
                ]
            ], // /api/moder/group/{id} - DELETE
            [
                "url"        => ("/api/moder/group/{id}"),
                "description"=> "Посмотреть информацию определенной группы",
                "group"      => "group",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200" => [
                        "id"        => 1,
                        "Name"      => "271",
                        "university"=> "ЧНУ",
                        "headman"   => "Petya"
                    ]
                ]
            ], // /api/moder/group/{id} - GET
            [
                "url"        => ("/api/moder/headman/{id}"),
                "description"=> "Сделать юзера старостой группы",
                "group"      => "group",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => [
                    "id"       => [
                        "required" => true,
                        "description"=>"id юзера"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => [
                    "id_Group" => [
                        "required" => false,
                        "type"     => "int",
                        "description" => "id группы, старостой которой будет юзер. По дефолту юзер становиться старостой своей группы"
                    ]
                ],
                "response"   => [
                    "200" => [
                        "id"        => 1,
                        "Surname"   => null,
                        "Login"     => "test",
                        "name"      => "Vasya",
                        "email"     => "test@google.com",
                        "email_verified_at"=> null,
                        "created_at"=> "2020-07-31T10:51:52.000000Z",
                        "updated_at"=> "2020-07-31T10:51:52.000000Z",
                        "id_Group"  => 1,
                        "LastLogin" => null,
                        "id_City"   => null,
                        "id_Country"=> null,
                        "Privilege" => 2,
                        "Avatar"    => "images/none.jpg"
                    ]
                ]
            ], // /api/moder/headman/{id} - GET
            [
                "url"        => ("/api/moder/university"),
                "description"=> "Создать объект университета",
                "group"      => "university",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "ru_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название университета на русском"
                    ],
                    "eng_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название университета на английском"
                    ],
                    "id_City" => [
                        "required" => false,
                        "type"     => "int",
                        "description"=>"id города, в котором находиться университет"
                    ],
                    "id_Country" => [
                        "required" => false,
                        "type"     => "int",
                        "description"=>"id страны, в котором находиться университет"
                    ],
                ],
                "response" => [
                    "200" => [
                        "ru_Name"   => "НУК",
                        "eng_Name"  => "NUK",
                        "id_City"   => "1",
                        "id_Country"=> "1",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/university - POST
            [
                "url"        => ("/api/moder/university/{id}"),
                "description"=> "Обновить информацию о университете. Смотрите параметры в описании создания университета.",
                "group"      => "university",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id университета",
                        "example"    => "/api/moder/university/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "ru_Name"   => "НУК",
                        "eng_Name"  => "NUCK",
                        "id_City"   => "20",
                        "id_Country"=> "1",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/university/{id} - PUT
            [
                "url"        => ("/api/moder/university/{id}"),
                "description"=> "Удалить объект университета",
                "group"      => "university",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id университета",
                        "example"    => "/api/moder/university/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/university/{id} - DELETE
            [
                "url"        => ("/api/moder/city"),
                "description"=> "Создать объект города",
                "group"      => "city",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "ru_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название города на русском"
                    ],
                    "eng_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название города на английском"
                    ],
                    "id_Country" => [
                        "required" => false,
                        "type"     => "int",
                        "description"=>"id страны, в котором находиться город"
                    ],
                ],
                "response" => [
                    "200" => [
                        "ru_Name"   => "Львов",
                        "eng_Name"  => "Lviv",
                        "id_Country"=> "1",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/city - POST
            [
                "url"        => ("/api/moder/city/{id}"),
                "description"=> "Обновить информацию о городе. Смотрите параметры в описании создания города.",
                "group"      => "city",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id города",
                        "example"    => "/api/moder/city/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "ru_Name"   => "Львовск",
                        "eng_Name"  => "Lviv",
                        "id_Country"=> "1",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/city/{id} - PUT
            [
                "url"        => ("/api/moder/city/{id}"),
                "description"=> "Удалить объект города",
                "group"      => "city",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id города",
                        "example"    => "/api/moder/city/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/city/{id} - DELETE
            [
                "url"        => ("/api/moder/country"),
                "description"=> "Создать объект страны",
                "group"      => "country",
                "method"     => "POST",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,                    
                "bodyParam"  => [
                    "ru_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название страны на русском"
                    ],
                    "eng_Name" => [
                        "required" => false,
                        "type"     => "string",
                        "description"=>"Название страны на английском"
                    ],
                ],
                "response" => [
                    "200" => [
                        "ru_Name"   => "Россия",
                        "eng_Name"  => "Russia",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/country - POST
            [
                "url"        => ("/api/moder/country/{id}"),
                "description"=> "Обновить информацию о стране. Смотрите параметры в описании создания страны.",
                "group"      => "country",
                "method"     => "PUT",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id страны",
                        "example"    => "/api/moder/country/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "200"      => [
                        "ru_Name"   => "Россия",
                        "eng_Name"  => "Russia",
                        "id"        => 2
                    ],
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/country/{id} - PUT
            [
                "url"        => ("/api/moder/country/{id}"),
                "description"=> "Удалить объект страны",
                "group"      => "country",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => [
                    "id"        => [
                        "required"   => true,
                        "type"       => "int",
                        "description"=> "id страны",
                        "example"    => "/api/moder/country/1"
                    ]
                ],
                "queryParam" => null,                    
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"   => "Unauthorized"
                    ]
                ]
            ], // /api/moder/country/{id} - DELETE
        ];
        return $ret;
    }
    public function adminInfo(){
        $ret = [
            [
                "url"        => "/api/admin/user",
                "description"=> "Получить информацию про всех юзеров",
                "group"      => "user",
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
            ], // /api/admin/user
            [
                "url"        => "/api/admin/user/{id}",
                "description"=> "Получить информацию про одного юзера",
                "group"      => "user",
                "method"     => "GET",
                "authReq"    => true,
                "urlParam"   => [
                    "id"    => [
                        "required" => true,
                        "description" => "user's id"
                    ]
                ],
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
            ], // /api/admin/user
            [
                "url"        => "/api/admin/user",
                "description"=> "Удалить юзера",
                "group"      => "user",
                "method"     => "DELETE",
                "authReq"    => true,
                "urlParam"   => null,
                "queryParam" => null,
                "bodyParam"  => null,
                "response"   => [
                    "203"      => null,
                    "401"      => [
                        "error"     => "Unauthorized"
                    ]
                ]
            ], // /api/admin/user
        ];
        return $ret;
    }
}
