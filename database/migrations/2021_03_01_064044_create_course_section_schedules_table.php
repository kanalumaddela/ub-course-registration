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
            $table->unsignedInteger('course_section_id');
            $table->string('days');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_online')->default(true);
            $table->unsignedInteger('location_id')->nullable();
            $table->string('room_number')->nullable();
            $table->string('type')->nullable();
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
