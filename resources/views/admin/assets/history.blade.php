@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Assign Assets')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <form method="GET" id="filterForm" class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Select Assets</label>
                        <select class="select2 form-control" id="select2-placeholder-single" name="asset_id">
                            <option value="">All Assets</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-12">
                        <label class="form-label fw-semibold">Assigned Date Range</label>
                        <div class="input-group">
                            <input type="date" name="assign_from" class="form-control"
                                value="{{ request('assign_from') }}">
                            <span class="input-group-text">to</span>
                            <input type="date" name="assign_to" class="form-control" value="{{ request('assign_to') }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label class="form-label fw-semibold">Return Date Range</label>
                        <div class="input-group">
                            <input type="date" name="return_from" class="form-control"
                                value="{{ request('return_from') }}">
                            <span class="input-group-text">to</span>
                            <input type="date" name="return_to" class="form-control" value="{{ request('return_to') }}">
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.assets.history') }}" class="btn btn-secondary">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="assetHistoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Asset</th>
                                <th>Assigned By</th>
                                <th>Assigned Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.return-btn', function() {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "This asset will be marked as returned",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Return',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }

            });
        });
    </script>

    <script>
        $(function() {
            let table = $('#assetHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: "{{ route('admin.assets.history.ajax') }}",
                    data: function(d) {
                        d.asset_id = $('select[name=asset_id]').val();
                        d.assign_from = $('input[name=assign_from]').val();
                        d.assign_to = $('input[name=assign_to]').val();
                        d.return_from = $('input[name=return_from]').val();
                        d.return_to = $('input[name=return_to]').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'asset',
                        name: 'asset'
                    },
                    {
                        data: 'assigned_by',
                        name: 'assigned_by'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#filterForm').on('submit', function(e) {
                e.preventDefault(); 
                table.ajax.reload();
            });

        });
    </script>

@endsection
