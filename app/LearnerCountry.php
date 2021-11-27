<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearnerCountry extends Model
{
    protected $table = 'learners_countries';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    public function learners()
    {
        return $this->hasMany(Learner::class, 'country');
    }
}
