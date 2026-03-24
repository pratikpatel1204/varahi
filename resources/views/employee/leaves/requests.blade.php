@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Leave Requests')
@section('content')
    <div class="content">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Pending Leave Requests</h5>
                @role('admin|super admin')
                    <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#add_leaves">
                        Apply New Leave
                    </a>
                @endrole
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="requestTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingLeaves as $k => $leave)
                            <tr id="row_{{ $leave->id }}">
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $leave->user->name ?? '-' }}</td>
                                <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                <td>{{ $leave->days }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm approveBtn" data-id="{{ $leave->id }}">
                                        Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm rejectBtn" data-id="{{ $leave->id }}">
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>All Leave Records</h5>
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
                        <a href="{{ route('employee.leave.requests') }}" class="btn btn-light w-100">Clear</a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="requestallTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allLeaves as $k => $leave)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $leave->user->name ?? '-' }}</td>
                                <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                <td>{{ $leave->days }}</td>
                                <td>
                                    <span
                                        class="badge 
                                        {{ $leave->status == 'Approved'
                                            ? 'bg-success'
                                            : ($leave->status == 'Rejected'
                                                ? 'bg-danger'
                                                : 'bg-warning text-dark') }}">
                                        {{ $leave->status }}
                                    </span>
                                    @if ($leave->status == 'Rejected' && $leave->comment)
                                        <br>
                                        <small class="text-danger">
                                            {{ $leave->comment }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if ($leave->status == 'Approved')
                                        {{ $leave->approver->name ?? 'N/A' }}
                                    @elseif($leave->status == 'Rejected')
                                        {{ $leave->approver->name ?? 'N/A' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $leave->reason }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rejectForm">
                    @csrf
                    <input type="hidden" id="reject_id">
                    <div class="modal-header">
                        <h5>Reject Leave</h5>
                    </div>
                    <div class="modal-body">
                        <label>Comment</label>
                        <textarea id="reject_comment" class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_leaves">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Leave</h4>
                    <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form id="leaveForm">
                    @csrf
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Employee *</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">
                                                {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text user_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Leave Type *</label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-control">
                                        <option value="">Select Leave Type</option>
                                    </select>
                                    <span class="text-danger error-text leave_type_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>From *</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control">
                                    <span class="text-danger error-text from_date_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>To *</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control">
                                    <span class="text-danger error-text to_date_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>No of Days *</label>
                                    <input type="text" name="days" id="days" class="form-control" readonly>
                                </div>
                            </div>
                            <input type="hidden" id="total_days">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Remaining Days *</label>
                                    <input type="text" name="remaining_days" id="remaining_days" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Reason *</label>
                                    <textarea name="reason" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" id="leaveSubmitBtn" class="btn btn-primary">
                            <span class="btn-text">Add Leave</span>
                            <span class="btn-loader d-none">
                                <i class="fa fa-spinner fa-spin"></i> Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#user_id').change(function() {
                let userId = $(this).val();

                if (!userId) {
                    $('#leave_type_id').html('<option value="">Select Leave Type</option>');
                    $('#remaining_days').val('');
                    return;
                }

                $.get("{{ url('employee/leave-requests/get-types') }}/" + userId, function(res) {

                    let options = '<option value="">Select Leave Type</option>';

                    $.each(res, function(key, val) {
                        options += `<option value="${val.leave_type_id}" 
                            data-remaining="${val.remaining_days}">
                            ${val.name} (Remaining: ${val.remaining_days})
                        </option>`;
                    });

                    $('#leave_type_id').html(options);
                });
            });

            function calculateDays() {
                let from = $('#from_date').val();
                let to = $('#to_date').val();

                if (from && to) {
                    let start = new Date(from);
                    let end = new Date(to);

                    let diff = (end - start) / (1000 * 3600 * 24);
                    let days = diff + 1;

                    if (days > 0) {
                        $('#days').val(days);
                    } else {
                        $('#days').val(0);
                    }
                }
            }

            function calculateRemaining() {

                let originalRemaining = $('#leave_type_id option:selected').data('remaining') || 0;
                let usedDays = parseInt($('#days').val()) || 0;

                let finalRemaining = originalRemaining - usedDays;

                if (finalRemaining < 0) {
                    finalRemaining = 0;
                }

                $('#remaining_days').val(finalRemaining);
            }

            $('#leave_type_id').change(function() {
                let remaining = $(this).find(':selected').data('remaining') || 0;
                $('#remaining_days').val(remaining);
                calculateRemaining();
            });

            $('#from_date, #to_date').change(function() {
                calculateDays();
                calculateRemaining();
            });
        });
        $('#leaveForm').submit(function(e) {
            e.preventDefault();

            let btn = $('#leaveSubmitBtn');

            // Show loader
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('employee.admin.leave.store') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {
                    toastr.success(res.message);

                    $('#add_leaves').modal('hide');
                    $('#leaveForm')[0].reset();
                    location.reload();
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, val) {
                            $('.' + key + '_error').text(val[0]);
                        });
                    } else {
                        toastr.error('Something went wrong');
                    }
                },

                complete: function() {
                    // Hide loader
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#requestTable').DataTable();
            $('#requestallTable').DataTable();

            $('.approveBtn').click(function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('employee.leave.request.approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function() {
                        toastr.success('Leave Approved');
                        $('#row_' + id).remove();
                    }
                });
            });

            $('.rejectBtn').click(function() {
                $('#reject_id').val($(this).data('id'));
                $('#rejectModal').modal('show');
            });

            $('#rejectForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('employee.leave.request.reject') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $('#reject_id').val(),
                        comment: $('#reject_comment').val()
                    },
                    success: function() {
                        toastr.error('Leave Rejected');
                        $('#rejectModal').modal('hide');
                        $('#row_' + $('#reject_id').val()).remove();
                    }
                });
            });

        });
    </script>
@endsection
