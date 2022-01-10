<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatementTemplate extends Model
{
    protected $fillable = ['name', 'actor', 'verb', 'object', 'result', 'context', 'timestamp', 'authority'];

    protected $table = 'statement_templates';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';
}
