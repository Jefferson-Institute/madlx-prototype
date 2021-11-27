<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    protected $fillable = ['username', 'name', 'email', 'bio', 'group', 'country'];

    protected $primaryKey = 'username';

    public $incrementing = false;

    protected $keyType = 'string';

    public function grades(){
        return $this->hasMany('LearnerGrade', 'username');
    }
}
