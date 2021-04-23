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
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('catalog_id');
            $table->timestamps();

//            $table->unique(['number', 'course_id', 'catalog_id']);
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
