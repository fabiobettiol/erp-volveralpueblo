<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demandant extends Model {
	protected $casts = [
		'birthdate' => 'date',
	];

	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}

	public function gender() {
		return $this->belongsTo(Gender::class);
	}

	public function documenttype() {
		return $this->belongsTo(Documenttype::class);
	}

	public function country() {
		return $this->belongsTo(Country::class);
	}

	public function provincefrom() {
		return $this->belongsTo(Province::class, 'provincefrom_id');
	}

	public function provinceto() {
		return $this->belongsTo(Province::class, 'provinceto_id');
	}

	public function zone() {
		return $this->belongsTo(Zone::class);
	}

	public function followups() {
		return $this->HasMany(Demandantfollowup::class);
	}

	public function documents() {
		return $this->HasMany(Demandantdoc::class);
	}
}
