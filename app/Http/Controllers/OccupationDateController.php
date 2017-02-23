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

        $startDate = $request['start_date'];
        $endDate = $request['end_date'];
        $location = $request['location'];
        $occupied = $request['occupation'];

        if($location != '') {
            $unitsArr = DB::table('units')->where("location_id","=",$location)->get();
        }else {
            $unitsArr = DB::table('units')->get();
        }
        // Headings and rows
        $headings = array('UNIT CODE', 'OCCUPIED', 'OCCUPATION START', 'OCCUPATION END', 'CONTRACT ID', 'UNIT ID', 'APPLICATION_ID');
        $array = array();

        foreach ($unitsArr as $u) {
            $isValid = FALSE;

            if ($occupied == 0) {
                $isValid = TRUE;
            }

            $occupations = DB::table('occupation_dates')->where('unit_id', '=', $u->id)->get();

            if (!empty($occupations->toArray())) {
                foreach ($occupations->toArray() as $o) {

                    $unitStartDate = $o->start_date;
                    $unitEndDate = $o->end_date;

                    if ($start_date != '' && $end_date != '') {

                        if (($unitStartDate >= $startDate && $unitStartDate <= $endDate) || ($unitEndDate <= $endDate && $unitEndDate >= $startDate)) {
                            if ($occupied == 1) {
                                $isValid = TRUE;
                            }
                        }
                    } else if (this . filterStartDate != '') {

                        if (($unitStartDate >= $startDate && $unitEndDate <= $startDate)) {
                            if ($occupied == 1) {
                                $isValid = TRUE;
                            }
                        }
                    } else if (this . filterEndDate != '') {

                        if (($unitEndDate >= $endDate && $unitStartDate <= $endDate)) {
                            if ($occupied == 1) {
                                $isValid = TRUE;
                            }
                        }
                    }

                    if ($isValid == TRUE) $array[] = array($u->code, 'Y', $unitStartDate, $unitEndDate, $o['contract_id'], $u->id, $o['application_id']);
                }
            } else {
                 if ($isValid == TRUE){ $array[] = array($u->code, 'N', '', '', '', $u->id, '');}
            }


        }

        $fh = fopen('php://output', 'w');

        ob_start();
        fputcsv($fh, $headings);

        if (!empty($array)) {
            foreach ($array as $item) {
                fputcsv($fh, $item);
            }
        }
        $string = ob_get_clean();

        $filename = 'csv_' . date('Ymd') . '_' . date('His');

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", FALSE);
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment filename=\"$filename.csv\";");
        header("Content-Transfer-Encoding: binary");
        exit($string);
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
}
