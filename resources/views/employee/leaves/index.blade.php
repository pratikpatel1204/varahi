@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Leaves')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Leaves List</h3>
                <br>
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="month" class="form-control">
                            <option value="">Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" class="form-control">
                            <option value="">Year</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('employee.my.leaves') }}" class="btn btn-light w-100">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="leavetable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaves as $k => $leave)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                <td>{{ $leave->days }}</td>
                                <td>
                                    @if ($leave->status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($leave->status == 'Rejected')
                                        <span class="badge bg-danger">Rejected</span><br>
                                        <span class="text-dark">{{ $leave->comment ?? '' }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $leave->reason }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No leaves found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
