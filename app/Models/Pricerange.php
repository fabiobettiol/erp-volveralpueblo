<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricerange extends Model
{
    use SoftDeletes;

	protected $table = 'priceranges';

}
