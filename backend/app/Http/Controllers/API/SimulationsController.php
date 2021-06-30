<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SimulationService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\CreateSimulationRequest;

class SimulationsController extends Controller
{
     /**
     * @var JSimulationServise
     */
    private $simulation_service;

    public function __construct(SimulationService $simulation_service)
    {
        $this->simulation_service = $simulation_service;
    }
    /**
     *シュミレーション一覧取得
     * 
     *  @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $response = $this->simulation_service->getUserSimulations();

        if ($response["status"] === config("const.response.success")) {
            return $this->successResponse($response["data"]);
        } else {
            return $this->errorResponse();
        }
    }

    /**
     *シュミレーションの作成
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSimulationRequest $request)
    {   
        $response = $this->simulation_service->createSimulation($request->only(['simulator_title', 'inquiries']));

        if ($response) {
            return $this->successResponse();
        } else {
            return $this->errorResponse();
        }
    }

    /**
     *シュミレーション詳細取得
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {   
        $response = $this->simulation_service->getSimulationDetail($id);
        if ($response["status"] === config("const.response.success")) {
            return $this->successResponse($response["data"]);
        } else {
            return $this->errorResponse();
        }
    }
}
