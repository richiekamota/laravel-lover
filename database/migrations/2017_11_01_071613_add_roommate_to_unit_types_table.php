<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoommateToUnitTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('unit_room_mate')->after('unit_fee_split')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('applications', 'unit_room_mate'))
        {
            Schema::table('applications', function (Blueprint $table)
            {
                $table->dropColumn('unit_room_mate');
            });
        }
    }
}
