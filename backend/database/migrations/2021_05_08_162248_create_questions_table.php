<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table): void{
            $table->id();
            $table->foreignId('simulation_id')->constrained();
            $table->string('title');
            $table->integer('position_x');
            $table->integer('position_y');
            $table->integer('node_type');
            $table->integer('previous_question_id')->default(0);
            $table->integer('previous_option_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
}
