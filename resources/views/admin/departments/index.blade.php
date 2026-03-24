@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Departments')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between mb-3">
                <h2>Departments</h2>
                <a href="#" data-bs-toggle="modal" data-bs-target="#add_dept" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>Add Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="deptTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Employees</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->code }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $row->employees_count }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($row->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editDept" data-id="{{ $row->id }}"
                                            data-name="{{ $row->name }}" data-code="{{ $row->code }}"
                                            data-status="{{ $row->status }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteDept" data-id="{{ $row->id }}">
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
    <div class="modal fade" id="add_dept">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addDeptForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Department</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
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
    <div class="modal fade" id="edit_dept">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editDeptForm">
                    @csrf
                    <input type="hidden" name="id" id="dept_id">
                    <div class="modal-header">
                        <h5>Edit Department</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="dept_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" id="dept_code" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="dept_status" class="form-select">
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
            $('#deptTable').DataTable({
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

        // ADD DEPARTMENT
        $('#addDeptForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.departments.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    $('#add_dept').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                }
            });
        });

        // EDIT BUTTON
        $('.editDept').click(function() {
            $('#dept_id').val($(this).data('id'));
            $('#dept_name').val($(this).data('name'));
            $('#dept_code').val($(this).data('code'));
            $('#dept_status').val($(this).data('status'));
            $('#edit_dept').modal('show');
        });

        // UPDATE
        $('#editDeptForm').submit(function(e) {
            e.preventDefault();

            let id = $('#dept_id').val();
            $.ajax({
                url: "/admin/departments/update/" + id,
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    $('#edit_dept').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                }
            });
        });

        // DELETE
        $('.deleteDept').click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Delete Department?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/departments/delete/' + id,
                        method: 'GET',
                        success: function(res) {
                            $('#row_' + id).remove();
                            toastr.success(res.message);
                        }
                    });
                }
            });

        });
    </script>
@endsection
