<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateApplicationEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE application_events CHANGE COLUMN action action ENUM('Application changes requested', 'Application approved', 'Application pending', 'Application declined', 'Contract Accepted', 'Application Cancelled', 'Contract Declined')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE application_events CHANGE COLUMN action action ENUM('Application approved', 'Application pending', 'Application declined', 'Contract Accepted', 'Application Cancelled', 'Contract Declined')");
    }
}
