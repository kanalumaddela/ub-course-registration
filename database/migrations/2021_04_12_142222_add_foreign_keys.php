<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('cascade');
        });

        Schema::table('course_sections', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('catalog_id')->references('id')->on('catalogs');
        });
        Schema::table('course_section_schedules', function (Blueprint $table) {
            $table->foreign('course_section_id')->references('id')->on('course_sections')
                ->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings');
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('course_section_id')->references('id')->on('course_sections')
                ->onDelete('cascade');
        });

        Schema::table('department_advisors', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('conversation_id')->references('id')->on('conversations')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_department_id_foreign');
        });

        Schema::table('course_sections', function (Blueprint $table) {
            $table->dropForeign('course_sections_course_id_foreign');
            $table->dropForeign('course_sections_catalog_id_foreign');
        });
        Schema::table('course_section_schedules', function (Blueprint $table) {
            $table->dropForeign('course_section_schedules_course_section_id_foreign');
            $table->dropForeign('course_section_schedules_building_id_foreign');
        });

        Schema::table('course_section_schedules', function (Blueprint $table) {
            $table->dropForeign('student_registrations_user_id_foreign');
            $table->dropForeign('student_registrations_course_section_id_foreign');
        });

        Schema::table('department_advisors', function (Blueprint $table) {
            $table->dropForeign('department_advisors_department_id_foreign');
            $table->dropForeign('department_advisors_user_id_foreign');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign('conversations_author_id_foreign');
            $table->dropForeign('conversations_recipient_id_foreign');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_conversation_id_foreign');
            $table->dropForeign('messages_user_id_foreign');
        });
    }
}
