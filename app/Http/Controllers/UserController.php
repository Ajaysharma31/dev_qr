<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function convertToReadableDate($intFormatHours)
    {
        $interval = CarbonInterval::hours(floor($intFormatHours))->minutes(round(($intFormatHours - floor($intFormatHours)) * 60));
        return $interval;
    }

    public function index()
    {
        $userID = Auth::id();
        $date = date('Y-m-d');
        $attendanceRecords = User::find($userID)->attendance()->whereDate('created_at', $date)->get();
        $t_hours = 0;
        $Atnd_data = [];

        foreach ($attendanceRecords as $attendanceRecord) {
            $inTime = Carbon::parse($attendanceRecord->in_time);
            $outTime = Carbon::parse($attendanceRecord->out_time);

            // calculate the working hours
            $diff = $inTime->diff($outTime);
            $workingHours = $diff->days * 24 + $diff->h + ($diff->i / 60) + ($diff->s / 3600);

            $t_hours += $workingHours;
        }
        $interval = $this->convertToReadableDate($t_hours);
        if ($interval->forHumans() != "1 second") {
            $Atnd_data[] = [
                'name' => Auth::user()->name,
                'attendance_ID' => Auth::user()->qrcode,
                'totalWorkingTime' => $interval->forHumans(),
                'create_at' => $attendanceRecord->created_at->format('d-m-Y (l)'),
            ];
        }

        return view('user.dashboard', compact('Atnd_data'));
    }

    public function markAttendance(Request $request)
    {
        $userID = Auth::id();
        // $rules = [
        //     'qrCodeNumber' => 'required | valid_qrcode',
        // ];

        // // custom error message
        // $message = [
        //     'valid_qrcode' => 'The QR code is invalid or does not match your account',
        // ];
        // // validate

        // $request['userID'] = $userID;
        // $validator = Validator::make($request->all(), $rules, $message);

        // if ($validator->fails()) {
        //     throw new ValidationException($validator);
        // }
        // return response()->json([
        //     'message' => 'validation passed',
        // ]);

        // die("success");


        $checkQRcode = User::where('id', $userID)->where('qrcode', $request->qrCodeNumber)->first();
        // if (count($checkQRcode) = 1) {
        //     dd($checkQRcode);
        // }
        if (!$checkQRcode) {
            $response = ([
                'head' => 'Not Allowed',
                'message' => 'The QR code is invalid or does not match your account',
                'status' => 201,

            ]);
            return $response;
        }
        $currentDateTime = Carbon::now();
        $timeTwoMinutesAgo = Carbon::now()->subMinute();

        $existingAttendace = Attendance::where('user_id', $userID)->whereBetween('in_time', [$timeTwoMinutesAgo, $currentDateTime])->first();

        if ($existingAttendace) {

            $existingAttendace->update([
                'in_time' => $currentDateTime,
            ]);
            $response = ([
                'head' => 'Updated',
                'message' => 'In-Time Updated ',
                'status' => 200,

            ]);
        } else {
            $existingAttendaceToday = Attendance::where('user_id', $userID)->whereDate('created_at', Carbon::today())->first();

            if ($existingAttendaceToday && $existingAttendaceToday->out_time === null) {
                $existingAttendaceToday->update([
                    'out_time' => $currentDateTime,
                ]);
                $response = ([
                    'head' => 'Updated',
                    'message' => 'Out-Time Updated ',
                    'status' => 200,

                ]);
            } else {

                Attendance::create([
                    'user_id' => $userID,
                    'in_time' => $currentDateTime,
                ]);
                $response = ([
                    'head' => 'Updated',
                    'message' => 'In-Time Updated ',
                    'status' => 200,

                ]);
            }
        }
        return $response;
    }
}
