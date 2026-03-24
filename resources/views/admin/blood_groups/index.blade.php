@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Blood Groups')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Blood Groups</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Blood Group
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="bloodtable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bloodGroups as $key => $row)
                            <tr id="row_{{ $row->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>
                                    @if ($row->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn" data-id="{{ $row->id }}"
                                        data-name="{{ $row->name }}" data-status="{{ $row->status }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $row->id }}">
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

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Blood Group</h5>
                    </div>
                    <div class="modal-body">
                        <label>Blood Group</label>
                        <input type="text" name="name" class="form-control mb-2" required>
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Blood Group</h5>
                    </div>
                    <div class="modal-body">
                        <label>Blood Group</label>
                        <input type="text" id="edit_name" name="name" class="form-control mb-2" required>
                        <label>Status</label>
                        <select id="edit_status" name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#bloodtable').DataTable({
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

        $('#addForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.blood-groups.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message)
                    $('#addModal').modal('hide')
                    setTimeout(() => location.reload(), 600)
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        toastr.error(v[0])
                    })
                }
            })
        })

        $('.editBtn').click(function() {
            $('#edit_id').val($(this).data('id'))
            $('#edit_name').val($(this).data('name'))
            $('#edit_status').val($(this).data('status'))
        })

        $('#editForm').submit(function(e) {
            e.preventDefault()

            let id = $('#edit_id').val()
            $.ajax({
                url: "{{ route('admin.blood-groups.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message)
                    $('#editModal').modal('hide')
                    setTimeout(() => location.reload(), 600)
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        toastr.error(v[0])
                    })
                }
            })
        })

        $('.deleteBtn').click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Delete Blood Group?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/blood-groups/delete/' + id,
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
