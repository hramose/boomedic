<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class questions_clinic_history extends Model
{
    protected $table = "questions_clinic_history";
    protected $fillable = [
    	'id',
    	'code_translation',
		'question',
		'text_help',
    ];

}
