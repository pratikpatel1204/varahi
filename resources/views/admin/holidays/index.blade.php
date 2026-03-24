@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Holiday')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Holiday Master</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#add_holiday" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Holiday
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="holidaytable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Title</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holidays as $k => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $row->year->year ?? '-' }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->from_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->to_date)) }}</td>
                                    <td>
                                        @if ($row->type == 'Full')
                                            <span class="badge bg-success">Full</span>
                                        @else
                                            <span class="badge bg-warning">Half</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#edit_holiday" data-id="{{ $row->id }}"
                                            data-year="{{ $row->year_id }}" data-title="{{ $row->title }}"
                                            data-from="{{ $row->from_date }}" data-to="{{ $row->to_date }}"
                                            data-type="{{ $row->type }}" data-status="{{ $row->status }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_holiday">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="addHolidayForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Year</label>
                                <select name="year_id" class="form-select select2">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $y)
                                        <option value="{{ $y->id }}">{{ $y->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Holiday Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>From Date</label>
                                <input type="date" name="from_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>To Date</label>
                                <input type="date" name="to_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Holiday Type</label>
                                <select name="type" class="form-select">
                                    <option value="Full">Full Day</option>
                                    <option value="Half">Half Day</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_holiday">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="updateHolidayForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Year</label>
                                <select name="year_id" id="edit_year" class="form-select select2-edit">
                                    @foreach ($years as $y)
                                        <option value="{{ $y->id }}">{{ $y->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Holiday Title</label>
                                <input type="text" name="title" id="edit_title" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>From Date</label>
                                <input type="date" name="from_date" id="edit_from" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>To Date</label>
                                <input type="date" name="to_date" id="edit_to" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Holiday Type</label>
                                <select name="type" id="edit_type" class="form-select">
                                    <option value="Full">Full Day</option>
                                    <option value="Half">Half Day</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="status" id="edit_status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#holidaytable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(document).on('click', '.btn-edit', function() {

            $('#edit_id').val($(this).data('id'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_from').val($(this).data('from'));
            $('#edit_to').val($(this).data('to'));

            $('#edit_year').val($(this).data('year')).trigger('change');
            $('#edit_type').val($(this).data('type'));
            $('#edit_status').val($(this).data('status'));

        });


        $('#addHolidayForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.holidays.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    toastr.success(res.message);
                    $('#add_holiday').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 700);
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        $.each(xhr.responseJSON.errors, function(k, v) {
                            toastr.error(v[0]);
                        });
                    }
                }
            });
        });

        $('#updateHolidayForm').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.holidays.update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    toastr.success(res.message);
                    $('#edit_holiday').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 700);
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        $.each(xhr.responseJSON.errors, function(k, v) {
                            toastr.error(v[0]);
                        });
                    }
                }
            });
        });

        $('.btn-delete').click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Delete Holiday?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/holidays/delete/' + id,
                        method: 'GET',
                        success: function(res) {
                            $('#row_' + id).remove();
                            toastr.success(res.message);
                        },
                        error: function() {
                            toastr.error('Something went wrong');
                        }
                    });
                }
            });
        });
    </script>

@endsection
