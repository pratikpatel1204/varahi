<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubDepartment;
use App\Models\Department;
use Illuminate\Http\Request;

class SubDepartmentController extends Controller
{

    public function index()
    {
        $departments = Department::where('status', 'Active')->get();
        $subdepartments = SubDepartment::with('department')->get();
        return view('admin.subdepartments.index', compact(
            'subdepartments',
            'departments'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
            'code' => 'required|unique:sub_departments,code',
            'status' => 'required'
        ]);

        SubDepartment::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sub Department added successfully'
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        
        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
            'code' => 'required|unique:sub_departments,code,' . $id,
            'status' => 'required'
        ]);

        $sub = SubDepartment::findOrFail($id);
        $sub->update([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sub Department updated successfully'
        ]);
    }

    public function delete($id)
    {
        $sub = SubDepartment::findOrFail($id);
        $sub->delete();
        return response()->json([
            'status' => true,
            'message' => 'Sub Department deleted successfully'
        ]);
    }
}
