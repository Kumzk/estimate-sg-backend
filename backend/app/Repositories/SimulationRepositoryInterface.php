<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface SimulationRepositoryInterface
{
    public function getUserSimulations(): Collection;

    public function createSimulation(array $params): bool;
}
