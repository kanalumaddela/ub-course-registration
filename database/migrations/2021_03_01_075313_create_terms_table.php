<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_full')->virtualAs('concat_ws(\' \', name, concat(upper(substring(semester, 1, 1)), substring(semester, 2)), year)');
            $table->enum('semester', ['spring', 'summer', 'fall', 'winter']);
            $table->year('year');
            $table->date('start');
            $table->date('end');
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
        Schema::dropIfExists('catalogs');
    }
}
