
<?php $__env->startSection('title', config('app.name') . ' || Employee Dashboard'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Employee Dashboard</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            Dashboard
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Employee Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="alert bg-secondary-transparent alert-dismissible fade show mb-4 leave-alert"
                data-id="<?php echo e($leave->id); ?>">

                Your Leave Request on
                <strong><?php echo e(\Carbon\Carbon::parse($leave->from_date)->format('d M Y')); ?></strong>
                has been
                <strong class="<?php echo e($leave->status == 'Approved' ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($leave->status); ?>

                </strong> !!!

                <button type="button" class="btn-close fs-14 mark-read" data-id="<?php echo e($leave->id); ?>"
                    data-bs-dismiss="alert">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="row">
            <div class="col-xl-4 d-flex">
                <div class="card position-relative flex-fill">
                    <div class="card-header bg-dark">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-lg avatar-rounded border border-white border-2 flex-shrink-0 me-2">
                                <?php if(!empty($employee->profile_image)): ?>
                                    <img src="<?php echo e(asset($employee->profile_image)); ?>" alt="Img">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('admin/img/person-dummy.jpg')); ?>" alt="Img">
                                <?php endif; ?>
                            </span>
                            <div>
                                <h5 class="text-white mb-1"><?php echo e($employee->name); ?></h5>
                                <div class="d-flex align-items-center">
                                    <p class="text-white fs-12 mb-0"><?php echo e($employee->designation->name ?? 'N/A'); ?></p>
                                    <span class="mx-1"><i class="ti ti-point-filled text-primary"></i></span>
                                    <p class="fs-12"><?php echo e($employee->department->name ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="d-block mb-1 fs-13">Employee ID</span>
                            <p class="text-gray-9"><?php echo e($employee->employee_code ?? '-'); ?></p>
                        </div>
                        <div class="mb-3">
                            <span class="d-block mb-1 fs-13">Phone Number</span>
                            <p class="text-gray-9"><?php echo e($employee->phone ?? '-'); ?></p>
                        </div>
                        <div class="mb-3">
                            <span class="d-block mb-1 fs-13">Email Address</span>
                            <p class="text-gray-9">
                                <a href="javascript:void(0)" class="text-info d-inline-flex align-items-center copy-email"
                                    data-email="<?php echo e($employee->email); ?>">
                                    <?php echo e($employee->email ?? '-'); ?><i class="ti ti-copy text-dark ms-2"></i>
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <span class="d-block mb-1 fs-13">Report Manager</span>
                            <p class="text-gray-9"><?php echo e($employee->manager->name ?? 'Not Assigned'); ?></p>
                        </div>
                        <div>
                            <span class="d-block mb-1 fs-13">Joined on</span>
                            <p class="text-gray-9">
                                <?php if(optional($employee->profile)->joining_date): ?>
                                    <?php echo e(\Carbon\Carbon::parse($employee->profile->joining_date)->format('d M Y')); ?>

                                <?php elseif($employee->created_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($employee->created_at)->format('d M Y')); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5>Attendance Overview (<?php echo e(now()->year); ?>)</h5>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xs bg-success me-2">
                                            <i class="ti ti-check"></i>
                                        </span>
                                        <span class="fw-medium">Present</span>
                                    </div>
                                    <span class="fw-bold text-success"><?php echo e($presentCount); ?></span>
                                </div>                            
                                <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xs bg-warning me-2">
                                            <i class="ti ti-clock"></i>
                                        </span>
                                        <span class="fw-medium">Approval Pending</span>
                                    </div>
                                    <span class="fw-bold text-warning"><?php echo e($apCount); ?></span>
                                </div>                            
                                <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xs bg-danger me-2">
                                            <i class="ti ti-x"></i>
                                        </span>
                                        <span class="fw-medium">Absent</span>
                                    </div>
                                    <span class="fw-bold text-danger"><?php echo e($absentCount); ?></span>
                                </div>                            
                                <hr class="my-3">                            
                                <p class="fw-semibold text-gray-7 mb-2">Leave Breakdown</p>                            
                                <?php $__currentLoopData = $leaveTypeData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $days): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs bg-info me-2">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                            <span class="fw-medium"><?php echo e($type); ?></span>
                                        </div>
                                        <span class="fw-bold text-info"><?php echo e($days); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                            
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-md-end">
                                    <div id="leaves_chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                            <h5>Leave Details</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <span class="d-block mb-1">Total Leaves</span>
                                    <h4><?php echo e($totalleaves); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <span class="d-block mb-1">Taken</span>
                                    <h4><?php echo e($takenLeaves); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <span class="d-block mb-1">Absent</span>
                                    <h4>0</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <span class="d-block mb-1">Request</span>
                                    <h4><?php echo e($pendingLeaves); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <span class="d-block mb-1">Remaining</span>
                                    <h4><?php echo e($remainingLeaves); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div>
                                    <a href="#" class="btn btn-dark w-100" data-bs-toggle="modal"
                                        data-bs-target="#add_leaves">Apply New Leave</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 d-flex">
                <div class="card flex-fill border-primary attendance-bg">
                    <div class="attendance-card card-body">
                        <div class="mb-4 text-center">
                            <h6 class="fw-medium text-gray-5 mb-1">Attendance</h6>
                            <h4><?php echo e(now()->format('h:i A, d M Y')); ?></h4>
                        </div>
                        <div class="attendance-circle-progress attendance-progress mx-auto mb-3">
                            <span class="progress-left">
                                <span class="progress-bar"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar"></span>
                            </span>
                            <div class="total-work-hours text-center w-100">
                                <span>Total Hours</span>
                                <h6 class="totalHours">00:00:00</h6>
                            </div>
                        </div>
                        <div class="text-center">
                            <h6 class="fw-medium d-flex align-items-center justify-content-center mb-4">
                                <i class="ti ti-fingerprint text-primary me-1"></i>
                                <span id="punchText">
                                    <?php if(!$attendance): ?>
                                        Not Punched In
                                    <?php elseif(!$attendance->punch_out): ?>
                                        Punch In at <?php echo e(\Carbon\Carbon::parse($attendance->punch_in)->format('h:i A')); ?>

                                    <?php else: ?>
                                        Completed
                                    <?php endif; ?>
                                </span>
                            </h6>
                            <button id="punchBtn" class="btn btn-primary w-100"
                                <?php echo e($attendance && $attendance->punch_out ? 'disabled' : ''); ?>

                                data-type="<?php echo e(!$attendance ? 'in' : (!$attendance->punch_out ? 'out' : 'completed')); ?>">

                                <?php if(!$attendance): ?>
                                    Punch In
                                <?php elseif(!$attendance->punch_out): ?>
                                    Punch Out
                                <?php else: ?>
                                    Completed
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="punch_in" value="<?php echo e($attendance->punch_in ?? ''); ?>">
                <input type="hidden" id="punch_out" value="<?php echo e($attendance->punch_out ?? ''); ?>">
            </div>

            <div class="col-xl-8 d-flex">
                <div class="row flex-fill">
                    <div class="col-xl-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="border-bottom mb-3 pb-2">
                                    <span class="avatar avatar-sm bg-dark mb-2">
                                        <i class="ti ti-clock-up"></i>
                                    </span>

                                    <h2 class="mb-2">
                                        <?php echo e($weekHours); ?>

                                        / <span class="fs-20 text-gray-5">45</span>
                                    </h2>

                                    <p class="fw-medium text-truncate">Total Hours Week</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="border-bottom mb-3 pb-2">
                                    <span class="avatar avatar-sm bg-info mb-2">
                                        <i class="ti ti-calendar-up"></i>
                                    </span>
                                    <h2 class="mb-2">
                                        <?php echo e($monthHours); ?>

                                        / <span class="fs-20 text-gray-5">180</span>
                                    </h2>
                                    <p class="fw-medium text-truncate">Total Hours Month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="border-bottom mb-3 pb-2">
                                    <span class="avatar avatar-sm bg-success mb-2">
                                        <i class="ti ti-clock-plus"></i>
                                    </span>
                                    <h2 class="mb-2">
                                        <?php echo e($weekOvertime); ?>

                                    </h2>
                                    <p class="fw-medium text-truncate">Overtime This Week</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="border-bottom mb-3 pb-2">
                                    <span class="avatar avatar-sm bg-pink mb-2">
                                        <i class="ti ti-calendar-star"></i>
                                    </span>
                                    <h2 class="mb-2">
                                        <?php echo e($monthOvertime); ?>

                                    </h2>
                                    <p class="fw-medium text-truncate">Overtime This Month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Leaves -->
    <div class="modal fade" id="add_leaves">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Leave</h4>
                    <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form id="leaveForm">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->leave_type_id); ?>">
                                                <?php echo e($type->leaveType->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <span class="text-danger error-text leave_type_id_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">From <span class="text-danger">*</span></label>
                                    <input type="date" name="from_date" id="from_date" class="form-control">
                                    <span class="text-danger error-text from_date_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">To <span class="text-danger">*</span></label>
                                    <input type="date" name="to_date" id="to_date" class="form-control">
                                    <span class="text-danger error-text to_date_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No of Days <span class="text-danger">*</span></label>
                                    <input type="text" name="days" id="days" class="form-control" readonly>
                                    <span class="text-danger error-text days_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Remaining Days <span class="text-danger">*</span></label>
                                    <input type="text" name="remaining_days" id="remaining_days" class="form-control"
                                        readonly>
                                    <span class="text-danger error-text remaining_days_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control" rows="3"></textarea>
                                    <span class="text-danger error-text reason_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="leaveSubmitBtn" class="btn btn-primary">
                            <span class="btn-text">Add Leaves</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function getCurrentTime() {
            let now = new Date();
            return now.toTimeString().slice(0, 5); // HH:MM
        }

        function formatTime(time) {
            if (!time) return getCurrentTime();

            // handle "YYYY-MM-DD HH:MM:SS"
            if (time.includes(' ')) {
                time = time.split(' ')[1]; // get only time part
            }

            let parts = time.split(':');

            if (parts.length < 2) return getCurrentTime();

            return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
        }

        function calculateWorkedHours(punchIn) {
            let now = new Date();

            // Convert "YYYY-MM-DD HH:MM:SS" → valid JS date
            let formatted = punchIn.replace(' ', 'T');
            let punchDate = new Date(formatted);

            let diff = (now - punchDate) / 1000; // seconds

            let hours = Math.floor(diff / 3600);
            let minutes = Math.floor((diff % 3600) / 60);

            return `${hours}`;
        }

        $('#punchBtn').click(function() {

            let btn = $(this);
            let type = btn.data('type');

            if (type === 'completed') return;

            btn.prop('disabled', true).text('Processing...');

            let currentTime = getCurrentTime();

            if (type === 'in') {
                submitPunch(type, {
                    in_time: currentTime,
                    custom: 0
                });
                return;
            }

            if (type === 'out') {

                // safely pass blade value
                let punchInTime = "<?php echo e($attendance->punch_in ?? ''); ?>";

                let hours = '';

                if (punchInTime) {
                    hours = calculateWorkedHours(punchInTime);
                }

                // ===== NORMAL CHECKOUT =====
                if (hours >= 8) {
                    submitPunch(type, {
                        out_time: currentTime,
                        custom: 0
                    });
                }

                // ===== CUSTOM CHECKOUT =====
                else {
                    console.log("Punch In Time:", punchInTime);
                    Swal.fire({
                        title: `Custom Punch Required`,
                        html: `
                            <div class="container-fluid text-start">
                                <div class="row">                                
    
                                    <div class="col-6">
                                        <label class="form-label">In Time</label>
                                        <input type="time" 
                                               id="swal-in-time" 
                                               class="form-control" 
                                               value="${formatTime(punchInTime)}">
                                    </div>
    
                                    <div class="col-6">
                                        <label class="form-label">Out Time</label>
                                        <input type="time" 
                                               id="swal-out-time" 
                                               class="form-control" 
                                               value="${currentTime}">
                                    </div>
    
                                    <div class="col-12 mt-2">
                                        <label class="form-label">Reason</label>
                                        <textarea id="swal-reason" 
                                                  class="form-control" 
                                                  placeholder="Enter reason"></textarea>
                                    </div>
    
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',

                        preConfirm: () => {

                            let inTime = $('#swal-in-time').val();
                            let outTime = $('#swal-out-time').val();
                            let reason = $('#swal-reason').val();

                            if (!inTime || !outTime) {
                                Swal.showValidationMessage('Both times are required');
                                return false;
                            }

                            if (outTime <= inTime) {
                                Swal.showValidationMessage('Out time must be greater than In time');
                                return false;
                            }

                            if (!reason) {
                                Swal.showValidationMessage('Reason is required');
                                return false;
                            }

                            return {
                                in_time: inTime,
                                out_time: outTime,
                                reason: reason,
                                custom: 1
                            };
                        }

                    }).then((result) => {

                        if (result.isConfirmed) {
                            submitPunch(type, result.value);
                        } else {
                            btn.prop('disabled', false).text('Punch Out');
                        }

                    });
                }
            }
        });

        function submitPunch(type, data) {
            $.ajax({
                url: "<?php echo e(route('employee.attendance.punch')); ?>",
                type: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    type: type,
                    ...data
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message
                    });
                    setTimeout(() => location.reload(), 800);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                    $('#punchBtn').prop('disabled', false).text(type === 'in' ? 'Punch In' : 'Punch Out');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {

            let remainingFromDB = 0;

            function calculateDays() {
                let from = $('#from_date').val();
                let to = $('#to_date').val();

                if (from && to) {
                    let start = new Date(from);
                    let end = new Date(to);

                    if (end < start) {
                        $('#days').val(0);
                        return;
                    }

                    let timeDiff = end - start;
                    let days = (timeDiff / (1000 * 3600 * 24)) + 1;

                    $('#days').val(days);
                    calculateRemaining();
                }
            }

            // ✅ Calculate remaining
            function calculateRemaining() {
                let usedDays = parseInt($('#days').val()) || 0;

                let remaining = remainingFromDB - usedDays;

                if (remaining < 0) {
                    remaining = 0;
                }

                $('#remaining_days').val(remaining);
            }

            // ✅ On Leave Type Change → AJAX
            $('#leave_type_id').change(function() {

                let leaveTypeId = $(this).val();

                if (!leaveTypeId) {
                    $('#remaining_days').val('');
                    $('#days').val('');
                    return;
                }

                $.ajax({
                    url: "<?php echo e(route('employee.leave.details')); ?>",
                    type: "GET",
                    data: {
                        leave_type_id: leaveTypeId
                    },
                    success: function(res) {

                        remainingFromDB = res.remaining_days;

                        $('#remaining_days').val(res.remaining_days);

                        // 🔄 recalculate if dates already selected
                        calculateDays();
                    },
                    error: function() {
                        toastr.error('Failed to fetch leave details');
                    }
                });
            });

            // ✅ On Date Change
            $('#from_date, #to_date').change(function() {
                calculateDays();
            });

        });
    </script>
    <Script>
        $(document).ready(function() {

            $('#leaveForm').submit(function(e) {
                e.preventDefault();

                let btn = $('#leaveSubmitBtn');
                $('.error-text').text('');

                $.ajax({
                    url: "<?php echo e(route('employee.leave.store')); ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    beforeSend: function() {
                        btn.prop('disabled', true);
                        btn.find('.btn-text').html(`
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Saving...
                        `);
                    },
                    success: function(res) {
                        toastr.success('Leave added successfully');
                        $('#leaveForm')[0].reset();
                        $('#leaveModal').modal('hide');
                        location.reload();
                    },
                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '_error').text(value[0]);
                            });
                        } else {
                            toastr.error('Something went wrong');
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        btn.find('.btn-text').html('Add Leaves');
                    }
                });
            });
        });
        $(document).on('click', '.mark-read', function() {

            let id = $(this).data('id');

            $.ajax({
                url: "<?php echo e(route('employee.leave.mark.read')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: id
                },
                success: function() {
                    console.log('Marked as read');
                }
            });
        });
    </Script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\varahi\resources\views/admin/employee_dashboard.blade.php ENDPATH**/ ?>