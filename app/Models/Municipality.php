<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use SoftDeletes;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function cdrs()
    {
        return $this->hasMany(Cdr::class);
    }

    public function localities() {
        return $this->hasMany(Locality::class);
    }
}
