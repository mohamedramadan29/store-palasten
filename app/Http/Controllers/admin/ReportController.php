<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\PublicSetting;
use App\Models\front\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $lastorders = Order::orderby('id','desc')->limit(5)->get();
        $publicsetting = PublicSetting::first();
        return view('admin.Reports.index',compact('lastorders','publicsetting'));
    }
}
