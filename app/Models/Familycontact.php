<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familycontact extends Model
{
    use SoftDeletes;

	protected $casts = [
		'date' => 'date',
	];

    public function cdr() {
        return $this->belongsTo(Cdr::class);
    }

    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }    
}
