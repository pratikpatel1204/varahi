<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') . ' || Reset Password' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/img/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icons/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/tabler-icons/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="bg-white">
    <div id="global-loader" style="display: none;">
        <div class="page-loader"></div>
    </div>
    <div class="main-wrapper">
        <div class="container-fuild">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100">
                <div class="row">
                    <div class="col-lg-5">
                        <div
                            class="d-lg-flex align-items-center justify-content-center d-none flex-wrap vh-100 bg-primary-transparent">
                            <div>
                                <img src="{{ asset('admin/img/bg/authentication-bg-06.svg') }}" alt="Img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap ">
                            <div class="col-md-7 mx-auto vh-100">
                                <form id="resetPasswordForm" class="vh-100">
                                    <div class="vh-100 d-flex flex-column justify-content-between p-4 pb-0">
                                        <div class=" mx-auto mb-5 text-center">
                                            <img src="{{ asset('admin/img/logo.png') }}" class="img-fluid"
                                                alt="Logo" style="height: 150px;">
                                        </div>
                                        <div class="">
                                            <div class="text-center mb-3">
                                                <h2 class="mb-2">Reset Password</h2>
                                                <p class="mb-0">Your new password must be different from previous used
                                                    passwords.</p>
                                            </div>
                                            <div>
                                                <div class="input-block mb-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Password</label>
                                                        <div class="pass-group" id="passwordInput">
                                                            <input type="password" id="password" class="form-control">
                                                            <span class="ti ti-eye-off toggle-password"></span>
                                                        </div>
                                                        <div id="passwordInfo" class="mt-2"></div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Confirm Password</label>
                                                    <div class="pass-group">
                                                        <input type="password" id="confirm_password" class="form-control">
                                                        <span class="ti ti-eye-off toggle-passwords"></span>
                                                    </div>
                                                    <div id="matchMessage" class="mt-2"></div>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit" id="resetBtn"
                                                        class="btn btn-primary w-100">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 pb-4 text-center">
                                            <p class="mb-0 text-gray-9">Copyright &copy; {{ date('Y') }} - Varahi</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('admin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/js/feather.min.js') }}"></script>
    <script src="{{ asset('admin/js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {

            // 🔹 CONFIRM PASSWORD MATCH CHECK
            $("#password, #confirm_password").on("keyup", function() {

                let pass = $("#password").val();
                let cpass = $("#confirm_password").val();

                if (pass.length < 1 || cpass.length < 1) {
                    $("#matchMessage").html("");
                    return;
                }

                if (pass === cpass) {
                    $("#matchMessage").html("<span class='text-success'>✔ Passwords match</span>");
                } else {
                    $("#matchMessage").html("<span class='text-danger'>✖ Passwords do not match</span>");
                }
            });

            // 🔹 PASSWORD STRENGTH CHECK
            $("#password").on("keyup", function() {
                let pass = $(this).val();
                let msg = "";

                if (pass.length < 6) {
                    msg = "<span class='text-danger'>Too weak</span>";
                } else if (pass.match(/[A-Z]/) && pass.match(/[0-9]/) && pass.match(/[^a-zA-Z0-9]/)) {
                    msg = "<span class='text-success'>Strong password</span>";
                } else {
                    msg = "<span class='text-warning'>Medium strength</span>";
                }

                $("#passwordInfo").html(msg);
            });

            // 🔹 SHOW / HIDE PASSWORD
            // Show / hide main password
            $(".toggle-password").on("click", function() {
                let input = $("#password");
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                    $(this).removeClass("ti-eye-off").addClass("ti-eye");
                } else {
                    input.attr("type", "password");
                    $(this).removeClass("ti-eye").addClass("ti-eye-off");
                }
            });

            // Show / hide confirm password
            $(".toggle-passwords").on("click", function() {
                let input = $("#confirm_password");

                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    $(this).removeClass("ti-eye-off").addClass("ti-eye");
                } else {
                    input.attr("type", "password");
                    $(this).removeClass("ti-eye").addClass("ti-eye-off");
                }
            });


            // 🔹 AJAX FORM SUBMIT + BUTTON LOADER
            $("#resetPasswordForm").on("submit", function(e) {
                e.preventDefault();

                let password = $("#password").val();
                let confirm_password = $("#confirm_password").val();

                if (password !== confirm_password) {
                    toastr.error("Passwords do not match!");
                    return;
                }

                // Loader
                let btn = $("#resetBtn");
                let originalText = btn.html();
                btn.prop("disabled", true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Processing...');

                $.ajax({
                    url: "{{ route('admin.reset.password.submit') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        password: password,
                        confirm_password: confirm_password,
                        email: '{{ $user->email }}',
                    },
                    success: function(response) {
                        btn.prop("disabled", false).html(originalText);

                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = response.redirect_url;
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        btn.prop("disabled", false).html(originalText);
                        toastr.error("Something went wrong. Try again.");
                    }
                });

            });

        });
    </script>
</body>

</html>
