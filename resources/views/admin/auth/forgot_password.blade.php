<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') . ' || Forgot Password' }}</title>
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
                        <div class="login-background position-relative d-lg-flex align-items-center justify-content-center d-none flex-wrap vh-100">
                            <div class="bg-overlay-img">
                                <img src="{{ asset('admin/img/bg/bg-01.png') }}" class="bg-1" alt="Img">
                                <img src="{{ asset('admin/img/bg/bg-02.png') }}" class="bg-2" alt="Img">
                                <img src="{{ asset('admin/img/bg/bg-03.png') }}" class="bg-3" alt="Img">
                            </div>
                            <div class="authentication-card w-100">
                                <div class="authen-overlay-item border w-100">
                                    <h1 class="text-white display-1">Empowering people <br> through seamless HR <br>
                                        management.</h1>
                                    <div class="my-4 mx-auto authen-overlay-img">
                                        <img src="{{ asset('admin/img/bg/authentication-bg-01.png') }}" alt="Img">
                                    </div>
                                    <div>
                                        <p class="text-white fs-20 fw-semibold text-center">Efficiently manage your
                                            workforce, streamline <br> operations effortlessly.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap">
                            <div class="col-md-7 mx-auto vh-100">
                                <form id="forgotPasswordForm" class="vh-100">
                                    <div class="vh-100 d-flex flex-column justify-content-between p-4 pb-0">
                                        <div class=" mx-auto mb-5 text-center">
                                            <img src="{{ asset('admin/img/logo.png') }}" class="img-fluid" style="height: 150px;"
                                                alt="Logo">
                                        </div>
                                        <div class="">
                                            <div class="text-center mb-3">
                                                <h2 class="mb-2">Forgot Password?</h2>
                                                <p class="mb-0">If you forgot your password, well, then we'll email
                                                    you instructions to reset your password.</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <div class="input-group">
                                                    <input type="email" id="email" name="email" value=""
                                                        class="form-control border-end-0">
                                                    <span class="input-group-text border-start-0">
                                                        <i class="ti ti-mail"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                                            </div>
                                            <div class="text-center">
                                                <h6 class="fw-normal text-dark mb-0">Return to
                                                    <a href="{{ route('admin.login') }}" class="hover-a">Sign In</a>
                                                </h6>
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
    <script src="{{ asset('admin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/js/feather.min.js') }}"></script>
    <script src="{{ asset('admin/js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#forgotPasswordForm').on('submit', function(e) {
                e.preventDefault();

                let email = $('#email').val();
                if (!email) {
                    toastr.error('Please enter an email address.');
                    return;
                }

                let $btn = $(this).find('button[type="submit"]');
                let originalText = $btn.text();

                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Checking...');

                $.ajax({
                    url: "{{ route('admin.forgot.password.check') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: email
                    },
                    success: function(res) {
                        if (res.exists) {
                            toastr.success('Email exists! Redirecting to OTP verification...');
                            window.location.href = "{{ route('admin.otp.verification', ':id') }}".replace(':id', res.user_id);
                        } else {
                            toastr.error('Email does not exist in our records.');
                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong. Please try again.');
                        console.log(err.responseText);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
</body>

</html>
