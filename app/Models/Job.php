<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class Job extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function cdr()
    {
        return $this->belongsTo(Cdr::class);
    }

    public function jobform()
    {
        return $this->belongsTo(Jobform::class);
    }

    public function jobownership()
    {
        return $this->belongsTo(Jobownership::class);
    }
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

}
