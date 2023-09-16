<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }    

    public function cdr()
    {
        return $this->belongsTo(Cdr::class);
    }
}
