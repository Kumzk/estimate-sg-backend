<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SimulationRepository implements SimulationRepositoryInterface
{
    /**
     * ユーザーのシュミレーションを全取得.
     * 
     * @param  User $user
     * @return Collection
     */
    public function getUserSimulations(User $user): Collection
    {
        return $user->simulations;
    }
}