<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface SimulationRepositoryInterface
{
    public function getUserSimulations(User $user): Collection;
}
