<?php

namespace App\Services;

use App\Models\Option;
use App\Models\Question;
use App\Models\Simulation;
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
     *シュミレーションの編集
     * 
     * @param array $params
     * @param int $id
     * @return array
     */
    public function updateSimulation(array $params, int $id): array
    {
        $data = [];
        $data["status"] = config("const.response.error"); 
        $data["data"] = [];
        try{
            $simulation = $this->currentUser->simulations()->where("id", $id)->first();
            
            if ($simulation == null) {
                throw new \Exception();
            }

            $simulation->update($params);

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
            
            $simulation_questions = $simulation_original->questions()->get();
            
            $new_simulation_ids = [];
            $new_option_ids = [];
            $new_option_ids[0] = 0;
            while(!(count($new_simulation_ids) == count($simulation_questions))){
                foreach($simulation_questions as $question) {
                    if (!array_key_exists($question->id, $new_simulation_ids)
                        && ($question->previous_question_id == 0 
                        || array_key_exists($question->previous_question_id, $new_simulation_ids))) {
                        
                        $previous_question_id = 0;
                        if (isset($new_simulation_ids[$question->previous_question_id])) {
                            $previous_question_id = $new_simulation_ids[$question->previous_question_id];
                        }

                        $new_question_date = $this->duplicateQustion($simulation, $question, $new_option_ids, $previous_question_id);

                        $new_simulation_ids[$question->id] = $new_question_date['new_question_id'];
                        $new_option_ids = $new_question_date['new_option_ids'];
                    }
                }
            }

            $data["status"] = config("const.response.success");
            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }


    /**
     * 質問の複製
     * 
     * @param Simulation $simulation
     * @param Question $question
     * @param array $new_option_ids
     * @param int $previous_question_id
     * @return array
     */
    private function duplicateQustion(Simulation $simulation, Question $question, array $new_option_ids,int $previous_question_id = 0): array
    {   
        $data = [];
        $new_question = $question->replicate();
        $new_question->previous_option_id = $new_option_ids[$question->previous_option_id];
        $new_question->previous_question_id = $previous_question_id;
        $new_question = $new_question->makeVisible(['position_x', 'position_y', 'previous_option_id'])->toArray();
        $new_question = $simulation->questions()->create($new_question);

        $options = $question->options()->get();
        foreach($options as $option){
            $new_option = $option->replicate();
            $new_option = $new_question->options()->create($new_option->toArray());
            $new_option_ids[$option->id] = $new_option->id;
        }

        $data['new_question_id'] = $new_question->id;
        $data['new_option_ids'] = $new_option_ids;
        return $data;
    }
}   