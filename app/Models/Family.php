<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model {

	use SoftDeletes;

	protected $casts = [
		'settlementdate' => 'date'
	];

	public function members() {
		return $this->hasMany(Familymember::class);
	}

	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}

	public function nationality() {
		return $this->belongsTo(Country::class);
	}

	public function settlementtype() {
		return $this->belongsTo(Settlementtype::class);
	}

	public function settlementstatus() {
		return $this->belongsTo(Settlementstatus::class);
	}

	public function followups() {
		return $this->hasMany(Familyfollowup::class);
	}

	public function contacts() {
		return $this->hasMany(Familycontact::class);
	}

	public function documents() {
		return $this->hasMany(Familydoc::class);
	}

	public function sourceprovince() {
		return $this->belongsTo(Province::class, 'fromprovince_id');
	}

	public function destinationprovince() {
		return $this->belongsTo(Province::class, 'toprovince_id' );
	}

	public function impacts() {
		return $this->belongsToMany(FamilyImpact::class, 'family_impact', 'family_id', 'familyimpact_id')
			->using(FamilyImpactPivot::class)
			->withPivot('date', 'description');
	}

}
