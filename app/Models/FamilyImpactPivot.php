<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Relations\Pivot;
 
class FamilyImpactPivot extends Pivot
{
    protected $casts = [
        'date' => 'date'
    ];
}
