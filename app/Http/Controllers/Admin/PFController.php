<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PfSetting;
use Illuminate\Http\Request;

class PFController extends Controller
{
    public function index()
    {
        $pf = PfSetting::first();
        return view('admin.pf.index', compact('pf'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pf_registration_number' => 'required|max:100',
            'establishment_name'     => 'required',
            'establishment_address'  => 'required',
            'pf_office_region'       => 'required',
            'employer_contribution'  => 'required|numeric|min:0|max:100',
            'employee_contribution'  => 'required|numeric|min:0|max:100',
            'eps_contribution'       => 'required|numeric|min:0|max:100',
            'epf_contribution'       => 'required|numeric|min:0|max:100',
            'pf_wage_ceiling'        => 'required|integer|min:1000',
            'pf_joining_date'        => 'required|date',
        ]);

        PfSetting::updateOrCreate(
            ['id' => 1], // only one row in table
            [
                'pf_registration_number' => $request->pf_registration_number,
                'establishment_name'     => $request->establishment_name,
                'establishment_address'  => $request->establishment_address,
                'pf_office_region'       => $request->pf_office_region,
                'employer_contribution'  => $request->employer_contribution,
                'employee_contribution'  => $request->employee_contribution,
                'eps_contribution'       => $request->eps_contribution,
                'epf_contribution'       => $request->epf_contribution,
                'pf_wage_ceiling'        => $request->pf_wage_ceiling,
                'pf_joining_date'        => $request->pf_joining_date,
            ]
        );

        return redirect()->back()->with('success', 'PF Details Saved Successfully');
    }
}
