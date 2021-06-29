<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
