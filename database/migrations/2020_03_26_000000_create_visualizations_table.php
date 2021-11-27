<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisualizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visualizations', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_id')->nullable();
            $table->string('authority')->default('combined');
            $table->string('background')->nullable();
            $table->string('icon')->nullable();
            $table->string('xtitle')->nullable();
            $table->string('ytitle')->nullable();
            $table->boolean('legend')->default(1);
            $table->text('viz_state');
            $table->text('pivot')->nullable();
            $table->string('title')->default('Some Title');
            $table->string('type')->default('line');
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->integer('w')->default(4);
            $table->integer('h')->default(17);
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
        Schema::dropIfExists('visualizations');
    }
}
