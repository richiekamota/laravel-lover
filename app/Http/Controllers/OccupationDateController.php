<?php

namespace Portal\Http\Controllers;

use Gate;
use Carbon\Carbon;
use DB;
use Portal\OccupationDate;
use Illuminate\Http\Request;
use Portal\Http\Requests\OccupationFilterRequest;
use Portal\Location;
use Portal\Unit;
use Portal\UnitType;
use Excel;

use Response;

class OccupationDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_unless(Gate::allows('is-admin'), 401);

        $unitsArr = DB::table('units')->get();
        $units = array();
        foreach ($unitsArr as $u) {
            $occupationDates = OccupationDate::where("unit_id", "=", $u->id)->where("status", "<>", "cancelled")->get();
            $u->occupation_dates = $occupationDates->toArray();
            $units[] = $u;
        }
        $units = json_encode($units);
        $locations = Location::all();

        // print_r($units);

        return view('occupation-dates.index', compact('units', 'locations'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportToCSV(OccupationFilterRequest $request)
    {
        abort_unless(Gate::allows('is-admin'), 401);

        $unitIDs = $request['unit'];
        $startDate = $request['start_date'];
        $endDate = $request['end_date'];
        $location = $request['location'];
        $occupied = $request['occupation'];
        $export_ids = explode("," , $request['export_ids']);

        if($location != '') {
            $unitsArr = DB::table('units')->where("location_id","=",$location)->whereIn('id',$export_ids)->get();
        }else {
            $unitsArr = DB::table('units')->whereIn('id',$export_ids)->get();
        }
        // Headings and rows
        $csv_array = [['UNIT CODE', 'OCCUPIED', 'OCCUPATION START', 'OCCUPATION END', 'CONTRACT ID', 'UNIT ID', 'APPLICATION_ID']];

        foreach ($unitsArr as $u) {
            $isValid = FALSE;
            $occupations = DB::table('occupation_dates')->where('unit_id', '=', $u->id)->get();

            if (!empty($occupations->toArray()) && $occupied == 1) {
                foreach ($occupations->toArray() as $o) {

                    $unitStartDate = $o->start_date;
                    $unitEndDate = $o->end_date;

                    if ($startDate != '' && $endDate != '') {

                        if (($unitStartDate >= $startDate && $unitStartDate <= $endDate) || ($unitEndDate <= $endDate && $unitEndDate >= $startDate)) {

                                $isValid = TRUE;

                        }
                    } else if ($startDate != '' && $endDate == '') {

                        if (($unitStartDate >= $startDate && $unitEndDate <= $startDate)) {

                                $isValid = TRUE;

                        }
                    } else if ($startDate == '' && $endDate != '') {

                        if (($unitEndDate >= $endDate && $unitStartDate <= $endDate)) {

                                $isValid = TRUE;

                        }
                    }

                    if ($isValid == TRUE) $csv_array[] =[$u->code, 'Y', $unitStartDate, $unitEndDate, $o->contract_id, $u->id, $o->application_id];
                }
            } else {
                 if (empty($occupations->toArray()) && $occupied == 0){ $csv_array[] = [$u->code, 'N', '', '', '', $u->id, ''];}
            }


        }

        $inputArr = json_decode(json_encode($csv_array,true));
        Excel::create('occupations', function($excel) use($inputArr) {

            $excel->sheet('Sheet1', function($sheet) use($inputArr) {

                $sheet->fromArray($inputArr, null, 'A1', false, false);

            });

        })->export('csv');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request|UnitCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(OccupationDateRequest $request)
    {

        // abort unless Auth > tenant
        $this->authorize('create', OccupationDate::class);

        DB::beginTransaction();

        try {

            // Store the location in the DB
            $occupationDate = OccupationDate::create($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.occupation_date_store_complete'),
                'data'    => $occupationDate->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'occupation_date_store_error',
                'message' => trans('portal.occupation_date_error'),
            ], 422);

        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    //Updating lease end date changes in the database
    public function updateEndDate(Request $request, $id)
    {          
        abort_unless(Gate::allows('is-admin'), 401);

        $updateEndDate = $request['date'];
        
        DB::beginTransaction();
        try {

            OccupationDate::where('contract_id',$id)->update(['end_date' => $updateEndDate]);

            DB::commit();

            return Response::json([
                'message' => trans('End date succesfully updated!'),               
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'occcupationdate_edit_error',
                'message' => trans('End date could not be updated!'),
            ], 422);

        }
    }
}
