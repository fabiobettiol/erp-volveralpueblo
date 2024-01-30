<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demandantfollowup extends Model {
	protected $casts = [
		'date' => 'date',
	];

	public function demandant() {
		return $this->belongsTo(Demandant::class);
	}

	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	// - Fabio, custom method
	public function uniqueDemandant(){
	  return $this->hasOne(Demandant::class, 'demandant_id');
	}
}
