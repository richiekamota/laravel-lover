<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('user_id');
            $table->enum('status', ['draft', 'open', 'pending', 'declined', 'approved']);
            $table->enum('step', [1,2,3,4,5,6,7,8]);

            // Step 1
            $table->integer('sa_id_number', 191)->nullable();
            $table->integer('passport_number', 191)->nullable();
            $table->dateTime('dob');
            $table->string('nationality', 191);
            $table->string('phone_mobile', 191);
            $table->string('phone_home ', 191);
            $table->string('phone_work ', 191);
            $table->text('current_address');
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->enum('married_type ', ['In community of property', 'ANC', 'Accural System']);
            // Step 2
            $table->boolean('current_property_owner');
            $table->enum('rental_time', ['12', '24', '36', '48'])->nullable();
            $table->integer('rental_amount')->nullable();
            $table->string('rental_name', 191)->nullable();
            $table->string('rental_phone_home', 191)->nullable();
            $table->string('rental_phone_mobile', 191)->nullable();
            // Step 3
            $table->boolean('selfemployed');
            $table->string('occupation', 191);
            $table->integer('current_monthly_expenses');
            $table->string('employer_company', 191)->nullable();
            $table->string('employer_name', 191)->nullable();
            $table->string('employer_phone_work', 191)->nullable();
            $table->string('employer_email', 191)->nullable();
            $table->string('employer_salary', 191)->nullable();
            // Step 4
            $table->string('resident_first_name', 191);
            $table->string('resident_last_name', 191);
            $table->integer('resident_sa_id_number', 191);
            $table->integer('resident_passport_number', 191);
            $table->dateTime('resident_dob');
            $table->string('resident_nationality', 191);
            $table->string('resident_phone_mobile', 191);
            $table->string('resident_email', 191);
            $table->string('resident_current_address', 191);
            $table->string('resident_landlord', 191);
            $table->string('resident_landlord_phone_work', 191);
            $table->string('resident_landlord_phone_mobile', 191);
            $table->string('resident_study_location', 191);
            $table->string('resident_study_year', 191);
            $table->enum('resident_gender', ['Male', 'Female', 'Unlisted']);
            // Step 5
            $table->string('unit_location', 191);
            $table->uuid('unit_type');
            $table->enum('unit_lease_length', ['11', '12']);
            $table->boolean('unit_car_parking');
            $table->boolean('unit_bike_parking');
            $table->boolean('unit_tv');
            $table->boolean('unit_storeroom');
            $table->dateTime('unit_occupation_date');
            // Step 6
            $table->boolean('judgements');
            $table->text('judgements_details');
            // Step 7
            $table->uuid('resident_id');
            $table->uuid('resident_study_permit');
            $table->uuid('resident_acceptance');
            $table->uuid('resident_financial_aid');
            $table->uuid('leaseholder_id');
            $table->uuid('leaseholder_address_proof');
            $table->uuid('leaseholder_payslip');
            // Step 8
            $table->text('comments');
            $table->boolean('confirm');
            $table->dateTime('confirm_time');

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
        Schema::dropIfExists('applications');
    }
}
