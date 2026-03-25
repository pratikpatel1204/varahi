<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoanManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanManagementController extends Controller
{
    public function loans_create()
    {
        $user = auth()->user();

        if (in_array($user->role, ['super admin'])) {
            $employees = User::empScope()->with(['department', 'subDepartment', 'designation'])->get();
        } else {
            $employees = User::with(['department', 'subDepartment', 'designation'])->where(function ($q) use ($user) {
                $q->where('id', $user->id)->orWhere('reporting_manager', $user->id);
            })->get();
        }
        return view('admin.loans.create', compact('employees'));
    }
    public function loans_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|exists:users,id',
            'loan_amount'   => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'no_of_emi'     => 'required|integer|min:1',
            'emi_start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $amount   = $request->loan_amount;
        $interest = $request->interest_rate;
        $emiCount = $request->no_of_emi;

        $total = $amount + ($amount * $interest / 100);
        $emi   = $emiCount > 0 ? ($total / $emiCount) : 0;

        $start = new \DateTime($request->emi_start_date);
        $start->modify('+' . ($emiCount - 1) . ' month');
        $emiEndDate = $start->format('Y-m-d');
        $employee = User::find($request->employee_id);
        LoanManagement::create([
            'employee_id'    => $request->employee_id,
            'reporting_manager' => $employee->reporting_manager,
            'loan_amount'    => $amount,
            'interest_rate'  => $interest,
            'no_of_emi'      => $emiCount,
            'emi_start_date' => $request->emi_start_date,
            'emi_end_date'   => $emiEndDate,
            'loan_amount_with_interest'   => $total,
            'emi_amount'     => $emi,
            'created_by'     => auth()->id(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Loan created successfully',
        ]);
    }

    public function loans_request(Request $request)
    {
        $user  = auth()->user();
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $query = LoanManagement::with(['employee', 'reportingManager', 'approvedBy'])
            ->whereMonth('emi_start_date', $month)
            ->whereYear('emi_start_date', $year);

        if (!in_array($user->role, ['admin', 'super admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                    ->orWhere('reporting_manager', $user->id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $allLoans = $query->latest()->get();

        $pendingQuery = LoanManagement::with(['employee', 'reportingManager'])
            ->where('status', 'pending')
            ->whereMonth('emi_start_date', $month)
            ->whereYear('emi_start_date', $year);

        if (!in_array($user->role, ['admin', 'super admin'])) {
            $pendingQuery->where('reporting_manager', $user->id);
        }

        $pendingLoans = $pendingQuery->latest()->get();


        $employees = User::empScope()->get();

        return view('admin.loans.requests', compact(
            'pendingLoans',
            'allLoans',
            'month',
            'year',
            'employees'
        ));
    }

    public function loans_update_status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:loan_managements,id',
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:500'
        ]);

        $loan = LoanManagement::findOrFail($request->id);
        if ($loan->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Loan already processed'
            ]);
        }

        $loan->status = $request->status;
        $loan->remarks = $request->remarks;
        $loan->approved_by = auth()->id(); 
        $loan->save();

        return response()->json([
            'status' => true,
            'message' => 'Loan ' . ucfirst($request->status) . ' successfully'
        ]);
    }
}
