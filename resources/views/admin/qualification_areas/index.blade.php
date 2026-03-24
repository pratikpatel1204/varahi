@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Qualification Area')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Qualification Area List</h5>
                <button class="btn btn-primary" id="btnAdd">
                    <i class="ti ti-plus"></i> Add Qualification Area
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="AreaTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Area Name</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $key => $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->area_name }}</td>
                                    <td>
                                        @if ($row->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success btnEdit" data-id="{{ $row->id }}"
                                            data-name="{{ $row->area_name }}" data-status="{{ $row->status }}">
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

    <div class="modal fade" id="areaModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="areaForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <h5 id="modalTitle">Add Qualification Area</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Area Name *</label>
                            <input type="text" name="area_name" id="area_name" class="form-control">
                            <span class="text-danger error-text area_name_error"></span>
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
            $('#AreaTable').DataTable();
            let modal = new bootstrap.Modal(document.getElementById('areaModal'));

            $('#btnAdd').click(function() {
                $('#areaForm')[0].reset();
                $('#id').val('');
                $('#modalTitle').text('Add Qualification Area');
                $('.error-text').text('');
                modal.show();
            });

            $('.btnEdit').click(function() {
                $('#modalTitle').text('Edit Qualification Area');
                $('#id').val($(this).data('id'));
                $('#area_name').val($(this).data('name'));
                $('#status').val($(this).data('status'));
                $('.error-text').text('');
                modal.show();
            });

            $('#areaForm').submit(function(e) {
                e.preventDefault();

                let id = $('#id').val();
                let url = id ?
                    "{{ route('admin.qualification.areas.update') }}" :
                    "{{ route('admin.qualification.areas.store') }}";

                $.ajax({

                    url: url,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        Swal.fire('Success', res.message ?? 'Saved Successfully', 'success');
                        modal.hide();
                        setTimeout(() => {
                            location.reload();
                        }, 800);
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
                    text: "Qualification Area will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/qualification-areas/delete') }}/" + id,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                $('#row_' + id).remove();
                                Swal.fire('Deleted', res.message ??
                                    'Deleted Successfully', 'success');
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
