<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable =[
        'ru_Name', 'eng_Name', 'id_Country'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
