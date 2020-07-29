<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutEvent extends Model
{
    protected $fillable =[
        'Name','Description','EvWhen',
        'EvWhenEnd','id_User','EvType',
        'EvWhere','id_Subject','id_Theme',
        'Keywords','Questions','Homework',
        'WhenDoHW','Color', 'id_Group'       
    ];
}
