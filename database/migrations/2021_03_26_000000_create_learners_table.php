<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learners', function (Blueprint $table) {
            $table->string('username')->primary();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->string('group')->references('name')->on('learners_groups')->nullable();
            $table->string('country')->references('name')->on('learners_countries')->nullable();
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
        Schema::dropIfExists('learners');
    }
}
