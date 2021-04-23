<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSectionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_section_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_section_id');
            $table->string('type')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('days')->nullable();
            $table->boolean('is_online')->default(true);
            $table->unsignedBigInteger('building_id')->nullable();
            $table->string('room')->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::statement('alter table course_section_schedules add check (end_time > start_time);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_section_schedules');
    }
}
