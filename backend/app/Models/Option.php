<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['question_id', 'created_at', 'updated_at'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
