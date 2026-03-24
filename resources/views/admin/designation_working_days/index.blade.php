@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Assign Working Days')
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5>Assign Working Days</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="workingDaysTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation</th>
                            <th>Working Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($designations as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>
                                    @foreach ($row->workingDays as $day)
                                        <span class="badge bg-info">
                                            {{ $day->day_name }}
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
    {{-- Assign Modal --}}
    <div class="modal fade" id="assignModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="assignForm">
                    @csrf
                    <input type="hidden" name="designation_id" id="designation_id">
                    <div class="modal-header">
                        <h5>Assign Working Days</h5>
                    </div>
                    <div class="modal-body">
                        <div id="daysBox" class="row">
                            <!-- Checkboxes via JS -->
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
    </div>
    <script>
        $(document).ready(function() {
            $('#workingDaysTable').DataTable();
        });

        $(document).on('click', '.btn-assign', function() {

            let id = $(this).data('id');

            $('#designation_id').val(id);

            $('#daysBox').html('');

            $('#assignModal').modal('show');

            $.get("{{ url('admin/designation-working-days') }}/" + id + "/get-days", function(res) {

                let html = '';

                @foreach ($days as $day)
                    html += `
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $day->id }}" ${res.includes({{ $day->id }}) ? 'checked' : ''}>
                            <label class="form-check-label">{{ $day->day_name }}</label>
                        </div>
                    </div>
                    `;
                @endforeach
                $('#daysBox').html(html);
            });
        });

        $('#assignForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.designation-working-days.save-modal') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status) {
                        $('#assignModal').modal('hide');
                        toastr.success('Working days assigned successfully');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error('Something went wrong');
                    }
                }
            });
        });
    </script>
@endsection
