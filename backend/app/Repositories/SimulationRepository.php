<?php

namespace App\Repositories;

use App\Models\Simulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

class SimulationRepository implements SimulationRepositoryInterface
{   
    /** @var User */
    private $currentUser;

    public function __construct()
    {
        $this->currentUser = Auth::user();
    }

    /**
     * ユーザーのシュミレーションを全取得.
     * 
     * @return Collection
     */
    public function getUserSimulations(): Collection
    {
        return $this->currentUser->simulations;
    }

    /**
     *シュミレーションの作成
     * 
     * @param array $params
     * @return bool
     */
    public function createSimulation(array $params): bool
    {
        try{
            $this->currentUser->simulations()->create($params);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}