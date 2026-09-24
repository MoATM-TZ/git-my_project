<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Program extends Model
{
    protected $fillable = ['department_id', 'name', 'level'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function courseClasses(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }

    public function resources(): HasManyThrough
    {
        return $this->hasManyThrough(Resource::class, CourseClass::class);
    }
}
