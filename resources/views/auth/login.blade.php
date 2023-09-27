@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <!-- <a href="index.html" class="d-inline-block auth-logo">
                            <img src="{{asset('assets/assets/images/logo-light.png')}}" alt="" height="20">
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">

                    <div class="card-body p-4">
                        <!-- <div class="text-center mt-2">
                            <img src="{{asset('assets/images/Group.svg')}}" alt="" width="15px" style="width: 75px; height: 100px;background-image: url('{{ asset('assets/images/lingkaran.svg') }}');background-size: 100% 100%;">
                            <h5 class="text-primary">Selamat Datang</h5>
                           
                        </div> -->
                        <div class="text-center mt-2" style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 75px; height: 100px; background-image: url('{{ asset('assets/images/lingkaran.svg') }}'); background-size: 100% 100%; background-position: center;"></div>
                            <img src="{{ asset('assets/images/Group.svg')}}" alt="" width="25px" style="margin-top: -70px;">
                            <br><br>
                            <h5 class="text-primary welcome">Selamat Datang</h5>
                            <p class="text-muted">Masukan akun anda untuk memulai pekerjaan.</p>
                        </div>

                        <div class="p-2 mt-4">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3 form-icon">
                                    <input type="text" name="email" class="form-control form-control-icon" id="username" placeholder="Username">
                                    <i><img src="{{ asset('assets/images/profile.svg')}}" width="15px"></i>
                                </div>

                                <div class="mb-3 form-icon">
                                    <!-- <div class="float-end">
                                        <a href="auth-pass-reset-basic.html" class="text-muted">Forgot password?</a>
                                    </div> -->
                                    <div class="position-relative auth-pass-inputgroup mb-3" style="display: flex; align-items: center;">
                                        <input type="password" class="form-control pe-5 password-input form-control-icon" placeholder="Password" id="password-input" name="password">
                                        <i><img src="{{ asset('assets/images/lock.svg')}}" width="15px"></i>
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon">
                                            <i><img src="{{ asset('assets/images/hide.svg')}}" width="15px" style="margin-left: -15px;margin-top: 18px;"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div> -->

                                <div class="mt-4">
                                    <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- <div class="mt-4 text-center">
                    <p class="mb-0">Don't have an account ? <a href="auth-signup-basic.html" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                </div> -->

            </div>
        </div>
        <!-- end row -->
    </div>
@endsection
