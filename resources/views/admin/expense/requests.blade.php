@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Expense Requests')
@section('content')
    <div class="content">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Expense Requests</h5>
                <a href="{{ route('admin.expense.reimbursement.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Expense
                </a>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label>Year</label>
                            <select name="year" class="form-control">
                                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Month</label>
                            <select name="month" class="form-control">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('admin.expense.reimbursement.list') }}" class="btn btn-light w-100">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pending Table --}}
        @if (!empty($pendingExpenses) && $pendingExpenses->count())
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <strong>Pending Expense Requests</strong>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="pendingTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Attachment</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pendingExpenses as $exp)
                                <tr>
                                    <td>
                                        <button class="btn btn-success btn-sm approveBtn" data-id="{{ $exp->id }}">
                                            Approve
                                        </button>

                                        <button class="btn btn-danger btn-sm rejectBtn" data-id="{{ $exp->id }}">
                                            Reject
                                        </button>
                                    </td>
                                    <td>{{ $exp->employee->name ?? '-' }}</td>
                                    <td>{{ ucfirst($exp->entry_type) }}</td>
                                    <td>{{ $exp->year_id }}</td>
                                    <td>{{ $exp->entry_month }}</td>
                                    <td>₹{{ number_format($exp->amount, 2) }}</td>
                                    <td>{{ $exp->description ?? '-' }}</td>
                                    <td>
                                        @if ($exp->bill)
                                            @php $files = json_decode($exp->bill, true); @endphp

                                            @foreach ($files as $file)
                                                <a href="{{ asset('uploads/expenses/' . $file) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary mb-1">
                                                    View
                                                </a>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        @endif

        {{-- All Table --}}
        <div class="card">
            <div class="card-header">
                <strong>All Expense Requests</strong>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="allTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Manager</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Remarks</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $exp)
                            <tr>
                                <td>{{ $exp->id }}</td>
                                <td>{{ $exp->employee->name ?? '-' }}</td>
                                <td>{{ $exp->reportingManager->name ?? '-' }}</td>
                                <td>{{ ucfirst($exp->entry_type) }}</td>
                                <td>₹{{ number_format($exp->amount, 2) }}</td>

                                <td>
                                    @if ($exp->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($exp->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>{{ $exp->approvedBy->name ?? '-' }}</td>
                                <td>{{ $exp->remarks ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($exp->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function initTable(id) {
                if ($(id).length && $(id + ' tbody tr').length > 0) {
                    $(id).DataTable();
                }
            }
            initTable('#pendingTable');
            initTable('#allTable');
        });
    </script>
    <script>
        $(document).ready(function() {

            // APPROVE
            $(document).on('click', '.approveBtn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Approve Expense?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(id, 'approved');
                    }
                });
            });

            // REJECT
            $(document).on('click', '.rejectBtn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Reject Expense',
                    input: 'text',
                    inputPlaceholder: 'Enter reason',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Reason is required!';
                        }
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Reject'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(id, 'rejected', result.value);
                    }
                });
            });

            // COMMON FUNCTION
            function updateStatus(id, status, remarks = '') {

                $.ajax({
                    url: "{{ route('admin.expense.reimbursement.update.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: status,
                        remarks: remarks
                    },

                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.message
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },

                    error: function() {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                });
            }

        });
    </script>
@endsection
