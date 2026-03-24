<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') . ' || Email Verification' }}</title>
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
                            class="login-background position-relative d-lg-flex align-items-center justify-content-center d-none flex-wrap vh-100">
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
                                <form id="otpForm" class="vh-100 digit-group">
                                    <div class="vh-100 d-flex flex-column justify-content-between p-4 pb-0">
                                        <div class=" mx-auto mb-5 text-center">
                                            <img src="{{ asset('admin/img/logo.png') }}" class="img-fluid"
                                                alt="Logo" style="height: 150px;">
                                        </div>
                                        <div class="">
                                            <div class="text-center mb-3">
                                                <h2 class="mb-2">Email OTP Verification</h2>
                                                <p class="mb-0">Please enter the OTP received to confirm your account
                                                    ownership. A code has been send to
                                                    {{ substr($user->email, 0, 5) . '****' . strstr($user->email, '@') }}
                                                </p>
                                            </div>
                                            <div class="text-center otp-input">
                                                <div class="d-flex align-items-center mb-3 otp-group">
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                                           id="digit-1" data-next="digit-2" maxlength="1">
                                                
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                                           id="digit-2" data-next="digit-3" data-previous="digit-1" maxlength="1">
                                                
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                                           id="digit-3" data-next="digit-4" data-previous="digit-2" maxlength="1">
                                                
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                                           id="digit-4" data-next="digit-5" data-previous="digit-3" maxlength="1">
                                                
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                                           id="digit-5" data-next="digit-6" data-previous="digit-4" maxlength="1">
                                                
                                                    <input type="text" class="otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold"
                                                           id="digit-6" data-previous="digit-5" maxlength="1">
                                                </div>                                                
                                                <div>
                                                    <div class="mb-3 d-flex justify-content-center">
                                                        <p class="text-gray-9">Didn't get the OTP? <a
                                                                href="javascript:void(0);" id="resendOtp"
                                                                class="text-primary">Resend OTP</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-100">Verify &
                                                    Proceed</button>
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
            $('.otp-input-box').on('keyup', function(e) {
                let input = $(this);
                let value = input.val();

                if (value.length === 1) {
                    // Move to next input
                    let nextId = input.data('next');
                    if (nextId) {
                        $('#' + nextId).focus();
                    }
                }

                // Move back on backspace
                if (e.key === "Backspace") {
                    let prevId = input.data('previous');
                    if (prevId) {
                        $('#' + prevId).focus();
                    }
                }
            });

            // OTP Form Submission via AJAX
            $('#otpForm').on('submit', function(e) {
                e.preventDefault();

                // Collect OTP digits
                let otp = '';
                $('.digit-group input').each(function() {
                    otp += $(this).val();
                });

                let $btn = $('#verifyBtn');
                let originalText = $btn.html();
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Verifying...');

                $.ajax({
                    url: "{{ route('admin.verify.otp') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: '{{ $user->email }}',
                        otp: otp
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            toastr.success(response.message);
                            // Redirect or do next step
                            window.location.href = response.redirect_url;
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalText);
                        toastr.error('Something went wrong. Please try again.');
                    }
                });
            });

            // Resend OTP via AJAX
            $('#resendOtp').on('click', function() {
                let $link = $(this);
                $link.prop('disabled', true).text('Sending...');

                $.ajax({
                    url: "{{ route('admin.resend.otp') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: '{{ $user->email }}'
                    },
                    success: function(response) {
                        $link.prop('disabled', false).text('Resend OTP');

                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $link.prop('disabled', false).text('Resend OTP');
                        toastr.error('Failed to resend OTP. Please try again.');
                    }
                });
            });

        });
    </script>
</body>

</html>
