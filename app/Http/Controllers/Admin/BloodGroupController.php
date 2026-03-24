<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use Illuminate\Http\Request;

class BloodGroupController extends Controller
{
    public function index()
    {
        $bloodGroups = BloodGroup::latest()->get();

        return view('admin.blood_groups.index', compact('bloodGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|unique:blood_groups,name',
            'status' => 'required'
        ]);

        $bloodGroup = BloodGroup::create([
            'name'   => $request->name,
            'status' => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Blood Group Added Successfully',
            'data'    => $bloodGroup
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $bloodGroup = BloodGroup::findOrFail($id);

        $request->validate([
            'name'   => 'required|unique:blood_groups,name,' . $id,
            'status' => 'required'
        ]);

        $bloodGroup->update([
            'name'   => $request->name,
            'status' => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Blood Group Updated Successfully',
            'data'    => $bloodGroup
        ]);
    }

    public function delete($id)
    {
        BloodGroup::findOrFail($id)->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Blood Group Deleted Successfully'
        ]);
    }
}
