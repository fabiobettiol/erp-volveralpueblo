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

class House extends Model implements HasMedia {
	use SoftDeletes;
	use InteractsWithMedia;

	public function community() {
		return $this->belongsTo(Community::class);
	}

	public function province() {
		return $this->belongsTo(Province::class);
	}

	public function municipality() {
		return $this->belongsTo(Municipality::class);
	}

	public function locality() {
		return $this->belongsTo(Locality::class);
	}

	public function cdr() {
		return $this->belongsTo(Cdr::class);
	}

	public function heating() {
		return $this->belongsTo(Heating::class);
	}

	public function stove() {
		return $this->belongsTo(Stove::class);
	}

	public function facilities() {
		return $this->hasMany(Facility::class);
	}

	public function waters() {
		return $this->belongsTo(Water::class);
	}

	public function form() {
		return $this->belongsTo(Form::class);
	}
	public function pricerange() {
		return $this->belongsTo(Pricerange::class);
	}

	public function status() {
		return $this->belongsTo(Status::class);
	}
	public function ownership() {
		return $this->belongsTo(Ownership::class);
	}

	public function area() {
		return $this->belongsTo(Arearange::class);
	}

	public function registerMediaConversions(Media $media = null): void{
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
