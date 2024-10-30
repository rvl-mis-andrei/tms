@extends('login.app')
@section('title', "Trip Monitoring | Login")
@section('login-form')
<form class="form w-100" id="form-login" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('admin.login') }}">
    <div class="text-center mb-15">
        <h1 class="text-dark fw-bolder mb-3">
            Sign In your Account
        </h1>
        <div class="text-gray-500 fw-semibold fs-6">
            Admin
        </div>
    </div>
    <div class="fv-row mb-8">
        <label for="" class="form-label">Username</label>
        <input type="text" placeholder="Username" name="username" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="fv-row mb-3">
        <label for="" class="form-label">Password</label>
        <input type="password" placeholder="Password" name="password" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
        <div></div>
        <a href="javascript:;" class="link-primary">
            Forgot Password ?
        </a>
    </div>
    <div class="d-grid mb-10">
        <button type="submit" class="btn btn-primary form-btn-submit">
            <span class="indicator-label"> Sign In</span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
@endsection
