<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::latest()->get();

        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:leave_types,name',
            'status' => 'required'
        ]);

        LeaveType::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Leave Type Created Successfully'
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $leave = LeaveType::findOrFail($id);
        $request->validate([
            'name' => 'required|max:100|unique:leave_types,name,' . $id,
            'status' => 'required'
        ]);

        $leave->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Leave Type Updated Successfully'
        ]);
    }

    public function delete($id)
    {
        LeaveType::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Leave Type Deleted Successfully'
        ]);
    }
}
