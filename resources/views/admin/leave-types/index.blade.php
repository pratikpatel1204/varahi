@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Leave Types')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Leave Types</h5>
                <button class="btn btn-primary" id="btnAdd">
                    <i class="ti ti-plus"></i> Add Leave
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="leavetable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveTypes as $k => $row)
                            <tr id="row_{{ $row->id }}">
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>
                                    @if ($row->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btnEdit" data-id="{{ $row->id }}"
                                        data-name="{{ $row->name }}" data-status="{{ $row->status }}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btnDelete" data-id="{{ $row->id }}">
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
    <div class="modal fade" id="leaveModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="leaveForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <h5 id="modalTitle">Add Leave</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <span class="text-danger error-text status_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#leavetable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    searching: true,
                    responsive: true
                });
            });
            $(document).ready(function() {
                let modal = new bootstrap.Modal(document.getElementById('leaveModal'));
                $('#btnAdd').click(function() {
                    $('#leaveForm')[0].reset();
                    $('#id').val('');
                    $('.error-text').text('');
                    $('#modalTitle').text('Add Leave');
                    modal.show();
                });

                $('.btnEdit').click(function() {
                    $('#modalTitle').text('Edit Leave');
                    $('#id').val($(this).data('id'));
                    $('#name').val($(this).data('name'));
                    $('#status').val($(this).data('status'));
                    $('.error-text').text('');
                    modal.show();
                });

                $('#leaveForm').submit(function(e) {
                    e.preventDefault();

                    let id = $('#id').val();
                    let url = id ?
                        "{{ route('admin.leave-types.update') }}" :
                        "{{ route('admin.leave-types.store') }}";

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.status) {
                                modal.hide();
                                toastr.success(res.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 800);
                            }
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

                $('.btnDelete').click(function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Delete?',
                        text: "This record will be deleted",
                        icon: 'warning',
                        showCancelButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('admin/leave-types/delete') }}/" + id,
                                method: "GET",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(res) {
                                    if (res.status) {
                                        $('#row_' + id).remove();
                                        toastr.success(res.message);
                                    }
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
