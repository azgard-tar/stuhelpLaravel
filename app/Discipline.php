<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'ru_Name','eng_Name','id_Group','id_User', 'global'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
