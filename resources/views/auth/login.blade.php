@extends('layouts.app')

@section('content')
<style>
    .box-login{
        border-radius: 10px;
        background: #F5F7F9;
        width: 393px;
        height: 55px;
    }

    .eye-toggle {
        position: absolute;
        right: 10px;
        cursor: pointer;
        width: 37px;
        font-size: 15px;
    }

    .button-login{
        height: 40px;
        border-radius: 10px;
        background: var(--1, #194BFB);
        border: none;
    }

</style>
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
                            <h5 class="text-primary welcome">Welcome</h5>
                            <p class="text-muted">
                                <!-- Masukan akun anda untuk memulai pekerjaan. -->
                                Enter your account to start the work
                            </p>
                        </div>

                        <div class="p-2 mt-4">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3 form-icon">
                                    <div class="position-relative auth-pass-inputgroup mb-3" style="display: flex; align-items: center;">
                                        <input type="text" name="email" class="form-control form-control-icon box-login" id="username" placeholder="Username">
                                        <i><img src="{{ asset('assets/images/profile.svg')}}" width="15px"></i>
                                    </div>
                                </div>

                                <div class="mb-3 form-icon">
                                    <div class="position-relative auth-pass-inputgroup mb-3" style="display: flex; align-items: center;">
                                        <input type="password" id="password" name="password" placeholder="Password" class="form-control form-control-icon box-login">
                                        <i><img src="{{ asset('assets/images/lock.svg')}}" width="15px"></i>
                                        <span toggle="#password" class="eye-toggle" onclick="togglePasswordVisibility()">
                                            <i class="mdi mdi-eye-off-outline" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                @if ($errors->any())
                                    <div style="color:red">
                                        <center>
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </center>
                                    </div>
                                @endif

                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div> -->

                                <div class="mt-4">
                                    <button class="btn btn-primary w-100 button-login" type="submit">Log In</button>
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

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.querySelector("#password");
            const eyeToggle = document.querySelector(".eye-toggle");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeToggle.innerHTML = '<i class="mdi mdi-eye-outline" aria-hidden="true"></i>';
            } else {
                passwordInput.type = "password";
                eyeToggle.innerHTML = '<i class="mdi mdi-eye-off-outline" aria-hidden="true"></i>';
            }
        }
    </script>
@endsection
