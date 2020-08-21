<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable =[
        'ru_Name', 'eng_Name', 'id_Country', 'id_City'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
