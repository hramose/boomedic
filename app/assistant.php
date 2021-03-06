<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class assistant extends Model
{
    protected $table = "assistant";
    protected $fillable = [
    	'id',
		'user_assist',
		'user_doctor',
		'confirmation'
    ];

    public function user_assist(){
	  return $this->belongsTo('App\User');
	}
    public function user_doctor(){
	  return $this->belongsTo('App\User');
	}	
}
