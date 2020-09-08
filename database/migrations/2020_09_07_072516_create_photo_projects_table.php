<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_projects', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("image",1000)->default('');
            $table->string("date")->default('');
            $table->string("time")->default('');
            $table->text("place");
            $table->string("photographer")->default('');
            $table->string("teacher")->default('');
            $table->string("sponsor")->default('');
            $table->double("price")->default(0.0);
            $table->boolean("is_active")->default(true);
            $table->integer("position")->default(0);
            $table->unsignedInteger("created_by_id")->nullable();
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
        Schema::dropIfExists('photo_projects');
    }
}
