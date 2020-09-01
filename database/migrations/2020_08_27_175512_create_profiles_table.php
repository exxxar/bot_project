<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->default('');
            $table->string('phone')->default('');

            $table->string('height')->default('');
            $table->string('age')->default('');
            $table->string('sex')->default('');

            $table->string('model_school_education')->default('');
            $table->string('wish_learn')->default('');
            $table->string('city')->default('');

            $table->unsignedInteger("user_id");

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
        Schema::dropIfExists('profiles');
    }
}
