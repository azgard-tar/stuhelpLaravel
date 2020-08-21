<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $fillable = [
        'Name','id_University','id_Headman'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
