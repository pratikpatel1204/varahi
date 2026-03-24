<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QualificationArea;

class QualificationAreaController extends Controller
{
    public function index()
    {
        $areas = QualificationArea::orderBy('id','desc')->get();
        return view('admin.qualification_areas.index', compact('areas'));
    }


    // Store
    public function store(Request $request)
    {
        $request->validate([
            'area_name' => 'required'
        ]);

        QualificationArea::create([
            'area_name' => $request->area_name,
            'status'    => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Area Added Successfully'
        ]);
    }


    // Update
    public function update(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'area_name' => 'required'
        ]);

        QualificationArea::where('id',$id)->update([
            'area_name' => $request->area_name,
            'status'    => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Area Updated Successfully'
        ]);
    }


    // Delete
    public function delete($id)
    {
        QualificationArea::where('id',$id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Area Deleted Successfully'
        ]);
    }
}
