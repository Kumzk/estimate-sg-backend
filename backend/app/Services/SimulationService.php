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
     * @return array
     */
    public function getUserSimulations(): array
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
     * @return array
     */
    public function createSimulation(array $params): array
    {   
        $data = [];
        $data["status"] = config("const.response.error"); 
        $data["data"] = [];

        try{           
            $data['data'] = $this->currentUser->simulations()->create([
                'simulator_title' => $params['simulator_title'],
                'inquiries' => $params['inquiries']
                ]);
            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e) {
            return $data;
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
        $data["data"]["questions"] = [];
        $data["data"]["relations"] = [];

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
                $question_data = [];
                $question_data["position"]["x"] = 0;
                $question_data["position"]["y"] = 0;
                $question_data["options"] = [];
                $question_options = [];

                $question_data = $question->toArray();
                $question_data["position"]["x"] = $question->position_x;
                $question_data["position"]["y"] = $question->position_y;
                $question_options = $options->where("question_id", $question->id)->toArray();
                foreach ($question_options as $option){
                    $question_data["options"][] = $option;
                }
                $data["data"]["questions"][] = $question_data;
                if (!($question->node_type == config("const.node_type.selectorInputNode"))){
                    $data["data"]["relations"][] = [
                        "source_id" => $question->previous_question_id,
                        "target_id" => $question->id
                    ];
                }
            }

            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e)  {
            return $data;
        }
    }

    /**
     *シュミレーションの削除
     * 
     * @param int $id
     * @return array
     */
    public function deleteSimulation(int $id): array
    {
        $data = [];
        $data["status"] = config("const.response.error"); 
        $data["data"] = [];
        try{
            $simulation = $this->currentUser->simulations()->where("id", $id)->first();
            
            if ($simulation == null) {
                throw new \Exception();
            }

            $simulation->delete();

            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }

    /**
     *シュミレーションの複製
     * 
     * @param array $params
     * @return array
     */
    public function duplicateSimulation(array $params): array
    {
        $data = [];
        $data["status"] = config("const.response.error"); 
        $data["data"] = [];
        try{
            $simulation_id = $params['id'];
            $simulator_title = $params['simulator_title'];
            $inquiries = $params['inquiries'];

            $simulation_original = $this->currentUser->simulations()->where("id", $simulation_id)->first();

            
            if ($simulation_original == null) {
                throw new \Exception();
            }
            
            $simulation = $this->currentUser->simulations()->create([
                'simulator_title' => $simulator_title,
                'inquiries' => $inquiries
            ]); 
            
            // $simulation_questions = $simulation_original

            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }
}