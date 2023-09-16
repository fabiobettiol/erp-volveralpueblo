<?php

namespace App\Models;

use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Familyfollowup extends Model {

	protected $casts = [
		'date' => 'date',
	];

	public function family() {
		return $this->belongsTo(Family::class);
	}

	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

}
