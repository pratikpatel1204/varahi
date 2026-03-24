<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingDay;
use Illuminate\Http\Request;

class WorkingDayController extends Controller
{
    // List
    public function index()
    {
        $days = WorkingDay::orderBy('id')->get();
        return view('admin.working_days.index', compact('days'));
    }

    // Add Form
    public function create()
    {
        return view('admin.working_days.create');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'day_name'   => 'required|unique:working_days',
            'is_working' => 'required'
        ]);

        WorkingDay::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Day Added Successfully',
        ]);
    }

    // Edit Form
    public function edit($id)
    {
        $day = WorkingDay::findOrFail($id);
        return view('admin.working_days.edit', compact('day'));
    }

    // Update
    public function update(Request $request)
    {
        $id = $request->id;
        $day = WorkingDay::findOrFail($id);

        $request->validate([
            'day_name'   => 'required|unique:working_days,day_name,' . $id,
            'is_working' => 'required'
        ]);

        $day->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Day Updated Successfully',
        ]);
    }

    // Delete
    public function delete($id)
    {
        WorkingDay::findOrFail($id)->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Day Deleted Successfully'
        ]);
    }
}
