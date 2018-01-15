<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = "paymentsmethods";
    protected $fillable = [
    	'id',
		'provider',
		'typemethod',
		'country',
		'month',
		'year',
		'cvv',
		'cardnumber',
		'owner',
		'paypal_email',
		'bank',
		'notified'
    ];

    public function user(){
	  return $this->belongsTo('App\User');
	}

	public function ownerApi(){
		return $this->BelongsTo(User::class, 'owner', 'id');
	}

	public function transactions(){
		return $this->hasMany(transaction_bank::class, 'paymentmethod', 'id');
	}
}
