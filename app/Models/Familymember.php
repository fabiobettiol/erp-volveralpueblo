<?php

namespace App\Models;

use App\Models\Family;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familymember extends Model {

	use SoftDeletes;

        protected $casts = [
                'dateofbirth' => 'date',
        ];


	public function family() {
		return $this->belongsTo(Family::class);
	}

	public function gender() {
		return $this->belongsTo(Gender::class);
	}

	public function sector() {
		return $this->belongsTo(Sector::class);
	}

	public function nationality() {
		return $this->belongsTo(Country::class);
	}

	public function citizenship() {
		return $this->belongsTo(Country::class);
	}

}
