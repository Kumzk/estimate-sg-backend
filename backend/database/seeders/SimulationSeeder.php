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
            'name' => 'test1@example.com',
        ], [
            'cognito_sub' => '89a88b81-be7e-4009-8542-c43afc2b9817',
        ]);

        for ($i = 1; $i < 5; ++$i) {
            $user->simulations()->create([
                'embedded_code' => "<iframe width='100%'height='100%' src='〜〜' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'allowfullscreen></iframe>",
                'title' => 'web制作見積もりシュミレーション'.$i,
                'inquiries' => 'test'.$i.'@gmail.com',
            ]);
        }

        $questions = [
            'ECサイトかHPどちらを作りますか？', //0
            '制作方法はどちらにしますか',      //1
            'デザイン制作は依頼しますか？',    //2
            'デザイン制作は依頼しますか？',    //3
            '何ページのHPを作成しますか？',   //4
        ];

        $items = [];

        $simulations = $user->simulations()->get();

        $now = Carbon::now();

        foreach ($simulations as $simulation) {
            foreach ($questions as $question) {
                $items[] = [
                    'title' => $question,
                    'simulation_id' => $simulation->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('questions')->insert($items);

        foreach ($simulations as $simulation) {
            $questions = $simulation->questions()->get();

            $options = [
                //ECサイトかHPどちらを作りますか
                ['question_id' => $questions[0]->id, 'next_question_id' => $questions[1]->id, 'answer' => 'ECサイト構築', 'description' => 'ECサイトを構築する', 'price' => 400000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[0]->id, 'next_question_id' => $questions[3]->id, 'answer' => 'HP制作', 'description' => 'HPやLPを制作する', 'price' => 100000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],

                //制作方法はどちらにしますか
                ['question_id' => $questions[1]->id, 'next_question_id' => $questions[2]->id, 'answer' => 'ECCUBEを使用する', 'description' => 'EC構築パッケージを使用する', 'price' => 100000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[1]->id, 'next_question_id' => $questions[2]->id, 'answer' => 'フルスクラッチで制作する', 'description' => '1からシステム構築をする', 'price' => 1000000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],

                //デザイン制作は依頼しますか？
                ['question_id' => $questions[2]->id, 'next_question_id' => 0, 'answer' => 'コンセプトから依頼したい', 'description' => '企画から参加して欲しい', 'price' => 100000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[2]->id, 'next_question_id' => 0, 'answer' => 'デザインは自分たちで行う', 'description' => 'デザイナーは自分で探してくる', 'price' => 50000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[2]->id, 'next_question_id' => 0, 'answer' => 'わからない', 'description' => 'わからない', 'price' => 0, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],

                //デザイン制作は依頼しますか？
                ['question_id' => $questions[3]->id, 'next_question_id' => $questions[4]->id, 'answer' => 'コンセプトから依頼したい', 'description' => '企画から参加して欲しい', 'price' => 100000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[3]->id, 'next_question_id' => $questions[4]->id, 'answer' => 'デザインは自分たちで行う', 'description' => 'デザイナーは自分で探してくる', 'price' => 50000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[3]->id, 'next_question_id' => $questions[4]->id, 'answer' => 'わからない', 'description' => 'わからない', 'price' => 0, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],

                //何ページのHPを作成しますか？
                ['question_id' => $questions[4]->id, 'next_question_id' => 0, 'answer' => '1 ~ 5', 'description' => '1 ~ 5 ページのサイト', 'price' => 100000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[4]->id, 'next_question_id' => 0, 'answer' => '6 ~ 10', 'description' => '6 ~ 10ページにサイト', 'price' => 500000, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
                ['question_id' => $questions[4]->id, 'next_question_id' => 0, 'answer' => 'わからない', 'description' => 'わからない', 'price' => 0, 'image_path' => 'https://eterein.co.jp/estimate/static/images/ec/ec.png', 'created_at' => $now, 'updated_at' => $now],
            ];

            DB::table('options')->insert($options);
        }
    }
}
