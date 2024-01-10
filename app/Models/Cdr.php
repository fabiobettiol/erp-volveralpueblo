<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cdr extends Model {
	use SoftDeletes;

	public function zone() {
		return $this->belongsTo(Zone::class);
	}

	public function community() {
		return $this->belongsTo(Community::class);
	}

	public function province() {
		return $this->belongsTo(Province::class);
	}

	public function municipality() {
		return $this->belongsTo(Municipality::class);
	}

	public function cdrtype() {
		return $this->belongsTo(Cdrtype::class);
	}

	public function users() {
		return $this->hasMany(User::class);
	}

	public function news() {
		return $this->hasMany(Cdrnew::class);
	}

	public function comarcas() {
		return $this->hasMany(Region::class);
	}

	public function convenios() {
		return $this->hasMany(Cdragreement::class);
	}

	public function demandantfollowups() {
		return $this->hasMany(Demandantfollowup::class);
	}
}
