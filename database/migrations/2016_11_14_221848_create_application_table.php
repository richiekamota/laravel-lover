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
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->integer('sa_id_number')->nullable();
            $table->string('passport_number', 191)->nullable();
            $table->dateTime('dob')->nullable();
            $table->string('nationality', 191)->nullable();
            $table->string('phone_mobile', 191)->nullable();
            $table->string('phone_home', 191)->nullable();
            $table->string('phone_work', 191)->nullable();
            $table->text('current_address')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->enum('married_type', ['In community of property', 'ANC', 'Accural System'])->nullable();
            // Step 2
            $table->boolean('current_property_owner')->nullable();
            $table->enum('rental_time', ['12', '24', '36', '48'])->nullable();
            $table->integer('rental_amount')->nullable();
            $table->string('rental_name', 191)->nullable();
            $table->string('rental_phone_home', 191)->nullable();
            $table->string('rental_phone_mobile', 191)->nullable();
            // Step 3
            $table->boolean('selfemployed')->nullable();
            $table->string('occupation', 191)->nullable();
            $table->integer('current_monthly_expenses')->nullable();
            $table->string('employer_company', 191)->nullable();
            $table->string('employer_name', 191)->nullable();
            $table->string('employer_phone_work', 191)->nullable();
            $table->string('employer_email', 191)->nullable();
            $table->string('employer_salary', 191)->nullable();
            // Step 4
            $table->string('resident_first_name', 191)->nullable();
            $table->string('resident_last_name', 191)->nullable();
            $table->integer('resident_sa_id_number')->nullable();
            $table->string('resident_passport_number', 191)->nullable();
            $table->dateTime('resident_dob')->nullable();
            $table->string('resident_nationality', 191)->nullable();
            $table->string('resident_phone_mobile', 191)->nullable();
            $table->string('resident_email', 191)->nullable();
            $table->string('resident_current_address', 191)->nullable();
            $table->string('resident_landlord', 191)->nullable();
            $table->string('resident_landlord_phone_work', 191)->nullable();
            $table->string('resident_landlord_phone_mobile', 191)->nullable();
            $table->string('resident_study_location', 191)->nullable();
            $table->string('resident_study_year', 191)->nullable();
            $table->enum('resident_gender', ['Male', 'Female', 'Unlisted'])->nullable();
            // Step 5
            $table->string('unit_location', 191)->nullable();
            $table->uuid('unit_type')->nullable();
            $table->enum('unit_lease_length', ['11', '12'])->nullable();
            $table->boolean('unit_car_parking')->nullable();
            $table->boolean('unit_bike_parking')->nullable();
            $table->boolean('unit_tv')->nullable();
            $table->boolean('unit_storeroom')->nullable();
            $table->dateTime('unit_occupation_date')->nullable();
            // Step 6
            $table->boolean('judgements')->nullable();
            $table->text('judgements_details')->nullable();
            // Step 7
            $table->uuid('resident_id')->nullable();
            $table->uuid('resident_study_permit')->nullable();
            $table->uuid('resident_acceptance')->nullable();
            $table->uuid('resident_financial_aid')->nullable();
            $table->uuid('leaseholder_id')->nullable();
            $table->uuid('leaseholder_address_proof')->nullable();
            $table->uuid('leaseholder_payslip')->nullable();
            // Step 8
            $table->text('comments')->nullable();
            $table->boolean('confirm')->nullable();
            $table->dateTime('confirm_time')->nullable();

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
        Schema::dropIfExists('applications');
    }
}
