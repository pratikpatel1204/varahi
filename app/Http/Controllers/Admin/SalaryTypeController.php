<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalaryType;

class SalaryTypeController extends Controller
{

    // Show all salary types
    public function index()
    {
        $salaryTypes = SalaryType::orderBy('type', 'asc')->get();
        return view('admin.salary_types.index', compact('salaryTypes'));
    }

    // Store Salary Type
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:Earning,Deduction',
            'value_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric'
        ]);

        $salaryType = SalaryType::create([
            'name' => $request->name,
            'type' => $request->type,
            'value_type' => $request->value_type,
            'value' => $request->value
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Salary Type added successfully',
            'data' => $salaryType
        ]);
    }

    // Update Salary Type
    public function update(Request $request, $id)
    {
        $salaryType = SalaryType::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:Earning,Deduction',
            'value_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric'
        ]);

        $salaryType->update([
            'name' => $request->name,
            'type' => $request->type,
            'value_type' => $request->value_type,
            'value' => $request->value
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Salary Type updated successfully'
        ]);
    }

    // Delete Salary Type
    public function destroy($id)
    {
        $salaryType = SalaryType::findOrFail($id);
        $salaryType->delete();
        return response()->json([
            'status' => true,
            'message' => 'Salary Type deleted successfully'
        ]);
    }
}
