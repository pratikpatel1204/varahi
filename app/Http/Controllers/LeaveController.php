<?php

namespace App\Http\Controllers;

use App\Models\DesignationLeaveType;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function leave_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|numeric|min:1',
            'remaining_days' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $leave = Leave::create([
            'user_id' => $user->id,
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'days' => $request->days,
            'remaining_days' => $request->remaining_days,
            'reason' => $request->reason,
            'reporting_manager_id' => $user->reporting_manager ?? null,
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave applied successfully',
            'data' => $leave
        ]);
    }
    public function get_Leave_Details(Request $request)
    {
        $user = auth()->user();

        $leaveConfig = DesignationLeaveType::where('role_id', $user->designation->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->first();

        $total = $leaveConfig->total_days ?? 0;

        $used = Leave::where('user_id', $user->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('status', 'Approved')
            ->sum('days');

        $remaining = $total - $used;

        return response()->json([
            'total_days' => $total,
            'used_days' => $used,
            'remaining_days' => max(0, $remaining),
        ]);
    }

    public function leave_mark_Read(Request $request)
    {
        Leave::where('id', $request->id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function employee_my_leaves(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $query = Leave::where('user_id', auth()->id())
            ->with('leaveType')
            ->whereMonth('from_date', $month)
            ->whereYear('from_date', $year);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->get();

        return view('employee.leaves.index', compact('leaves', 'month', 'year'));
    }

    public function leave_request(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $pendingLeaves = Leave::managerScope()
            ->where('status', 'Pending')
            ->with(['user', 'leaveType'])
            ->latest()
            ->get();

        $query = Leave::managerScope()->with(['user', 'leaveType', 'approver'])->whereMonth('from_date', $month)->whereYear('from_date', $year); 

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $allLeaves = $query->latest()->get();
        $employees = User::empScope()->get();
        return view('employee.leaves.requests', compact('pendingLeaves', 'allLeaves', 'month', 'year', 'employees'));
    }

    public function leave_request_approve(Request $request)
    {
        Leave::where('id', $request->id)->update([
            'status' => 'Approved',
            'approved_by' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function leave_request_reject(Request $request)
    {
        Leave::where('id', $request->id)->update([
            'status' => 'Rejected',
            'approved_by' => auth()->id(),
            'comment' => $request->comment
        ]);

        return response()->json(['success' => true]);
    }

    public function get_Leave_Types($userId)
    {
        $user = User::with('designation')->findOrFail($userId);

        $user = User::with('designation')->findOrFail($userId);

        $leaveTypes = DesignationLeaveType::with('leaveType')
            ->where('role_id', $user->designation->id)
            ->get();

        $data = [];

        foreach ($leaveTypes as $type) {

            $total = $type->total_days;

            $used = Leave::where('user_id', $userId)
                ->where('leave_type_id', $type->leave_type_id)
                ->where('status', 'Approved')
                ->sum('days');

            $remaining = max(0, $total - $used);

            $data[] = [
                'leave_type_id' => $type->leave_type_id,
                'name' => $type->leaveType->name ?? '',
                'total_days' => $total,
                'used_days' => $used,
                'remaining_days' => $remaining,
            ];
        }

        return response()->json($data);
    }

    public function admin_leave_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|numeric|min:1',
            'remaining_days' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $emp = User::findOrFail($request->user_id);
        $leave = Leave::create([
            'user_id' => $request->user_id,
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'days' => $request->days,
            'remaining_days' => $request->remaining_days,
            'reason' => $request->reason,
            'reporting_manager_id' => $emp->reporting_manager ?? null,
            'status' => 'Approved',
            'approved_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave applied successfully',
            'data' => $leave
        ]);
    }
}
