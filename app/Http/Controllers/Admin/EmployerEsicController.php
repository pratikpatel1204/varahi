<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployerEsic;

class EmployerEsicController extends Controller
{
    public function index()
    {
        $esic = EmployerEsic::first();
        return view('admin.esic.index', compact('esic'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'esic_registration_number' => 'required|string|max:50',
            'establishment_name'       => 'required|string|max:255',
            'establishment_address'    => 'required|string',
            'esic_branch_office'       => 'required|string|max:255',
            'employer_contribution'    => 'required|numeric|min:0|max:100',
            'employee_contribution'    => 'required|numeric|min:0|max:100',
            'esic_wage_limit'          => 'required|integer|min:1000',
            'esic_start_date'          => 'required|date',
        ]);

        $esic = EmployerEsic::first();

        if ($esic) {
            $esic->update($validated);
            $msg = 'ESIC settings updated successfully!';
        } else {
            EmployerEsic::create($validated);
            $msg = 'ESIC settings saved successfully!';
        }

        return redirect()->route('admin.esic.index')->with('success', $msg);
    }
}
