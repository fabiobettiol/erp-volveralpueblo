<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cdrnew extends Model
{

    use SoftDeletes;

    protected $casts = [
        'date' => 'date'
    ];

    public function cdr()
    {
        return $this->belongsTo(Cdr::class);
    }
}
