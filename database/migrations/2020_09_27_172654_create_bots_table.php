<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->string('bot_url',191)->unique();
            $table->string('token_prod')->default('');
            $table->string('token_dev')->default('');
            $table->string('description')->default('');
            $table->string('bot_pic',1000)->default('');
            $table->boolean('is_active')->default(false);
            $table->double('money')->default(0);
            $table->double('money_per_day')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            if (env("DB_CONNECTION") == 'mysql') {
                $table->foreign('user_id')->references('id')->on('users');
            }
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
        Schema::dropIfExists('bots');
    }
}
