<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class family extends Model
{
    protected $table = "family";
    protected $fillable = [
    	'id',
    	'activeUser',
		'passiveUser',
		'relationship',
		'activeUserStatus',
		'parent'
    ];

    public function parent(){
	  return $this->belongsTo('App\User', 'parent');
	}
	public function activeUser(){
	  return $this->belongsTo('App\User', 'activeUser');
	}
	public function passiveUser(){
	  return $this->belongsTo('App\User', 'passiveUser');
	}
	public static function ilike($a){
  	return DB::table('users')->where('name', 'ILIKE','%'.$a.'%')->get();
	}
}

