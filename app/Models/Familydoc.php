<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familydoc extends Model
{
    use SoftDeletes;

    public function family() {
        return $this->belongsTo(Family::class);
    }
}
