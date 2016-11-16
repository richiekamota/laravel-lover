<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->uuid('location_id');
            $table->integer('cost');
            $table->boolean('wifi')->default(0);
            $table->boolean('electricity')->default(0);
            $table->boolean('dstv')->default(0);
            $table->boolean('parking_car')->default(0);
            $table->boolean('parking_bike')->default(0);
            $table->boolean('storeroom')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_types');
    }
}
