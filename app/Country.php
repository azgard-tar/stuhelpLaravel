<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable =[
        'ru_Name', 'eng_Name'
    ];
}
