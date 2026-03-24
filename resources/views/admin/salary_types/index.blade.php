@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Salary Types')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between mb-3">
                <h3>Salary Types</h3>
                <a href="#" data-bs-toggle="modal" data-bs-target="#add_salary_type" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>Add Salary Type
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="salaryTypeTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryTypes as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        @if ($row->type == 'Earning')
                                            <span class="badge bg-success">Earning</span>
                                        @else
                                            <span class="badge bg-danger">Deduction</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->value }}
                                        @if ($row->value_type == 'fixed')
                                            /-
                                        @else
                                            %
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm editBtn"
                                            data-id="{{ $row->id }}" data-name="{{ $row->name }}"
                                            data-type="{{ $row->type }}" data-value="{{ $row->value }}"
                                            data-value_type="{{ $row->value_type }}" data-bs-toggle="modal"
                                            data-bs-target="#edit_salary_type">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteBtn"
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

    <!-- ADD MODAL -->
    <div class="modal fade" id="add_salary_type">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="addSalaryTypeForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Salary Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="Earning">Earning</option>
                                    <option value="Deduction">Deduction</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Value Type</label>
                                <select name="value_type" class="form-select" required>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Value</label>
                                <input type="number" name="value" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="edit_salary_type">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editSalaryTypeForm">
                    @csrf
                    <input type="hidden" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Salary Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Type</label>
                                <select name="type" id="edit_type" class="form-select">
                                    <option value="Earning">Earning</option>
                                    <option value="Deduction">Deduction</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Value Type</label>
                                <select name="value_type" id="edit_value_type" class="form-select">
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Value</label>
                                <input type="number" name="value" id="edit_value" class="form-control" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#salaryTypeTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true
            });
        });

        $(document).ready(function() {

            /* ADD SALARY TYPE */
            $('#addSalaryTypeForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.salary-types.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        $('#add_salary_type').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });


            /* EDIT BUTTON */
            $('.editBtn').click(function() {
                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_type').val($(this).data('type'));
                $('#edit_value').val($(this).data('value'));
                $('#edit_value_type').val($(this).data('value_type'));
            });

            /* UPDATE */
            $('#editSalaryTypeForm').submit(function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();
                $.ajax({
                    url: "/admin/salary-types/" + id,
                    type: "POST",
                    data: $(this).serialize() + "&_method=PUT",
                    success: function(res) {
                        toastr.success(res.message);
                        $('#edit_salary_type').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });

            /* DELETE */
            $('.deleteBtn').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Delete?',
                    text: 'Salary Type will be removed',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/salary-types/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                toastr.success(res.message);
                                $('#row_' + id).remove();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
