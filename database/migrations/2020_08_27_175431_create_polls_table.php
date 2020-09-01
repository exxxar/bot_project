<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string("question");
            $table->json("media")->nullable();
            $table->json("options");
            $table->boolean("is_anonymous")->default(false);
            $table->boolean("allows_multiple_answers")->default(false);
            $table->integer("correct_option_id")->nullable();
            $table->integer("close_date")->nullable();
            $table->string("type")->default("regular");
            $table->unsignedInteger("created_by_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
