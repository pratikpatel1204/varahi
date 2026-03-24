<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DesignationWorkingDayController extends Controller
{
    public function index()
    {
        $designations = Role::with('workingDays')->get();
        $days = WorkingDay::all();
        return view('admin.designation_working_days.index', compact('designations', 'days'));
    }

    public function getDays($id)
    {
        $designation = Role::with('workingDays')->findOrFail($id);
        $assigned = $designation->workingDays->pluck('id');
        return response()->json($assigned);
    }

    public function saveFromModal(Request $request)
    {
        $designation = Role::findOrFail($request->designation_id);
        $days = $request->working_days ?? [];
        $designation->workingDays()->sync($days);
        return response()->json(['status' => true, 'msg' => 'Working Days Updated']);
    }

    public function edit($id)
    {
        $designation = Role::findOrFail($id);
        $days = WorkingDay::all();
        $assigned = $designation->workingDays->pluck('id')->toArray();
        return view('admin.designation_working_days.edit',compact('designation', 'days', 'assigned'));
    }

    public function update(Request $request, $id)
    {
        $designation = Role::findOrFail($id);
        $days = $request->working_days ?? [];
        $designation->workingDays()->sync($days);
        return redirect()->route('designation-working-days.index')->with('success', 'Working Days Assigned');
    }
}
