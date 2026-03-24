
<?php $__env->startSection('title', config('app.name') . ' || Edit Employee'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Edit Employee</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Employee</li>
                        <li class="breadcrumb-item active">Edit Employee</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- /Breadcrumb -->
        <div class="card">
            <div class="card-body">
                <form id="EditEmployeeForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                    <div class="row mb-2">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Employee Code <small class="text-danger">*</small></label>
                            <input type="text" name="employee_code" class="form-control"
                                value="<?php echo e(old('employee_code', $employee->employee_code)); ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Name <small class="text-danger">*</small></label>
                            <input type="text" name="name" class="form-control"
                                value="<?php echo e(old('name', $employee->name)); ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Email <small class="text-danger">*</small></label>
                            <input type="email" name="email" class="form-control"
                                value="<?php echo e(old('email', $employee->email)); ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password">
                                <span class="input-group-text" onclick="togglePassword('password')"><i class="ti ti-eye"
                                        id="passwordIcon"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation">
                                <span class="input-group-text" onclick="togglePassword('password_confirmation')">
                                    <i class="ti ti-eye" id="passwordConfirmIcon"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Phone <small class="text-danger">*</small></label>
                            <input type="text" name="phone" class="form-control"
                                value="<?php echo e(old('phone', $employee->phone)); ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Gender <small class="text-danger">*</small></label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" <?php echo e(old('gender', $employee->gender) == 'male' ? 'selected' : ''); ?>>
                                    Male
                                </option>
                                <option value="female" <?php echo e(old('gender', $employee->gender) == 'female' ? 'selected' : ''); ?>>
                                    Female
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">DOB <small class="text-danger">*</small></label>
                            <input type="date" name="dob" class="form-control"
                                value="<?php echo e(old('dob', $employee->dob)); ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Designation *</label>
                            <select name="designation" id="manager_designation" class="form-select">
                                <option value="">Select</option>
                                <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $des): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($des->name); ?>"
                                        <?php echo e(old('designation', $employee->getRoleNames()->first()) == $des->name ? 'selected' : ''); ?>>
                                        <?php echo e($des->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Reporting Manager</label>
                            <select name="reporting_manager" id="reporting_manager" class="form-select">
                                <option value="">Select Manager</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Department *</label>
                            <select name="department_id" id="department" class="form-select">
                                <option value="">Select</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>"
                                        <?php echo e(old('department_id', $employee->department_id) == $dept->id ? 'selected' : ''); ?>>
                                        <?php echo e($dept->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Sub Department *</label>
                            <select name="sub_department_id" id="sub_department" class="form-select">
                                <option value="">Select Sub Department</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Skill Type <small class="text-danger">*</small></label>
                            <select id="skill_type" name="skill_type" class="form-select">
                                <option value="">Select</option>
                                <option value="skilled"
                                    <?php echo e(old('skill_type', $employee->skill_type) == 'skilled' ? 'selected' : ''); ?>>
                                    Skilled
                                </option>
                                <option value="semi_skilled"
                                    <?php echo e(old('skill_type', $employee->skill_type) == 'semi_skilled' ? 'selected' : ''); ?>>
                                    Semi Skilled
                                </option>
                                <option value="non_skilled"
                                    <?php echo e(old('skill_type', $employee->skill_type) == 'non_skilled' ? 'selected' : ''); ?>>
                                    Non Skilled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">
                            <?php if($employee->profile_image): ?>
                                <img src="<?php echo e(asset($employee->profile_image)); ?>" width="80" class="mt-2 rounded">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 my-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="pf_applicable"
                                    name="pf_applicable" value="1"
                                    <?php echo e(isset($employee->pf_applicable) && $employee->pf_applicable == 1 ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="pf_applicable">
                                    PF Applicable
                                </label>
                            </div>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="esic_applicable"
                                    name="esic_applicable" value="1"
                                    <?php echo e(isset($employee->esic_applicable) && $employee->esic_applicable == 1 ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="esic_applicable">
                                    ESIC Applicable
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Address <small class="text-danger">*</small></label>
                            <textarea name="address" class="form-control" rows="2"><?php echo e(old('address', $employee->address)); ?></textarea>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <a href="<?php echo e(route('admin.employees.index')); ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" id="updateEmployeeBtn" class="btn btn-primary">
                            <span class="btn-text">Update Employee</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm"></span> Updating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            let selectedManager = "<?php echo e($employee->reporting_manager); ?>";

            $('#manager_designation').on('change', function() {
                let id = $(this).val();

                if (id) {
                    $.get("<?php echo e(route('admin.get.reporting.manager', ':id')); ?>".replace(':id', id), function(
                        data) {

                        let html = '<option value="">Select</option>';

                        $.each(data, function(i, v) {
                            let selected = (v.id == selectedManager) ? 'selected' : '';
                            html +=
                                `<option value="${v.id}" ${selected}>${v.name}</option>`;
                        });

                        $('#reporting_manager').html(html);
                    });
                }
            });

            // ✅ Trigger on page load
            $('#manager_designation').trigger('change');

            let dept = "<?php echo e($employee->department_id); ?>";
            let sub = "<?php echo e($employee->sub_department_id); ?>";

            function loadSub(deptId, selected = null) {
                if (!deptId) return;

                let url = "<?php echo e(route('admin.get.subdepartments', ':id')); ?>".replace(':id', deptId);

                $.get(url, function(data) {
                    let opt = '<option value="">Select</option>';

                    data.forEach(d => {
                        let isSelected = (d.id == selected) ? 'selected' : '';
                        opt += `<option value="${d.id}" ${isSelected}>${d.name}</option>`;
                    });

                    $('#sub_department').html(opt);
                });
            }

            // ✅ Load on edit
            if (dept) {
                loadSub(dept, sub);
            }

            // ✅ Change event
            $('#department').change(function() {
                loadSub($(this).val());
            });

        });

        let dept = "<?php echo e($employee->department_id); ?>";
        let sub = "<?php echo e($employee->sub_department_id); ?>";

        function loadSub(deptId, selected = null) {
            if (!deptId) return;
            let url = "<?php echo e(route('admin.get.subdepartments', ':id')); ?>".replace(':id', deptId);
            $.get(url, function(data) {
                let opt = '<option value="">Select</option>';
                data.forEach(d => {
                    opt += `<option value="${d.id}" ${d.id==selected?'selected':''}>${d.name}</option>`;
                });
                $('#sub_department').html(opt);
            });
        }

        if (dept) loadSub(dept, sub);
        $('#department').change(function() {
            loadSub($(this).val());
        });

        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = fieldId === 'password' ?
                document.getElementById('passwordIcon') :
                document.getElementById('passwordConfirmIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("ti-eye");
                icon.classList.add("ti-eye-off");
            } else {
                input.type = "password";
                icon.classList.remove("ti-eye-off");
                icon.classList.add("ti-eye");
            }
        }
    </script>
    <script>
        $('#EditEmployeeForm').on('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);
            let btn = $('#updateEmployeeBtn');

            // ✅ START LOADER
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $(".error-text").remove();
            $(".is-invalid").removeClass("is-invalid");

            $.ajax({
                url: "<?php echo e(route('admin.employees.update')); ?>",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {

                    // ✅ STOP LOADER
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');

                    toastr.success(res.message || 'Employee updated successfully');

                    setTimeout(() => {
                        window.location.href = "<?php echo e(route('admin.employees.index')); ?>";
                    }, 1500);
                },

                error: function(xhr) {

                    // ✅ STOP LOADER
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, message) {

                            let input = $("[name='" + field + "']");
                            input.addClass("is-invalid");

                            input.after(
                                '<span class="text-danger error-text">' +
                                message[0] +
                                '</span>'
                            );
                        });

                        toastr.error('Please fix the errors');

                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\varahi\resources\views/admin/employees/edit.blade.php ENDPATH**/ ?>