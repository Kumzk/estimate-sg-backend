<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SimulationService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\CreateSimulationRequest;
use App\Http\Requests\API\DuplicateSimulationRequest;
use Symfony\Component\HttpFoundation\Response;

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
    public function index(): Response
    {   
        $response = $this->simulation_service->getUserSimulations();

        return $this->createResponse($response["status"], $response["data"]);
    }

    /**
     *シュミレーションの作成
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSimulationRequest $request): Response
    {   
        $response = $this->simulation_service->createSimulation($request->only(['simulator_title', 'inquiries']));

        return $this->createResponse($response["status"]);
    }

    /**
     *シュミレーション詳細取得
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): Response
    {   
        $response = $this->simulation_service->getSimulationDetail($id);

        return $this->createResponse($response["status"], $response["data"]);
    }

    /**
     *シュミレーション削除
     * 
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id): Response
    {   
        $response = $this->simulation_service->deleteSimulation($id);

        return $this->createResponse($response["status"]);
    }

    /**
     *シュミレーションの複製
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(DuplicateSimulationRequest $request): Response
    {
        $response = $this->simulation_service->duplicateSimulation($request->only(['id', 'simulator_title', 'inquiries']));
        
        return $this->createResponse($response["status"]);
    }
}
