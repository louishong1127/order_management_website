<?php

namespace App\Models;

use App\Models\Scopes\EnabledScope;
use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    protected $table = 'case_types';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    protected $fillable = [
        'name', 'slug','enabled'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EnabledScope());
    }
}
