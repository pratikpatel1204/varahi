@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Years List')
@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-1">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Years</li>
                        <li class="breadcrumb-item active" aria-current="page">Years List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Years List</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#add_year" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i> Add Year
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="yearTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($years as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <h6 class="fw-medium">{{ $row->year }}</h6>
                                    </td>
                                    <td>
                                        @if ($row->status == 'Active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="me-2 btn btn-sm btn-primary editYearBtn"
                                            data-id="{{ $row->id }}" data-year="{{ $row->year }}"
                                            data-status="{{ $row->status }}" data-bs-toggle="modal"
                                            data-bs-target="#edit_year">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger deleteYearBtn"
                                            data-id="{{ $row->id }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_year">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addYearForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Year</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Year</label>
                            <input type="text" name="year" maxlength="4" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
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
    <!-- EDIT YEAR MODAL -->
    <div class="modal fade" id="edit_year">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editYearForm">
                    @csrf
                    <input type="hidden" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Year</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Year</label>
                            <input type="text" name="year" id="edit_year_value" maxlength="4" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
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
            $('#yearTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true
            });
        });
        $(document).ready(function() {
            $('#addYearForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.years.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            $('#add_year').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });

            /* EDIT BUTTON CLICK */
            $('.editYearBtn').click(function() {
                $('#edit_id').val($(this).data('id'));
                $('#edit_year_value').val($(this).data('year'));
                $('#edit_status').val($(this).data('status'));
            });

            /* UPDATE YEAR */
            $('#editYearForm').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                $.ajax({
                    url: "/admin/years/update/" + id,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            $('#edit_year').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });

            /* DELETE YEAR */
            $('.deleteYearBtn').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This year will be deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/years/delete/" + id,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status) {
                                    toastr.success(response.message);
                                    $('#row_' + id).remove();
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
