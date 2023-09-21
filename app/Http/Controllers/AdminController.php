<?php

namespace App\Http\Controllers;

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
        return view('admin.userManagement');
    }

    public function userUpdate()
    {
    }

    public function attendanceRecord()
    {
        return view('admin.record');
    }
}
