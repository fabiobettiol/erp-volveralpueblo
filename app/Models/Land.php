<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use App\Models\registerMediaConversions;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class Land extends Model implements HasMedia
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

    public function ownership()
    {
        return $this->belongsTo(Ownership::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function landtype()
    {
        return $this->belongsTo(Landtype::class);
    }
    public function landuse()
    {
        return $this->belongsTo(Landuse::class);
    } 
    public function pricerange()
    {
        return $this->belongsTo(Pricerange::class);
    }
    public function arearange()
    {
        return $this->belongsTo(Arearange::class);
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
