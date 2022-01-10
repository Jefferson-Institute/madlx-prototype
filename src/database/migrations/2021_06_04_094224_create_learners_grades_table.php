<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLearnersGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learners_grades', function (Blueprint $table) {
            $table->string('username')->primary()->references('username')->on('learners');
            $table->string('oid');
            $table->string('objective');
            $table->string('grade');
            $table->date('date');
            $table->timestamps();
        });

        DB::unprepared('ALTER TABLE `learners_grades` DROP PRIMARY KEY, ADD PRIMARY KEY (  `username` ,  `oid` )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learners_grades');
    }
}
