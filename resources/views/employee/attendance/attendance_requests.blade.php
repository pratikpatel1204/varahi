@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Attendance Requests')
@section('content')
    <div class="content">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Pending Attendance Requests</h5>
                <form method="GET" class="row g-2 mt-2">
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
                        <a href="{{ route('employee.attendance.request') }}" class="btn btn-light w-100">Clear</a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="requestTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Total Hours</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingAttendance as $k => $att)
                            <tr id="row_{{ $att->id }}">
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $att->user->name ?? '-' }}</td>
                                <td>{{ $att->punch_in ?? '-' }}</td>
                                <td>{{ $att->punch_out ?? '-' }}</td>
                                <td>{{ $att->total_hours ?? '00:00:00' }}</td>
                                <td>{{ $att->reason ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($att->status == 'Pending')
                                        <button class="btn btn-success btn-sm approveBtn" data-id="{{ $att->id }}">
                                            Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm rejectBtn" data-id="{{ $att->id }}">
                                            Reject
                                        </button>
                                    @else
                                        <span class="text-muted">No Action</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#requestTable').DataTable();

            $('.approveBtn').click(function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to approve this attendance?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ route('employee.attendance.request.approve') }}", 
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function() {
                                toastr.success('Attendance Approved');
                                $('#row_' + id).remove();
                            }
                        });

                    }
                });
            });

            $('.rejectBtn').click(function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Reject Attendance',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Enter reason...',
                    inputAttributes: {
                        'aria-label': 'Enter reason'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Reject',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Reason is required!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ route('employee.attendance.request.reject') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
                                reject_comment: result.value 
                            },
                            success: function() {
                                toastr.error('Attendance Rejected');
                                $('#row_' + id).remove();
                            }
                        });

                    }
                });
            });

        });
    </script>
@endsection
