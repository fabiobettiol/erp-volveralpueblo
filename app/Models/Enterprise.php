<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;

    protected $casts = [
        'date' => 'date'
    ];

    public function entrepreneur () {
        return $this->belongsTo(Entrepreneur::class);
    }

    public function locality () {
        return $this->belongsTo(Locality::class);
    }

    public function municipality () {
        return $this->belongsTo(Municipality::class);
    }

    public function province () {
        return $this->belongsTo(Province::class);
    }

    public function community () {
        return $this->belongsTo(Community::class);
    }
}
