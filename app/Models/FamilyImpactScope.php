<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyImpactScope extends Model
{
    protected $table = 'familyimpactscopes';

    public function impact() {
        return $this->belongsToMany(FamilyImpact::class, 'familyimpact_familyimpactscope', 'familyimpcopes_idacts', 'familyimpact_id' );
    }
}
