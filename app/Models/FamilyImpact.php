<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyImpact extends Model
{
    use SoftDeletes;

    protected $table = 'familyimpacts';

    public function families() {
        return $this->belongsToMany(Family::class, 'family_impact', 'familyimpact_id', 'family_id')
            ->using(FamilyImpactPivot::class)
            ->withPivot('date', 'description');
    }

    public function type() {
        return $this->belongsTo(FamilyImpactType::class, 'impacttype_id');
    }

    public function scopes() {
        return $this->belongsToMany(FamilyImpactScope::class, 'familyimpact_familyimpactscope', 'familyimpact_id', 'familyimpactscopes_id');
    }
}
