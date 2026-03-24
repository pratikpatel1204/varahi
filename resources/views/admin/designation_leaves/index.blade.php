@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Designation Leave Quota')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5>Designation Leave Quota</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="designationTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation</th>
                            <th>Leave Types</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($designations as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>
                                    @foreach ($row->leaveTypes as $l)
                                        <span class="badge bg-info">
                                            {{ $l->name }} : {{ $l->pivot->total_days }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-assign" data-id="{{ $row->id }}">
                                        Assign
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="assignModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="assignForm">
                    @csrf
                    <input type="hidden" name="designation_id" id="designation_id">
                    <div class="modal-header">
                        <h5>Assign Leave Quota</h5>
                    </div>
                    <div class="modal-body">
                        <div id="leaveBox" class="row"></div>
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
    </div>
    <script>
        $(document).ready(function() {
            $('#designationTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true
            });
        });
        $(document).ready(function() {
            // OPEN MODAL
            $(document).on('click', '.btn-assign', function() {
                let id = $(this).data('id');
                $('#designation_id').val(id);
                $('#leaveBox').html('');
                $('#assignModal').modal('show');

                $.get("{{ url('admin/designation-leaves') }}/" + id + "/get", function(res) {
                    let html = '';
                    let row = null;
                    let days = '';
                    @foreach ($leaveTypes as $l)
                        row = res.find(x => x.leave_type_id == {{ $l->id }});
                        days = row ? row.total_days : '';
                        html += `
                            <div class="col-md-6 mb-3">
                                <label>{{ $l->name }}</label>
                                <input type="hidden" name="leave_type_id[]" value="{{ $l->id }}">
                                <input type="number"
                                    name="total_days[]"
                                    value="${days}"
                                    class="form-control"
                                    placeholder="Allowed Days"
                                    min="0">
                            </div>
                        `;
                    @endforeach
                    $('#leaveBox').html(html);
                }).fail(function() {
                    toastr.error('Failed to load leave types');
                });
            });


            // SAVE FORM
            $('#assignForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.designation.leaves.save') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.status) {
                            toastr.success('Leave quota assigned successfully');
                            $('#assignModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.message ?? 'Something went wrong');
                        }
                    },
                    error: function() {
                        toastr.error('Server error. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
