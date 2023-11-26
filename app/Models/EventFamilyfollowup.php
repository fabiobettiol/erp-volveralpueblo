<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFamilyfollowup extends Model
{
    use HasFactory;

    protected $table = 'event_family_followups';    

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
