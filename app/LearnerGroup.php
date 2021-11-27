<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearnerGroup extends Model
{
    protected $table = 'learners_groups';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    public function learners()
    {
        return $this->hasMany(Learner::class, 'group');
    }
}
