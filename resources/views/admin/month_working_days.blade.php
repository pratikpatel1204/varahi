@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Monthly Working Days')
@section('content')
    <div class="content">
        <div class="container-fluid mt-4">
            <h4 class="mb-4">Monthly Working Days Entry</h4>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.monthly_working.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($months as $month)
                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-body p-3 text-center">
                                    <h6 class="card-title mb-3">{{ $month->month_name }}</h6>
                                    <input type="number" min="0" max="31"
                                        name="working_days[{{ $month->id }}]" class="form-control text-center"
                                        placeholder="Enter total days" value="{{ $workingDays[$month->id] ?? '' }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
