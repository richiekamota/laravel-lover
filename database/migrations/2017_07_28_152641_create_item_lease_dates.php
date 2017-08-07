<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemLeaseDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'item_lease_dates', function ( Blueprint $table ) {
            $table->uuid( 'id' );
            $table->primary( 'id' );
            $table->uuid( 'user_id' )->nullable();
            $table->text( 'leasee_name' )->nullable();
            $table->text( 'item_name' )->nullable();
            $table->uuid( 'item_id' );
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
        Schema::dropIfExists('item_lease_dates');
    }
}
