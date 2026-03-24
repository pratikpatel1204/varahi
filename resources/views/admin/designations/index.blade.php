@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Designation')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between">
                <h2>Designations</h2>
                <a href="#" data-bs-toggle="modal" data-bs-target="#addDesignationModal" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>Add Designation
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="desigtable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designations as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $row->id }}"
                                            data-name="{{ $row->name }}" data-bs-toggle="modal"
                                            data-bs-target="#editDesignationModal">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        {{-- <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $row->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addDesignationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addDesignationForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Designation</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
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

    <div class="modal fade" id="editDesignationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editDesignationForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5>Edit Designation</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
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
            $('#desigtable').DataTable({
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

        $('#addDesignationForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.designations.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message)
                    $('#addDesignationModal').modal('hide')
                    setTimeout(() => location.reload(), 600)
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        toastr.error(v[0])
                    })
                }
            })
        })

        $(document).on('click', '.editBtn', function() {
            $('#edit_id').val($(this).data('id'))
            $('#edit_name').val($(this).data('name'))
        })


        $('#editDesignationForm').submit(function(e) {
            e.preventDefault()

            let id = $('#edit_id').val()
            $.ajax({
                url: "{{ route('admin.designations.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message)
                    $('#editDesignationModal').modal('hide')
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
                title: 'Delete Designation?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/designations/delete/' + id,
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
