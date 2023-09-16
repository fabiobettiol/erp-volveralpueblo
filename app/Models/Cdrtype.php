<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cdrtype extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function cdrs()
    {
        return $this->hasMany(Crd::class);
    }
}
