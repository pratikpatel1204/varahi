@extends('admin.layout.main-layout')

@section('title', config('app.name') . ' || Profile')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Profile</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard')}}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="border-bottom mb-3 pb-3">
                <h4>Profile</h4>
            </div>

            <form id="adminProfileForm" enctype="multipart/form-data">
                @csrf
                @method('POST')            

                {{-- Basic Information --}}
                <div class="border-bottom mb-3 pb-3">
                    <h6 class="mb-3">Basic Information</h6>

                    <div class="row mb-3">
                        <div class="col-md-12 d-flex align-items-center bg-light rounded p-3">
                            <div class="avatar avatar-xxl rounded-circle border border-dashed me-3 text-dark">
                                @if(auth()->user()->profile_image)
                                    <img src="{{ asset(auth()->user()->profile_image) }}" class="rounded-circle" width="100">
                                @else
                                    <i class="ti ti-user fs-16"></i>
                                @endif
                            </div>
                            <div>
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="photo" class="form-control">
                                <small class="text-muted">Recommended size: 150x150</small><br>
                                @if(auth()->user()->role)
                                    <span class="badge bg-primary text-uppercase">
                                        {{ auth()->user()->role }}
                                    </span>
                                @endif
                            </div>                                                        
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                        </div>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="border-bottom mb-3 pb-3">
                    <h6 class="mb-3">Change Password</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Save --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <span class="btn-text">Save</span>
                        <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                    </button>                    
                </div>

            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    
        $("#adminProfileForm").on("submit", function (e) {
            e.preventDefault();
    
            $(".error-text").remove(); // Remove old errors
            $("input, select").removeClass("is-invalid");
    
            let formData = new FormData(this);
    
            // Button loading
            $("#saveBtn .btn-text").addClass("d-none");
            $("#saveBtn .btn-spinner").removeClass("d-none");
            $("#saveBtn").prop("disabled", true);
    
            $.ajax({
                url: "{{ route('admin.profile.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
    
                success: function(response) {
                    if (response.status === true) {
                        toastr.success(response.message);                        
                    } else {
                        toastr.error(response.message);
                    }
                },
    
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            let input = $('[name="'+ key +'"]');
                            input.addClass("is-invalid");
                            input.after('<span class="text-danger error-text">' + value[0] + '</span>');
                        });
                    } else {
                        toastr.error("Something went wrong!");
                    }
                },
    
                complete: function() {
                    $("#saveBtn .btn-text").removeClass("d-none");
                    $("#saveBtn .btn-spinner").addClass("d-none");
                    $("#saveBtn").prop("disabled", false);
                }
            });
    
        });
    
    });
    </script>    
@endsection
