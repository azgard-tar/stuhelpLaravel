<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupRequests extends Model
{
    protected $fillable = [
        'id_User', 'id_Group'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
