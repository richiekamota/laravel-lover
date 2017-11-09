<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractDeclineReasonToApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->text("contract_decline_reason")->nullable();
        });

        DB::statement("ALTER TABLE application_events MODIFY COLUMN action ENUM('Application approved', 'Application pending', 'Application declined', 'Contract Accepted', 'Application Cancelled','Contract declined')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('contract_decline_reason');
        });
    }
}
