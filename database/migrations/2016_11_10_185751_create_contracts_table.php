<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'contracts', function ( Blueprint $table ) {
            $table->uuid( 'id' );
            $table->primary( 'id' );
            $table->uuid( 'user_id' );
            $table->uuid( 'unit_id' );
            $table->uuid( 'application_id' );
            $table->uuid( 'document_id' )->nullable();
            $table->enum('status', ['pending', 'declined', 'accepted', 'cancelled']);
            $table->dateTime( 'start_date' );
            $table->dateTime( 'end_date' );
            $table->text( 'secure_link' )->nullable();
            $table->dateTime('approved')->nullable()->default(null);

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
        Schema::dropIfExists( 'contracts' );
    }
}
