<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('id', 'desc')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required'
        ]);
        Course::create([
            'course_name' => $request->course_name,
            'status'      => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Course Added Successfully'
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'course_name' => 'required'
        ]);

        Course::where('id', $id)->update([
            'course_name' => $request->course_name,
            'status'      => $request->status
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Course Updated Successfully'
        ]);
    }

    public function delete($id)
    {
        Course::where('id', $id)->delete();
        
        return response()->json([
            'status'  => true,
            'message' => 'Course Deleted Successfully'
        ]);
    }
}
