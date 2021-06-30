<?php

namespace App\Services;

use App\Models\Option;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SimulationService
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
     * @return Array
     */
    public function getUserSimulations(): Array
    {   
        $data = [];
        $data["status"] = config("const.response.error");
        $data["data"] = [];
        
        try{
            $data["data"] = $this->currentUser->simulations->all();
            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e)  {
            return $data;
        }
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
            return false;
        }
    }

    /**
     *シュミレーションの取得
     * 
     * @param int $id
     * @return array
     */
    public function getSimulationDetail(int $id): array
    {
        $data = [];
        $data["status"] = config("const.response.error"); 
        $data["data"] = [];

        $simulation = null;
        $questions = [];
        $question_ids = [];
        $options = [];

        try{
            $simulation = $this->currentUser->simulations()->where("id", $id)->first();
            
            if ($simulation == null) {
                throw new \Exception();
            }

            $questions = $simulation->questions()->get();

            $question_ids = $questions->pluck('id')->all();

            $options = Option::whereIn("question_id", $question_ids)->get();

            foreach($questions as $question){
                $question["options"][] = $options->where("question_id", $question->id)->all();
                $data["data"]["questions"][] = $question;
                if (!($question->node_type == config("const.node_type.selectorInputNode")))
                $data["data"]["relations"][] = [
                    "source_id" => $question->previous_question_id,
                    "target_id" => $question->id
                ];
            }

            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e)  {
            return $data;
        }
    }
}