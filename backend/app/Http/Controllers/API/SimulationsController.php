<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\SimulationRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SimulationsController extends Controller
{
    protected $user;

    public function __construct(SimulationRepositoryInterface $simulation_repository)
    {
        $this->user = Auth::user();
        $this->simulation_repository = $simulation_repository;
    }

    public function index()
    {
        return response()->json([
                'data' => $this->simulation_repository->getUserSimulations($this->user),
                'success' => true
            ], 200);
    }
}
