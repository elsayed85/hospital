@php
    $configData = appClasses();
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Multi Steps Sign-up - Pages')

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-sticky/jquery-sticky.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('/js/pages-auth-multisteps.js') }}"></script>
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">

            <!-- Left Text -->
            <div class="d-none d-lg-flex col-lg-4 align-items-center justify-content-end p-5 pe-0">
                <div class="w-px-400">
                    <img src="{{ asset('/img/illustrations/create-account-' . $configData['style'] . '.png') }}"
                        class="img-fluid scaleX-n1-rtl" alt="multi-steps" width="600"
                        data-app-light-img="illustrations/create-account-light.png"
                        data-app-dark-img="illustrations/create-account-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->

            <div class="d-flex col-lg-8 authentication-bg align-items-center justify-content-center p-sm-5 p-4">
                <div class="d-flex flex-column w-px-700">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2 mb-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo h3 mb-0 fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">{{ __('login.welcome_message') }} 👋</h4>
                    <p class="mb-4">
                        {{ __('login.welcome_message_description') }}
                    </p>

                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="Post">
                        @csrf
                        <h5 class="my-4">
                            1. {{ __('login.select_your_account_type') }}
                        </h5>
                        <div class="row gy-3">
                            @foreach ($types as $index => $type)
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label custom-option-content" for="{{ $index }}">
                                            <span class="custom-option-body">
                                                <i class='bx bx-briefcase-alt-2'></i>
                                                <span class="custom-option-title"> {{ __($type['name']) }} </span>
                                                @if (isset($type['hint']) && $logged_in_as[$index] == false)
                                                    <small>{{ $type['hint'] }}</small>
                                                @elseif ($logged_in_as[$index] == true)
                                                    <small>{{ __('login.already_logged_in') }}</small>
                                                    <button class="btn btn-danger d-grid w-100"
                                                        id="logout_btn_{{ $index }}">
                                                        logout
                                                    </button>
                                                @endif
                                            </span>
                                            @if ($logged_in_as[$index] == false)
                                                <input name="login_type" class="form-check-input" type="radio"
                                                    value="{{ $index }}" id="{{ $index }}" required
                                                    @if (old('login_type', $route_type) == $index) checked @endif />
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <h5 class="my-4">
                            2. {{ __('login.enter_your_credentials') }}
                        </h5>
                        <div class="mb-3">
                            <label for="login_key" class="form-label">
                                {{ __('login.email_or_username') }}
                            </label>
                            <input type="text" class="form-control" id="login_key" name="login_key"
                                placeholder="{{ __('login.email_or_username_placeholder') }}" autofocus required>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">{{ __('login.password') }}</label>
                                <a href="{{ url('auth/forgot-password-cover') }}">
                                    <small>
                                        {{ __('login.forgot_password') }}
                                    </small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">
                                    {{ __('login.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">
                            {{ __('login.login_button') }}
                        </button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <script>
        // Check selected custom option
        window.Helpers.initCustomOptionCheck();
        @foreach ($types as $index => $type)
            $("button#logout_btn_{{ $index }}").click(function() {
                
            });
        @endforeach
    </script>
@endsection
