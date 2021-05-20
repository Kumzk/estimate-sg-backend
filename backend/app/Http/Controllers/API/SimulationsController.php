<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\SimulationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\CreateSimulationRequest;

class SimulationsController extends Controller
{
    protected $user;

    public function __construct(SimulationRepositoryInterface $simulation_repository)
    {
        $this->user = Auth::user();
        $this->simulation_repository = $simulation_repository;
    }
    /**
     *シュミレーションの作成
     * 
     *  @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
                'data' => $this->simulation_repository->getUserSimulations($this->user),
                'success' => true
            ], 200);
    }

    /**
     *シュミレーションの作成
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSimulationRequest $request)
    {   
        $this->simulation_repository->createSimulation($request->only(['simulator_title', 'inquiries']));
        return response()->json(['success' => true], 200);
    }
}
