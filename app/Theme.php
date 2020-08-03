<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'ru_Name','eng_Name','id_Group','id_User','id_Subject', 'global'
    ];
}
