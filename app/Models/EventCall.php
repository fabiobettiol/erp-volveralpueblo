<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCall extends Model {
	use SoftDeletes;

	protected $casts = [
		'start' => 'datetime',
		'end' => 'datetime',
	];

	public function user() {
		return $this->belongsTo(User::class);
	}
	
	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}	
}


