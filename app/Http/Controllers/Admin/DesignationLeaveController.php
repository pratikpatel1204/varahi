<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\RoleLeaveType;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DesignationLeaveController  extends Controller
{
    public function index()
    {
        $designations = Role::with('leaveTypes')->get();
        $leaveTypes = LeaveType::where('status', 1)->get();
        return view('admin.designation_leaves.index', compact('designations', 'leaveTypes'));
    }

    public function getData($id)
    {
        $role = RoleLeaveType::where('role_id', $id)->get();
        return response()->json($role);
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required',
            'leave_type_id' => 'required|array',
            'total_days' => 'required|array'
        ]);

        $designationId = $request->designation_id;
        $role = RoleLeaveType::where('role_id', $designationId)->delete();

        foreach ($request->leave_type_id as $key => $leaveId) {

            $days = $request->total_days[$key] ?? 0;

            if ($days > 0) {

                RoleLeaveType::insert([
                    'role_id' => $designationId,
                    'leave_type_id' => $leaveId,
                    'total_days' => $days,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Leave quota saved successfully'
        ]);
    }
}
