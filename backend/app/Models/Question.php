<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id'];
    protected $hidden = ['simulation_id', 'previous_option_id', 'position_x', 'position_y','created_at', 'updated_at'];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public static function boot()
    {
    parent::boot();

        static::deleting(function($question) {
            $options = $question->options()->get();

            foreach ($options as $option) {
                $option->delete();
            }
        });
    }
}
