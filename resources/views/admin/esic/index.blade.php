@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || ESIC Settings')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Employer ESIC Settings</h5>
                </div>
                <div class="card-body">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.esic.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>ESIC Registration Number</label>
                                <input type="text" name="esic_registration_number" class="form-control"
                                    placeholder="17 digit employer code" value="{{ $esic->esic_registration_number ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Establishment Name</label>
                                <input type="text" name="establishment_name" class="form-control"
                                    placeholder="Company Name as per ESIC" value="{{ $esic->establishment_name ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Establishment Address</label>
                                <textarea name="establishment_address" class="form-control" rows="2" placeholder="Registered address" required>{{ $esic->establishment_address ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>ESIC Branch Office</label>
                                <input type="text" name="esic_branch_office" class="form-control"
                                    placeholder="Branch office name" value="{{ $esic->esic_branch_office ?? '' }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Employer Contribution %</label>
                                <input type="number" step="0.01" name="employer_contribution" class="form-control"
                                    value="{{ $esic->employer_contribution ?? 3.25 }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Employee Contribution %</label>
                                <input type="number" step="0.01" name="employee_contribution" class="form-control"
                                    value="{{ $esic->employee_contribution ?? 0.75 }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>ESIC Wage Limit</label>
                                <input type="number" name="esic_wage_limit" class="form-control"
                                    value="{{ $esic->esic_wage_limit ?? 21000 }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>ESIC Start Date</label>
                                <input type="date" name="esic_start_date" class="form-control"
                                    value="{{ $esic->esic_start_date ?? '' }}" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">
                                @if ($esic)
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
