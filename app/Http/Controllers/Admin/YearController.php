<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{

    public function index()
    {
        $years = Year::orderBy('year', 'desc')->get();
        return view('admin.years.index', compact('years'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'year'   => 'required|digits:4|unique:years,year',
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::beginTransaction();

        try {

            if ($request->status == 'Active') {
                Year::where('status', 'Active')->update([
                    'status' => 'Inactive'
                ]);
            }

            Year::create([
                'year'   => $request->year,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Year Added Successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {

        $year = Year::findOrFail($id);

        $request->validate([
            'year'   => 'required|digits:4|unique:years,year,' . $id,
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::beginTransaction();

        try {

            if ($request->status == 'Active') {

                Year::where('id', '!=', $id)
                    ->where('status', 'Active')
                    ->update([
                        'status' => 'Inactive'
                    ]);
            }

            $year->update([
                'year'   => $request->year,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Year Updated Successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }



    public function delete($id)
    {

        try {

            Year::where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Year Deleted Successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Delete failed'
            ], 500);
        }
    }
}
