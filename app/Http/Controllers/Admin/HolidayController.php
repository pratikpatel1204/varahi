<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index()
    {
        $years = Year::where('status', 'Active')->get();
        $holidays = Holiday::with('year')->orderBy('from_date', 'ASC')->get();

        return view('admin.holidays.index', compact('holidays', 'years'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year_id' => 'required',
            'title' => 'required|max:150',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'required|in:Full,Half',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $holiday = Holiday::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Holiday Added Successfully',
            'data' => $holiday
        ]);
    }

    public function update(Request $request)
    {
        $holiday = Holiday::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'year_id' => 'required',
            'title' => 'required|max:150',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'required|in:Full,Half',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $holiday->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Holiday Updated Successfully'
        ]);
    }

    public function delete($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'status' => true,
            'message' => 'Holiday Deleted Successfully'
        ]);
    }
}
