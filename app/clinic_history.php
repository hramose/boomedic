<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clinic_history extends Model
{
    protected $table = "clinic_history";
    protected $fillable = [
    	'id',
    	'answer',
		'answer_id',
		'question_id',
		'question',
		'userid'
    ];

    public function userid(){
	  return $this->belongsTo('App\User', 'userid');
	}
}
