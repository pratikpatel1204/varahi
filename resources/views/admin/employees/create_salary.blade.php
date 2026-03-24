@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Create Employee Salary')
@section('content')
    <div class="content">
        <div class="card shadow-sm emp-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary">Create Salary</h5>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="auto_calculation" name="auto_calculation"
                        value="1" checked>
                    <label class="form-check-label" for="auto_calculation">
                        Auto Calculation
                    </label>
                </div>
            </div>
            <div class="card-body">
                <form id="salaryForm" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $empid }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->year }}" {{ $year->year == date('Y') ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Salary Basis</label>
                            <select name="salary_basis" class="form-select">
                                <option value="monthly" selected>Monthly</option>
                                <option value="daily">Daily</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select">
                                <option value="bank" selected>Bank</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gross Salary</label>
                            <input type="number" name="gross_Salary" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-center bg-success p-2">
                                <h5 class="text-white">Earnings</h5>
                            </div>
                            @php
                                $earningTypes = $salaryTypes->where('type', 'Earning');
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($earningTypes as $earning)
                                        <tr>
                                            <td>{{ $earning->name }}</td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" class="form-control earning-value"
                                                        value="{{ $earning->value }}" data-type="{{ $earning->value_type }}"
                                                        data-id="{{ $earning->id }}" step="0.01" readonly>

                                                    <span class="input-group-text">
                                                        {{ $earning->value_type == 'percentage' ? '%' : '₹' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="earnings[{{ $earning->id }}]"
                                                    class="form-control earning-input" step="0.01">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Deductions -->
                        <div class="col-md-6">
                            <div class="text-center bg-danger p-2">
                                <h5 class="text-white">Deductions</h5>
                            </div>

                            @php
                                $deductionTypes = $salaryTypes->where('type', 'Deduction');
                            @endphp

                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($deductionTypes as $deduction)
                                        <tr>
                                            <td>{{ $deduction->name }}</td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" class="form-control deduction-value"
                                                        value="{{ $deduction->value }}"
                                                        data-type="{{ $deduction->value_type }}"
                                                        data-id="{{ $deduction->id }}" step="0.01" readonly>

                                                    <span class="input-group-text">
                                                        {{ $deduction->value_type == 'percentage' ? '%' : '₹' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="deductions[{{ $deduction->id }}]"
                                                    class="form-control deduction-input" step="0.01">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Total Earnings</label>
                            <input type="number" name="total_earning" id="totalEarning" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Deductions</label>
                            <input type="number" name="total_deduction" id="totalDeduction" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Net Salary</label>
                            <input type="number" name="net_salary" id="netSalary" class="form-control fw-bold text-success"
                                readonly>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" id="saveSalaryBtn" class="btn btn-primary w-auto">
                            <span class="btn-text">Save Salary</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm"></span> Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function calculateSalary() {

            if (!$('#auto_calculation').is(':checked')) {
                return;
            }

            let gross = parseFloat($('input[name="gross_Salary"]').val()) || 0;

            let basic = 0;
            let hra = 0;
            let totalEarning = 0;
            let totalDeduction = 0;

            $('.earning-value').each(function() {

                let value = parseFloat($(this).val()) || 0;
                let type = $(this).data('type');
                let id = $(this).data('id');
                let name = $(this).closest('tr').find('td:first').text().trim();

                let amount = 0;

                if (name === 'Basic Salary') {
                    amount = (gross * value) / 100;
                    basic = amount;
                } else if (name === 'House Rent Allowance') {
                    amount = (basic * value) / 100;
                    hra = amount;
                } else if (name === 'Other') {
                    amount = gross - (basic + hra);
                    if (amount < 0) amount = 0;
                } else {
                    amount = (type === 'percentage') ? (gross * value) / 100 : value;
                }

                $('input[name="earnings[' + id + ']"]').val(amount.toFixed(2));
                totalEarning += amount;
            });

            $('.deduction-value').each(function() {

                let value = parseFloat($(this).val()) || 0;
                let type = $(this).data('type');
                let id = $(this).data('id');
                let name = $(this).closest('tr').find('td:first').text().trim();

                let amount = 0;

                if (name === 'EPF') {
                    amount = (basic * value) / 100;
                    if (amount > 1800) amount = 1800;
                } else if (name === 'ESIC') {
                    amount = (gross * value) / 100;
                } else {
                    amount = (type === 'percentage') ? (gross * value) / 100 : value;
                }

                $('input[name="deductions[' + id + ']"]').val(amount.toFixed(2));
                totalDeduction += amount;
            });

            let net = gross - totalDeduction;

            $('#totalEarning').val(totalEarning.toFixed(2));
            $('#totalDeduction').val(totalDeduction.toFixed(2));
            $('#netSalary').val(net.toFixed(2));
        }
        $(document).on('input', 'input[name="gross_Salary"], .earning-value, .deduction-value', function() {
            calculateSalary();
        });

        $('#auto_calculation').on('change', function() {
            calculateSalary();
        });
        $('#salaryForm').on('submit', function(e) {
            e.preventDefault();

            let btn = $('#saveSalaryBtn');

            // SHOW LOADER
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.employees.store.salary') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Salary saved successfully',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); 
                    });
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');

                    $('.text-danger').remove();
                    $('.is-invalid').removeClass('is-invalid');

                    let msg = "Something went wrong";

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;
                        msg = Object.values(errors)[0][0];
                        $.each(errors, function(key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.next('.text-danger').remove();
                            input.after('<div class="text-danger small mt-1">' + value[0] + '</div>');
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });
    </script>
@endsection
