<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name');
            //$table->integer('object_type_id')->unsigned();
            $table->string('address');
            //$table->integer('place_id')->unsigned();
            $table->time('check_in');
            $table->time('check_out');

            $table->float('lat', 10, 6)->nullable();
            $table->float('lon', 10, 6)->nullable();

            $table->smallInteger('capacity')->unsigned()->default(0);
            $table->smallInteger('capacity_extra')->unsigned()->default(0);

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
        Schema::dropIfExists('objects');
    }
}
