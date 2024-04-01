<?php

namespace App\Models;

use App\Models\registerMediaConversions;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Business extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

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

    public function cdr()
    {
        return $this->belongsTo(Cdr::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function ownership()
    {
        return $this->belongsTo(Ownership::class);
    }
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function pricerange()
    {
        return $this->belongsTo(Pricerange::class);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);

        $this->addMediaConversion('resized')
            ->border(15, 'white', Manipulations::BORDER_OVERLAY)
            ->watermarkPadding(30, 30, Manipulations::UNIT_PIXELS)
            ->watermark('storage/logos/water-mark-coceder.png')
            ->watermarkOpacity(50)
            ->width(1024)
            ->height(1024);
    }    
}
