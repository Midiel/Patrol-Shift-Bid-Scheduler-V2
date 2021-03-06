<?php

namespace App\Http\Controllers\User;

use App\Models\Bid;
use App\User;
use App\Spot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function today()
    {
        $weekMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        $daySelected = Carbon::now();

        $bids = Bid::join('spots', 'bids.spot_id', '=', 'spots.id')
            ->join('shifts', 'spots.shift_id', '=', 'shifts.id')
            ->join('schedules', 'shifts.schedule_id', '=', 'schedules.id')
            ->where([['schedules.start_date', '<=', date('Y-m-d')], ['schedules.end_date', '>=', date('Y-m-d')], ['bids.approved', '=', 1], ['spots.'.$weekday.'_s', '<>', null], ['spots.'.$weekday.'_e', '<>', null]])
            ->get();

        $shifts = array();
        foreach ($bids as $bid){
                if(!in_array($bid->spot->shift->name, $shifts)){
                    array_push($shifts, $bid->spot->shift->name);
                }
            }

        $user = Auth::user();
        if($user->hasAnyRoles(['root', 'admin'])){
            return view('user.psheet')->with([
                'editable' => true,
                'spots' => $bids,
                'weekday' => $weekday,
                'shifts' => $shifts,
                'daySelected' => $daySelected
            ]);
        }

        return view('user.psheet')->with([
            'spots' => $bids,
            'weekday' => $weekday,
            'shifts' => $shifts,
            'daySelected' => $daySelected
        ]);
    }

    /**
     * Return the schedule for an specific date coming for the view.
     *
     * @param \http\Env\Request
     * @return view with the list for that specific date
    **/
    public function date(Request $request)
    {
        $dataValidated = $request->validate([
            'calendar_date' => ['required', 'date']
        ]);

        $dayofweek = date('w', strtotime($dataValidated['calendar_date']));

        $weekMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $weekday = $weekMap[$dayofweek];

        $bids = Bid::join('spots', 'bids.spot_id', '=', 'spots.id')
            ->join('shifts', 'spots.shift_id', '=', 'shifts.id')
            ->join('schedules', 'shifts.schedule_id', '=', 'schedules.id')
            ->where([['schedules.start_date', '<=', $dataValidated['calendar_date']], ['schedules.end_date', '>=', $dataValidated['calendar_date']], ['bids.approved', '=', 1], ['spots.'.$weekday.'_s', '<>', null], ['spots.'.$weekday.'_e', '<>', null]])
            ->get();

        //$unitNumber = $spots[3]->shift->specialty->users;

        $shifts = array();
        foreach ($bids as $bid){
            if(!in_array($bid->spot->shift->name, $shifts)){
                array_push($shifts, $bid->spot->shift->name);
            }
        }

        $spotsItemsLenght = sizeof($bids);

        if($spotsItemsLenght == 0){
            $user = Auth::user();
            if($user->hasAnyRoles(['root', 'admin'])){
                return view('user.psheet')->with([
                    'editable' => true,
                    'spots' => $bids,
                    'weekday' => $weekday,
                    'shifts' => $shifts,
                    'daySelected' => $dataValidated['calendar_date'],
                    'noSpots' => 'No schedule available for this date'
                ]);
            }

            return view('user.psheet')->with([
                'spots' => $bids,
                'weekday' => $weekday,
                'shifts' => $shifts,
                'daySelected' => $dataValidated['calendar_date'],
                'noSpots' => 'No schedule available for this date'
            ]);
        }

        $user = Auth::user();
        if($user->hasAnyRoles(['root', 'admin'])){
            return view('user.psheet')->with([
                'editable' => true,
                'spots' => $bids,
                'weekday' => $weekday,
                'shifts' => $shifts,
                'daySelected' => $dataValidated['calendar_date']
            ]);
        }

        return view('user.psheet')->with([
            'spots' => $bids,
            'weekday' => $weekday,
            'shifts' => $shifts,
            'daySelected' => $dataValidated['calendar_date']
        ]);
    }


}
