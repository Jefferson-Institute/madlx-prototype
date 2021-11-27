<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStatementTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statement_templates', function (Blueprint $table) {
            $table->string('name')->primary();
            $table->text('actor')->nullable();
            $table->text('verb')->nullable();
            $table->text('object')->nullable();
            $table->text('result')->nullable();
            $table->text('context')->nullable();
            $table->text('timestamp')->nullable();
            $table->text('authority')->nullable();
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
        Schema::dropIfExists('statement_templates');
    }
}
