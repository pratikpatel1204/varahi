@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || PF Settings')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary ">
                    <h5 class="mb-0 text-white">Employer PF Settings</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.pf.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>PF Registration Number</label>
                                <input type="text" name="pf_registration_number" class="form-control"
                                    placeholder="Employer PF Code (Ex: MH/BAN/12345/000)"
                                    value="{{ $pf->pf_registration_number ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Establishment Name</label>
                                <input type="text" name="establishment_name" class="form-control"
                                    placeholder="Company Name as per PF" value="{{ $pf->establishment_name ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Establishment Address</label>
                                <textarea name="establishment_address" placeholder="Registered address" class="form-control" rows="2" required>{{ $pf->establishment_address ?? '' }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>PF Office Region</label>
                                <input type="text" name="pf_office_region" class="form-control"
                                    placeholder="PF regional office" value="{{ $pf->pf_office_region ?? '' }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>PF Wage Ceiling</label>
                                <input type="number" name="pf_wage_ceiling" class="form-control"
                                    value="{{ $pf->pf_wage_ceiling ?? 15000 }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>PF Joining Date</label>
                                <input type="date" name="pf_joining_date" class="form-control"
                                    value="{{ $pf->pf_joining_date?->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Employer %</label>
                                <input type="number" step="0.01" name="employer_contribution" class="form-control"
                                    value="{{ $pf->employer_contribution ?? 12 }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Employee %</label>
                                <input type="number" step="0.01" name="employee_contribution" class="form-control"
                                    value="{{ $pf->employee_contribution ?? 12 }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>EPS %</label>
                                <input type="number" step="0.01" name="eps_contribution" class="form-control"
                                    value="{{ $pf->eps_contribution ?? 8.33 }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>EPF %</label>
                                <input type="number" step="0.01" name="epf_contribution" class="form-control"
                                    value="{{ $pf->epf_contribution ?? '' }}">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">
                                @if ($pf)
                                    Update Settings
                                @else
                                    Save Settings
                                @endif

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
