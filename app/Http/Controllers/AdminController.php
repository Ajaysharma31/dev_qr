<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function getUserDetails()
    {
        $userDatas = User::select('name', 'email', 'role', 'qrcode')->get();
        return view('admin.userManagement', compact('userDatas'));
    }

    public function userUpdate()
    {
    }

    public function convertToReadableDate($intFormatHours)
    {
        $interval = CarbonInterval::hours(floor($intFormatHours))->minutes(round(($intFormatHours - floor($intFormatHours)) * 60));
        return $interval;
    }

    public function attendanceRecord(Request $request)
    {
        // $date = date('Y-m-d');
        // $attendanceRecords = User::find(5)->attendance()->whereDate('in_time', $date)->get();
        // $t_hours = 0;



        // foreach ($attendanceRecords as $attendanceRecord) {
        //     $inTime = Carbon::parse($attendanceRecord->in_time);
        //     $outTime = Carbon::parse($attendanceRecord->out_time);

        //     // calculate the working hours
        //     $diff = $inTime->diff($outTime);
        //     $workingHours = $diff->days * 24 + $diff->h + ($diff->i / 60) + ($diff->s / 3600);

        //     $t_hours += $workingHours;
        // }
        // dd($this->convertToReadableDate($t_hours));
        // dd($attendanceRecords);


        $users = User::select('id', 'name', 'email')->where('role', 'user')->with('attendance')->get();;
        $Atnd_data = [];

        foreach ($users as $user) {
            $t_w_hours = 0;
            foreach ($user->attendance as $attend) {
                $inTime = Carbon::parse($attend->in_time);
                $outTime = Carbon::parse($attend->out_time);
                $diff = $inTime->diff($outTime);
                $workingHours = $diff->days * 24 + $diff->h + ($diff->i / 60) + ($diff->s / 3600);
                $t_w_hours += $workingHours;
            }
            $interval = $this->convertToReadableDate($t_w_hours);
            // dd($interval->forHumans());
            if ($interval->forHumans() != "1 second") {
                $Atnd_data[] = [
                    'user' => $user,
                    'totalWorkingTime' => $interval->forHumans(),
                    'create_at' => $attend->created_at->format('d-m-Y (l)'),
                ];
            }
        }
        return view('admin.record', compact('Atnd_data'));
    }
}
