<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventOther extends Model {
	use SoftDeletes;

	// protected $casts = [
	// 	'start' => 'datetime',
	// 	'end' => 'datetime',
	// ];
}
