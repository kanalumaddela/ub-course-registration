<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->unsignedSmallInteger('seats')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('faculty')->nullable();
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('term_id');
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
        Schema::dropIfExists('course_sections');
    }
}
