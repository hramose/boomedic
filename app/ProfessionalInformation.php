<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessionalInformation extends Model
{
    protected $table = "professional_information";
    protected $fillable = [
		'specialty',
		'schoolOfMedicine',
		'facultyOfSpecialization',
		'practiseProfessional',
		'user'
    ];

    public function user(){
	  return $this->belongsTo('App\User', 'user');
	}
}
