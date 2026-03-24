@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Course')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h3 class="mb-0">Course List</h3>
                <button class="btn btn-primary" id="btnAdd">
                    <i class="ti ti-plus"></i> Add Course
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="CourseTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->course_name }}</td>
                                    <td>
                                        @if ($row->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success btnEdit" data-id="{{ $row->id }}"
                                            data-name="{{ $row->course_name }}" data-status="{{ $row->status }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn-delete btn btn-sm btn-secondary" data-id="{{ $row->id }}">
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

    <div class="modal fade" id="courseModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="courseForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <h5 id="modalTitle">Add Course</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Course Name</label>
                            <input type="text" name="course_name" id="course_name" class="form-control">
                            <span class="text-danger error-text course_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
        $(document).ready(function() {
            $('#CourseTable').DataTable();

            let modal = new bootstrap.Modal(document.getElementById('courseModal'));

            $('#btnAdd').click(function() {
                $('#courseForm')[0].reset();
                $('#id').val('');
                $('#modalTitle').text('Add Course');
                $('.error-text').text('');
                modal.show();
            });

            $('.btnEdit').click(function() {
                $('#modalTitle').text('Edit Course');
                $('#id').val($(this).data('id'));
                $('#course_name').val($(this).data('name'));
                $('#status').val($(this).data('status'));
                modal.show();
            });

            $('#courseForm').submit(function(e) {
                e.preventDefault();

                let id = $('#id').val();
                let url = id ?
                    "{{ route('admin.courses.update') }}" :
                    "{{ route('admin.courses.store') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        toastr.success(res.message ?? 'Saved Successfully');
                        modal.hide();
                        setTimeout(() => {
                            location.reload();
                        }, 700);
                    },
                    error: function(err) {
                        if (err.status == 422) {
                            let errors = err.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                $('.' + key + '_error').text(val[0]);
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Delete?',
                    text: 'Course will be removed!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/courses/delete') }}/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                $('#row_' + id).remove();
                                toastr.success(res.message ?? 'Deleted Successfully');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
