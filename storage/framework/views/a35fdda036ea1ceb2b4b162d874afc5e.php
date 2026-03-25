
<?php $__env->startSection('title', config('app.name') . ' || Create Expense'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Create Expense</h3>
            </div>
            <div class="card-body">
                <form id="expenseForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="employee_id" id="employee_select" required>
                                <option value="">-- Select Employee --</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emp->id); ?>" data-name="<?php echo e($emp->name); ?>"
                                        data-phone="<?php echo e($emp->phone); ?>" data-code="<?php echo e($emp->employee_code); ?>"
                                        data-department="<?php echo e($emp->department->name ?? ''); ?>"
                                        data-subdept="<?php echo e($emp->subDepartment->name ?? ''); ?>"
                                        data-designation="<?php echo e($emp->designation->name ?? ''); ?>">
                                        <?php echo e($emp->name); ?> (<?php echo e($emp->employee_code); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-2 bg-light h-100">
                                <p><strong>Name:</strong> <span id="emp_name"></span></p>
                                <p><strong>Phone:</strong> <span id="emp_phone"></span></p>
                                <p><strong>Code:</strong> <span id="emp_code"></span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-2 bg-light h-100">
                                <p><strong>Department:</strong> <span id="emp_department"></span></p>
                                <p><strong>Sub Dept:</strong> <span id="emp_subdept"></span></p>
                                <p><strong>Designation:</strong> <span id="emp_designation"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Entry Type <span class="text-danger">*</span></label>
                            <select name="entry_type" class="form-select" required>
                                <option value="advance">Advance Salary</option>
                                <option value="expense">Expense</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year <span class="text-danger">*</span></label>
                            <select name="year_id" class="form-select" required>
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->year); ?>" <?php echo e($year->year == now()->year ? 'selected' : ''); ?>>
                                        <?php echo e($year->year); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Month <span class="text-danger">*</span></label>
                            <select name="entry_month" class="form-select" required>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($month->month_name); ?>"
                                        <?php echo e(strtolower($month->month_name) == strtolower(now()->format('F')) ? 'selected' : ''); ?>>
                                        <?php echo e($month->month_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" rows="3" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label">Attachment <span class="text-danger">*</span></label>
                            <input type="file" name="attachment[]" class="form-control" multiple required>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <span class="btn-text">Save Expense</span>
                            <span class="spinner-border spinner-border-sm d-none" id="btnLoader"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#employee_select').on('change', function() {
                let option = this.options[this.selectedIndex];
                if (!this.value) return;
                $('#emp_name').text(option.getAttribute('data-name') || '-');
                $('#emp_phone').text(option.getAttribute('data-phone') || '-');
                $('#emp_code').text(option.getAttribute('data-code') || '-');
                $('#emp_department').text(option.getAttribute('data-department') || '-');
                $('#emp_subdept').text(option.getAttribute('data-subdept') || '-');
                $('#emp_designation').text(option.getAttribute('data-designation') || '-');
            });

            $('#expenseForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);

                let btn = $('#submitBtn');
                let loader = $('#btnLoader');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.prop('disabled', true);
                loader.removeClass('d-none');
                $('.btn-text').text('Saving...');

                $.ajax({
                    url: "<?php echo e(route('admin.expense.reimbursement.store')); ?>",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(res) {
                        btn.prop('disabled', false);
                        loader.addClass('d-none');
                        $('.btn-text').text('Save Expense');

                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.message
                            }).then(() => {
                                window.location.href =
                                    "<?php echo e(route('admin.expense.reimbursement.list')); ?>";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false);
                        loader.addClass('d-none');
                        $('.btn-text').text('Save Expense');

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');

                                input.after(
                                    '<span class="invalid-feedback text-danger d-block">' +
                                    value[0] + '</span>');
                            });
                        } else {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        }
                    }
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\Varahi\resources\views/admin/expense/create.blade.php ENDPATH**/ ?>