<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Create locations database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uuid', 30);
            $table->longText('reference')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE locations ADD geopoint POINT');
    }

    /**
     * Drop locations database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }
}
