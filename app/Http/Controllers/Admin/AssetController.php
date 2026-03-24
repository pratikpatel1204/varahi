<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;
use App\Models\EmployeeAsset;
use App\Models\Employee;
use App\Models\User;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::latest()->get();
        return view('admin.assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:assets,code',
            'type' => 'nullable|string|max:100'
        ]);

        Asset::create([
            'name'   => $request->name,
            'code'   => $request->code,
            'type'   => $request->type
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Asset Added Successfully',
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:assets,code,' . $id,
            'type' => 'nullable|string|max:100'
        ]);

        $asset = Asset::findOrFail($id);

        $asset->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Asset Updated Successfully'
        ]);
    }

    public function delete($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Asset Deleted Successfully'
        ]);
    }

    public function assign_assets_index()
    {
        $employees = User::where('status', 1)->get();
        $assets = Asset::where('status', 'Available')->get();
        return view('admin.assets.assign', compact('employees', 'assets'));
    }

    public function get_assigned_assets($id)
    {
        $assignedAssetIds = EmployeeAsset::where('employee_id', $id)
            ->where('status', 'Assigned')
            ->pluck('asset_id')
            ->toArray();
        return response()->json($assignedAssetIds);
    }

    public function assign_assets_store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'asset_ids'   => 'nullable|array',
            'asset_ids.*' => 'exists:assets,id',
        ]);

        $employeeId = $request->employee_id;
        $assetIds   = $request->asset_ids ?? [];

        $assignedBy = auth()->user()->name ?? null;

        $existingAssets = EmployeeAsset::where('employee_id', $employeeId)
            ->where('status', 'Assigned')
            ->pluck('asset_id')
            ->toArray();

        $toAssign   = array_diff($assetIds, $existingAssets);
        $toUnassign = array_diff($existingAssets, $assetIds);

        foreach ($toAssign as $assetId) {
            EmployeeAsset::create([
                'employee_id' => $employeeId,
                'asset_id'    => $assetId,
                'assigned_by' => $assignedBy,
                'status'      => 'Assigned',
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Assets updated successfully'
        ]);
    }

    public function assets_history(Request $request)
    {
        $query = EmployeeAsset::with(['employee', 'asset']);
        if ($request->asset_id) {
            $query->where('asset_id', $request->asset_id);
        }
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }
        $histories = $query->latest()->get();
        $assets = Asset::all();
        return view('admin.assets.history', compact('histories', 'assets'));
    }

    public function assets_history_ajax(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'employee_id',
            2 => 'asset_id',
            3 => 'assigned_by',
            4 => 'created_at',
            5 => 'status',
        ];

        $totalData = EmployeeAsset::count();
        $totalFiltered = $totalData;

        $limit = $request->length;
        $start = $request->start;
        $order = $columns[$request->order[0]['column']];
        $dir   = $request->order[0]['dir'];

        $query = EmployeeAsset::with(['employee', 'asset']);

        if ($request->asset_id) {
            $query->where('asset_id', $request->asset_id);
        }
        if ($request->assign_from && $request->assign_to) {
            $query->whereBetween('created_at', [
                $request->assign_from . ' 00:00:00',
                $request->assign_to . ' 23:59:59'
            ]);
        }
        if ($request->return_from && $request->return_to) {
            $query->whereBetween('return_date', [
                $request->return_from,
                $request->return_to
            ]);
        }
        if (!empty($request->search['value'])) {

            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {

                $q->whereHas('employee', function ($qq) use ($search) {
                    $qq->where('name', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('asset', function ($qq) use ($search) {
                        $qq->where('name', 'LIKE', "%{$search}%");
                    })

                    ->orWhere('assigned_by', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $query->count();
        }

        $data = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        $jsonData = [];
        $i = $start + 1;

        foreach ($data as $row) {
            $btn = '';
            if ($row->status == 'Assigned') {
                $btn = '<button class="btn btn-sm btn-danger return-btn" data-url="' . route('admin.assets.return', $row->id) . '">Return
                        </button>';
                }
            $jsonData[] = [
                'DT_RowIndex' => $i++,
                'employee' => $row->employee->name ?? '-',
                'asset' => $row->asset->name ?? '-',
                'assigned_by' => $row->assigned_by ?? '-',
                'date' => $row->created_at->format('d-m-Y'),
                'status' => $row->status == 'Assigned'
                    ? '<span class="badge bg-success">Assigned</span>'
                    : '<span class="badge bg-secondary">Returned</span>',
                'action' => $btn,
            ];
        }
        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $jsonData,
        ]);
    }

    public function assets_return($id)
    {
        $asset = EmployeeAsset::findOrFail($id);

        $asset->update([
            'status' => 'Returned',
            'return_date' => date('Y-m-d')

        ]);

        return back()->with('success', 'Asset Returned Successfully');
    }
}
