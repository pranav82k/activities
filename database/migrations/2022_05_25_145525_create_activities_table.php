<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('1. Globally 2. User');
            $table->string('title', 120);
            $table->text('description');
            $table->string('featured_image', 120);
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('activities', function (Blueprint $table) {
        //     $table->dropIndex(['type']);
        // });

        Schema::dropIfExists('activities');
    }
}
