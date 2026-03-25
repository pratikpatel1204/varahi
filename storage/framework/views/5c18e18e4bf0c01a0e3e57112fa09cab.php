
<?php $__env->startSection('title', config('app.name') . ' || Assets Master'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h3 class="mb-0">Assets Master</h3>
                <button class="btn btn-primary" id="btnAddAsset">
                    <i class="ti ti-plus"></i> Add Asset
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="AssetTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asset Name</th>
                                <th>Asset Code</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="row_<?php echo e($row->id); ?>">
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($row->name); ?></td>
                                    <td><?php echo e($row->code); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success btnEdit" data-id="<?php echo e($row->id); ?>"
                                            data-name="<?php echo e($row->name); ?>" data-code="<?php echo e($row->code); ?>">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button class="btn-delete btn btn-sm btn-danger" data-id="<?php echo e($row->id); ?>">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="assetModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="assetForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="id">

                    <div class="modal-header">
                        <h5 id="modalTitle">Add Asset</h5>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Asset Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>

                        <div class="mb-3">
                            <label>Asset Code</label>
                            <input type="text" name="code" id="code" class="form-control">
                            <span class="text-danger error-text code_error"></span>
                        </div>

                    </div>

                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-primary" id="btnSave">
                            <span class="btn-text">Save</span>
                            <span class="btn-loader d-none">
                                <i class="fa fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#AssetTable').DataTable();
            let modal = new bootstrap.Modal(document.getElementById('assetModal'));
            $('#btnAddAsset').click(function() {
                $('#assetForm')[0].reset();
                $('#id').val('');
                $('#modalTitle').text('Add Asset');
                $('.error-text').text('');
                modal.show();
            });

            $('.btnEdit').click(function() {
                $('#modalTitle').text('Edit Asset');
                $('#id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));
                modal.show();
            });

            $('#assetForm').submit(function(e) {
                e.preventDefault();

                let id = $('#id').val();
                let url = id ?
                    "<?php echo e(route('admin.assets.update')); ?>" :
                    "<?php echo e(route('admin.assets.store')); ?>";

                // 🔥 Show loader
                $('#btnSave').prop('disabled', true);
                $('#btnSave .btn-text').addClass('d-none');
                $('#btnSave .btn-loader').removeClass('d-none');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $(this).serialize(),

                    success: function(res) {
                        toastr.success(res.message || 'Saved Successfully');
                        modal.hide();
                        setTimeout(() => location.reload(), 700);
                    },

                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            $('.error-text').text('');
                            $.each(errors, function(key, val) {
                                $('.' + key + '_error').text(val[0]);
                            });
                        }
                    },

                    complete: function() {
                        // 🔥 Hide loader (always runs)
                        $('#btnSave').prop('disabled', false);
                        $('#btnSave .btn-text').removeClass('d-none');
                        $('#btnSave .btn-loader').addClass('d-none');
                    }
                });
            });

            // ✅ Delete
            $(document).on('click', '.btn-delete', function() {

                let id = $(this).data('id');

                Swal.fire({
                    title: 'Delete?',
                    text: 'Asset will be removed!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?php echo e(url('admin/assets/delete')); ?>/" + id,
                            type: "DELETE",
                            data: {
                                _token: "<?php echo e(csrf_token()); ?>"
                            },

                            success: function(res) {
                                $('#row_' + id).remove();
                                toastr.success(res.message || 'Deleted Successfully');
                            }
                        });
                    }
                });
            });

        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\Varahi\resources\views/admin/assets/index.blade.php ENDPATH**/ ?>