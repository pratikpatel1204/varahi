<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    public function index()
    {
        $departments = Department::withCount('employees')->latest()->get();

        return view('admin.departments.index', compact('departments'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:150',
            'code'   => 'required|unique:departments,code',
            'status' => 'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $department = Department::create([
            'name'   => $request->name,
            'code'   => $request->code,
            'status' => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Department added successfully',
            'data'    => $department
        ]);
    }


    public function update(Request $request, $id)
    {

        $department = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:150',
            'code'   => 'required|unique:departments,code,' . $id,
            'status' => 'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $department->update([
            'name'   => $request->name,
            'code'   => $request->code,
            'status' => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Department updated successfully'
        ]);
    }

    public function delete($id)
    {

        $department = Department::findOrFail($id);

        $department->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Department deleted successfully'
        ]);
    }
}
