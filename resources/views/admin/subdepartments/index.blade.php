@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Sub Department')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between">
                <h2>Sub Departments</h2>
                <a href="#" data-bs-toggle="modal" data-bs-target="#add_sub" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>Add Sub Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Employees</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subdepartments as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->department->name ?? '-' }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->code }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $row->employees_count ?? 0 }}
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
                                        <button class="btn btn-sm btn-primary editSubDeptBtn" data-id="{{ $row->id }}"
                                            data-department="{{ $row->department_id }}" data-name="{{ $row->name }}"
                                            data-code="{{ $row->code }}" data-status="{{ $row->status }}"
                                            data-bs-toggle="modal" data-bs-target="#editSubDeptModal">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteSubDept" data-id="{{ $row->id }}">
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

    <div class="modal fade" id="editSubDeptModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editSubDeptForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Sub Department</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Department</label>
                            <select name="department_id" id="edit_department" class="form-select select2-dept" required>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-select">
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

    <div class="modal fade" id="add_sub">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addSubDeptForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Sub Department</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Department</label>
                            <select name="department_id" class="form-select select2-dept" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $('#addSubDeptForm').submit(function(e) {

            e.preventDefault();

            $.ajax({

                url: "{{ route('admin.sub-departments.store') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {
                    toastr.success(res.message)
                    $('#add_sub').modal('hide')
                    setTimeout(function() {
                        location.reload()
                    }, 700)
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        toastr.error(v[0])
                    })
                }

            })

        })

        $(document).on('click', '.editSubDeptBtn', function() {
            $('#edit_id').val($(this).data('id'))
            $('#edit_department').val($(this).data('department')).trigger('change')
            $('#edit_name').val($(this).data('name'))
            $('#edit_code').val($(this).data('code'))
            $('#edit_status').val($(this).data('status'))

        })

        $('#editSubDeptForm').submit(function(e) {
            e.preventDefault()

            let id = $('#edit_id').val()

            $.ajax({

                url: "{{ route('admin.sub-departments.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message)
                    $('#editSubDeptModal').modal('hide')
                    setTimeout(function() {
                        location.reload()
                    }, 700)
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        toastr.error(v[0])
                    })
                }

            })

        })

        $('.deleteSubDept').click(function() {
            let id = $(this).data('id')
            Swal.fire({
                title: 'Delete Sub Department?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/sub-departments/delete/' + id,
                        method: 'GET',
                        success: function(res) {
                            $('#row_' + id).remove();
                            toastr.success(res.message);
                        }
                    })
                }
            })
        })

        $(document).ready(function() {
            function initSelect2() {
                $('.select2-dept').each(function() {
                    let modal = $(this).closest('.modal')
                    $(this).select2({
                        width: '100%',
                        dropdownParent: modal
                    })
                })
            }
            initSelect2()
            $('.modal').on('shown.bs.modal', function() {
                initSelect2()
            })
        })
    </script>

@endsection
