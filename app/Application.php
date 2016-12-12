<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{

    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'step',
        'sa_id_number',
        'passport_number',
        'dob',
        'nationality',
        'phone_mobile',
        'phone_home',
        'phone_work',
        'current_address',
        'marital_status',
        'married_type',
        'current_property_owner',
        'rental_time',
        'rental_amount',
        'rental_name',
        'rental_phone_home',
        'rental_phone_mobile',
        'selfemployed',
        'occupation',
        'current_monthly_expenses',
        'employer_company',
        'employer_name',
        'employer_phone_work',
        'employer_email',
        'employer_salary',
        'resident_first_name',
        'resident_last_name',
        'resident_sa_id_number',
        'resident_passport_number',
        'resident_dob',
        'resident_nationality',
        'resident_phone_mobile',
        'resident_email',
        'resident_current_address',
        'resident_landlord',
        'resident_landlord_phone_work',
        'resident_landlord_phone_mobile',
        'resident_study_location',
        'resident_study_year',
        'resident_gender',
        'unit_location',
        'unit_type',
        'unit_lease_length',
        'unit_car_parking',
        'unit_bike_parking',
        'unit_tv',
        'unit_storeroom',
        'unit_occupation_date',
        'judgements',
        'judgements_details',
        'resident_id',
        'resident_study_permit',
        'resident_acceptance',
        'resident_financial_aid',
        'leaseholder_id',
        'leaseholder_address_proof',
        'leaseholder_payslip',
        'comments',
        'confirm',
        'confirm_time',
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function user()
    {

        return $this->hasOne( 'Portal\User', 'id', 'user_id' );

    }

    public function events()
    {

        return $this->hasMany( 'Portal\ApplicationEvent');

    }

    public function residentId()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'resident_id' );

    }

    public function residentStudyPermit()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'resident_study_permit' );

    }

    public function residentAcceptance()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'resident_acceptance' );

    }

    public function residentFinancialAid()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'resident_financial_aid' );

    }

    public function leaseholderId()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'leaseholder_id' );

    }

    public function leaseholderAddressProof()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'leaseholder_address_proof' );

    }

    public function leaseholderPayslip()
    {

        return $this->hasOne( 'Portal\Document', 'id', 'leaseholder_payslip' );

    }



}
