<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('options')->truncate();
        DB::table('questions')->truncate();
        DB::table('simulations')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user = \App\Models\User::firstOrCreate([
            'cognito_sub' => '89a88b81-be7e-4009-8542-c43afc2b9817',
        ], [
            'email' => 'test1@example.com', //[password] => Password-1
        ]);

        for ($i = 1; $i < 5; ++$i) {
            $user->simulations()->create([
                'simulator_title' => 'web制作見積もりシュミレーション'.$i,
                'inquiries' => 'test'.$i.'@gmail.com',
            ]);
        }

        $questions = [
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 655, "position_y" => 136, "node_type" => 1],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 374, "position_y" => 327, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 307, "position_y" => 535, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 374, "position_y" => 327, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 456, "position_y" => 698, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 828, "position_y" => 274, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 875, "position_y" => 666, "node_type" => 2],
            ["title" => "Googleマップなど、他社のツールを使用しますか？", "position_x" => 930, "position_y" => 633, "node_type" => 2],
        ];

        $options = [
            ["answer" => "はい", "description" => "回答の説明,回答の説明", "price" => 200000, "image_path" => "https://dummyimage.com/600x400/000/0011ff"],
            ["answer" => "いいえ", "description" => "回答の説明,回答の説明", "price" => 1000, "image_path" => "https://dummyimage.com/600x400/000/49f596"],
            ["answer" => "いいえ", "description" => "回答の説明,回答の説明", "price" => 0, "image_path" => "https://dummyimage.com/600x400/000/f59649"],
        ];

        $simulations = $user->simulations()->get();

        foreach ($simulations as $simulation) {
            foreach ($questions as $question) {
                $question = $simulation->questions()->create($question);
                foreach ($options as $option) {
                    $question->options()->create($option);
                }
            }
        }

        foreach ($simulations as $simulation) {
            $questions = $simulation->questions()->get();

            $first_question = $questions[0];
            $first_question_options = $first_question->options()->get();

            $second_question = $questions[1];
            $second_question_options = $second_question->options()->get();
            $second_question->previous_question_id = $first_question->id;
            $second_question->save();

            $third_question = $questions[2];
            $third_question->previous_question_id = $second_question->id;
            $third_question->previous_option_id = $second_question_options[0]->id;
            $third_question->save();

            $third_question = $questions[3];
            $third_question->previous_question_id = $second_question->id;
            $third_question->save();

            $third_question = $questions[4];
            $third_question->previous_question_id = $second_question->id;
            $third_question->previous_option_id = $second_question_options[2]->id;
            $third_question->save();

            $sixth_question = $questions[5];
            $sixth_question_options = $sixth_question->options()->get();
            $sixth_question->previous_question_id = $first_question->id;
            $sixth_question->previous_option_id = $first_question_options[2]->id;
            $sixth_question->save();

            $seventh_question = $questions[6];
            $seventh_question->previous_question_id = $sixth_question->id;
            $seventh_question->previous_option_id = $sixth_question_options[2]->id;
            $seventh_question->save();

            $eighth_question = $questions[6];
            $eighth_question->previous_question_id = $sixth_question->id;
            $eighth_question->save();
        }
    }
}
