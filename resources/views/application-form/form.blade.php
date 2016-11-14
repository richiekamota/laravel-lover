@extends('layouts.app')

@section('content')

    <h1>Appliction form in here</h1>
    <!--
    Step 1
    first_name* - text
    last_name* - text
    email* - email
    password* - password
    sa_id_number - text - id OR passport
    passport_number - text - id OR passport
    dob* - date time - over 18?
    nationality* - text
    phone_mobile* - number
    phone_home - number
    phone_work - number
    current_address - long text
    marital_status - enum
    married_type - enum
    Step 2
    current_property_owner - boolean
    rental_time - enum - if current_property_owner is false
    rental_amount - number - if current_property_owner is false
    rental_name - text - if current_property_owner is false
    rental_phone_home - number - if current_property_owner is false
    rental_phone_mobile - number
    Step 3
    selfemployed - boolean
    occupation - text
    current_monthly_expenses - number - if not selfemployed
    employer_company - text - if not selfemployed
    employer_name - text - if not selfemployed
    employer_phone_work - number - if not selfemployed
    employer_email - email - if not selfemployed
    employer_salary - number - if not selfemployed
    Step 4
    resident_first_name
    resident_last_name
    resident_sa_id_number
    resident_passport_number
    resident_dob - date time
    resident_nationality - text
    resident_phone_mobile - number
    resident_email - email
    resident_current_address - text
    resident_landlord - text
    resident_landlord_phone_work - number
    resident_landlord_phone_mobile - number
    resident_study_location - text
    resident_study_year - number
    resident_gender - enum
    Step 5
    unit_location - uuid link
    unit_type - enum
    unit_lease_length - number
    unit_car_parking - boolean
    unit_bike_parking - boolean
    unit_tv - boolean
    unit_storeroom - boolean
    unit_occupation_date - date time
    Step 6
    judgements - boolean
    judgements_details - long text
    Step 7
    resident_id* - uuid link
    resident_study_permit* - uuid link
    resident_acceptance* - uuid link
    resident_financial_aid* - uuid link
    leaseholder_id* - uuid link
    leaseholder_address_proof* - uuid link
    leaseholder_payslip* - uuid link
    Step 8
    comments - large text
    confirm - boolean
    confirm_time - date time
   -->

@endsection
