<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function cdrs()
    {
        return $this->hasMany(Cdr::class);
    }
}
