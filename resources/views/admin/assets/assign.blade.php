@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Assign Assets')

@section('content')

    <style>
        .asset-check {
            display: none;
        }

        /* Default */
        .asset-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            transition: 0.3s;
            background: #fff;
        }

        /* Hover */
        .asset-box:hover {
            border-color: #0d6efd;
            background: #f8fbff;
        }

        /* Selected (Blue) */
        .asset-check:checked+label .asset-box {
            border-color: #0d6efd;
            background: #e7f1ff;
        }

        /* Assigned (Green) */
        .asset-box.assigned {
            border-color: #198754 !important;
            background: #e9f7ef !important;
        }

        /* Assigned + Checked stays Green */
        .asset-check:checked+label .asset-box.assigned {
            border-color: #198754 !important;
            background: #e9f7ef !important;
        }

        .asset-text {
            font-weight: 600;
        }

        .asset-code {
            font-size: 13px;
            color: #6c757d;
        }
    </style>

    <div class="content">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Assign Assets to Employee</h5>
                </div>

                <div class="card-body">
                    <form id="assignAssetForm">
                        @csrf

                        {{-- Employee --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Employee</label>
                            <select name="employee_id" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->name }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Assets --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">
                                    Select Assets
                                </label>

                                <div>
                                    <span id="selectedCount" class="me-3 text-muted">0 selected</span>

                                    <button type="button" class="btn btn-sm btn-primary" id="toggleAssets">
                                        Select All
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($assets as $asset)
                                    <div class="col-md-4 mb-3">
                                        <input type="checkbox" class="asset-check" name="asset_ids[]"
                                            value="{{ $asset->id }}" id="asset_{{ $asset->id }}">

                                        <label for="asset_{{ $asset->id }}" class="w-100">
                                            <div class="asset-box">
                                                <div class="asset-text">
                                                    {{ $asset->name }}
                                                </div>
                                                <div class="asset-code">
                                                    {{ $asset->code }}
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success px-4" id="btnAssign">
                                <span class="btn-text">
                                    <i class="ti ti-device-floppy me-1"></i> Assign Assets
                                </span>
                                <span class="btn-loader d-none">
                                    <i class="fa fa-spinner fa-spin"></i> Assigning...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        $(document).ready(function() {

            let allSelected = false;

            // ✅ Select All Toggle
            $('#toggleAssets').click(function() {
                allSelected = !allSelected;
                $('.asset-check').prop('checked', allSelected);
                $(this).text(allSelected ? 'Deselect All' : 'Select All');
                updateCount();
            });

            // ✅ Count Selected
            $(document).on('change', '.asset-check', function() {
                updateCount();
            });

            function updateCount() {
                let count = $('.asset-check:checked').length;
                $('#selectedCount').text(count + ' selected');
            }

            // ✅ Load Assigned Assets on Employee Change
            $('select[name="employee_id"]').change(function() {

                let employeeId = $(this).val();

                // Reset all
                $(".asset-check").prop('checked', false);
                $(".asset-box").removeClass('assigned');

                if (employeeId) {
                    $.ajax({
                        url: '{{ route("admin.get.assigned.assets", ":id") }}'.replace(':id', employeeId),
                        type: 'GET',

                        success: function(assignedIds) {

                            assignedIds.forEach(function(id) {
                                let checkbox = $('#asset_' + id);

                                checkbox.prop('checked', true);

                                let box = $('label[for="asset_' + id + '"]').find(
                                    '.asset-box');
                                box.addClass('assigned');
                            });

                            updateCount();
                        }
                    });
                }
            });

            // ✅ AJAX Submit
            $('#assignAssetForm').submit(function(e) {
                e.preventDefault();

                let form = $(this);

                $('#btnAssign').prop('disabled', true);
                $('#btnAssign .btn-text').addClass('d-none');
                $('#btnAssign .btn-loader').removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.assign.assets.store') }}",
                    type: "POST",
                    data: form.serialize(),

                    success: function(res) {
                        toastr.success(res.message || 'Assets Assigned Successfully');

                        form[0].reset();
                        $('.asset-check').prop('checked', false);
                        $('.asset-box').removeClass('assigned');
                        $('#toggleAssets').text('Select All');
                        updateCount();
                    },

                    error: function(err) {
                        toastr.error('Something went wrong');
                    },

                    complete: function() {
                        $('#btnAssign').prop('disabled', false);
                        $('#btnAssign .btn-text').removeClass('d-none');
                        $('#btnAssign .btn-loader').addClass('d-none');
                    }
                });
            });

        });
    </script>

@endsection
