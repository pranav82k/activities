<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('activity_id');
            $table->string('title', 120);
            $table->text('description');
            $table->string('featured_image', 120);
            $table->tinyInteger('particular_edited')->default(0)->comment('Admin Edited For Particular User');
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('activity_id')->references('id')->on('activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['activity_id']);
        });

        Schema::dropIfExists('user_activities');
    }
}
