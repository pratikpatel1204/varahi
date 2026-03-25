<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseReimbursement;
use App\Models\Month;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseReimbursementController extends Controller
{
    public function expense_reimbursement_list(Request $request)
    {
        $user  = auth()->user();
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $allQuery = ExpenseReimbursement::with(['employee', 'reportingManager', 'approvedBy'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        if (!in_array($user->role, ['admin', 'super admin'])) {
            $allQuery->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                    ->orWhere('reporting_manager', $user->id);
            });
        }

        if ($request->status) {
            $allQuery->where('status', $request->status);
        }

        $expenses = $allQuery->latest()->get();

        $pendingQuery = ExpenseReimbursement::with(['employee', 'reportingManager'])
            ->where('status', 'pending')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $pendingQuery->where('reporting_manager', $user->id);

        $pendingExpenses = $pendingQuery->latest()->get();


        return view('admin.expense.requests', compact(
            'expenses',
            'pendingExpenses',
            'month',
            'year'
        ));
    }

    public function expense_reimbursement_create()
    {
        $user = auth()->user();

        if (in_array($user->role, ['super admin'])) {
            $employees = User::empScope()->with(['department', 'subDepartment', 'designation'])->get();
        } else {
            $employees = User::with(['department', 'subDepartment', 'designation'])->where(function ($q) use ($user) {
                $q->where('id', $user->id)->orWhere('reporting_manager', $user->id);
            })->get();
        }
        $years = Year::all();
        $months = Month::all();

        return view('admin.expense.create', compact('employees', 'years', 'months'));
    }

    public function expense_reimbursement_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:users,id',
            'entry_type'  => 'required|in:advance,expense,deduction',
            'year_id'     => 'required',
            'entry_month' => 'required',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
            'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $files = [];

            // ✅ Multiple File Upload
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/expenses'), $name);
                    $files[] = $name;
                }
            }

            // ✅ Get Reporting Manager
            $employee = \App\Models\User::find($request->employee_id);

            $expense = \App\Models\ExpenseReimbursement::create([
                'employee_id'       => $request->employee_id,
                'reporting_manager' => $employee->reporting_manager ?? null,
                'entry_type'        => $request->entry_type,
                'year_id'           => $request->year_id,
                'entry_month'       => $request->entry_month,
                'amount'            => $request->amount,
                'description'       => $request->description,
                'bill'              => !empty($files) ? json_encode($files) : null,
                'status'            => 'pending',
                'approved_by'       => null,
                'remarks'           => null,
                'created_by'        => auth()->id(),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Expense created successfully',
                'data'    => $expense
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function expense_reimbursement_update_status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expense_reimbursements,id',
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:500'
        ]);

        $expense = ExpenseReimbursement::findOrFail($request->id);

        $expense->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
            'approved_by' => auth()->id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Expense status updated successfully'
        ]);
    }
}
