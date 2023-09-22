<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model {
	Use SoftDeletes;

	public function cdrs() {
		return $this->hasMany(Cdr::class);
	}	
}
