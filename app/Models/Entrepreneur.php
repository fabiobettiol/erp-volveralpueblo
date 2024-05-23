<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrepreneur extends Model
{
    use SoftDeletes;

    protected $casts = [
        'dob' => 'date'
    ];

    public function enterprises () {
        return $this->hasMany(Enterprise::class);
    }

    public function locality () {
        return $this->belongsTo(Locality::class);
    }

    public function province () {
        return $this->belongsTo(Province::class);
    }

    public function community () {
        return $this->belongsTo(Community::class);
    }
}
