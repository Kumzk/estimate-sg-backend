<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The preferences that we don't want to show to the client.
     */
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class);
    }
}
