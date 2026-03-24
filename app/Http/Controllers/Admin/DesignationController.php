<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Role::where('name', '!=', 'super admin')->latest()->get();
        return view('admin.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create([
            'name'   => $request->name,
            'guard_name' => 'admin'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Designation Added Successfully'
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $designation = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id
        ]);

        $designation->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Designation Updated Successfully'
        ]);
    }
    public function delete($id)
    {
        Role::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Designation Deleted Successfully'
        ]);
    }
}
