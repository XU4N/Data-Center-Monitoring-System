<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('threshold_category');
            $table->string('units');
            $table->float('min_critical_value');
            $table->float('min_warning_value');
            $table->float('normal_value');
            $table->float('max_warning_value');
            $table->float('max_critical_value');
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
        Schema::drop('thresholds');
    }
}
