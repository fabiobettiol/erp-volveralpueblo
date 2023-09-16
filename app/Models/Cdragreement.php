<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cdragreement extends Model
{
    use SoftDeletes;

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    public function cdr() {
        return $this->belongsTo(Cdr::class);
    }    
}
