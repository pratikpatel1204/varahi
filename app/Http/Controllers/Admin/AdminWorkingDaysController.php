<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthlyWorkingDay;
use App\Models\Month;
use App\Models\Year;

class AdminWorkingDaysController extends Controller
{
    public function create()
    {
        $months = Month::orderBy('id')->get();
        $activeYear = Year::where('status', 'Active')->first();
        if (!$activeYear) {
            return back()->with('error', 'No active year found!');
        }
        $yearId = $activeYear->id;
        $workingDays = MonthlyWorkingDay::where('year_id', $yearId)->pluck('total_days', 'month_id')->toArray();
        return view('admin.month_working_days', compact('months', 'workingDays', 'yearId'));
    }


    public function store(Request $request)
    {
        $data = $request->input('working_days');

        $activeYear =  Year::where('status', 'Active')->first();

        if (!$activeYear) {
            return back()->with('error', 'No active year found!');
        }

        $yearId = $activeYear->id;

        foreach ($data as $monthId => $days) {

            if (!is_null($days) && $days !== '') {
                MonthlyWorkingDay::updateOrCreate(
                    [
                        'month_id' => $monthId,
                        'year_id'  => $yearId,
                    ],
                    [
                        'total_days' => $days
                    ]
                );
            }
        }

        return back()->with('success', 'Monthly working days saved successfully.');
    }
}
