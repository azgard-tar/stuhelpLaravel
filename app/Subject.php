<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'ru_Name','eng_Name','id_Group','id_User','id_Discipline', 'global'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
