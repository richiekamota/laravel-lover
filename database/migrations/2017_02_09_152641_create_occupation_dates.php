<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccupationDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'occupation_dates', function ( Blueprint $table ) {
            $table->uuid( 'id' );
            $table->primary( 'id' );
            $table->uuid( 'contract_id' );
            $table->uuid( 'application_id' );
            $table->uuid( 'unit_id' );
            $table->enum('status', ['pending', 'declined', 'approved','cancelled']);
            $table->dateTime( 'start_date' );
            $table->dateTime( 'end_date' );
            $table->timestamps();
            $table->softDeletes();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('occupation_dates');
    }
}
