<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demandantdoc extends Model
{
    use SoftDeletes;

    public function demandant() {
         return $this->belongsTo(Demandant::class);
    }
}
