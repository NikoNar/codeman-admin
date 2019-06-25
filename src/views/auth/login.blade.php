<!DOCTYPE html>
<html lang="en">
<head>
    <title>CODEMAN | ADMIN LOGIN </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/animate/animate.css') }}">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-panel/login/css/main.css') }}">
<!--===============================================================================================-->
</head>
<body style="background-color: #666666;">
    <div class="show-on-mobile" style="text-align: center; padding-bottom: 40px; background: #02a0de; padding-top: 40px; ">
        <img src="{{ asset('admin-panel/login/images/codeman-logo-white.svg') }}" alt="CODEMAN LOGO" style="width: 70%; border: 4px solid #fff; padding:15px;text-align: center;">
    </div>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form action="{{ route('postLogin') }}" class="login100-form validate-form" method="POST">
                    @csrf
                    
                    <span class="login100-form-title p-b-43">
                        LOGIN TO CONTINUE
                    </span>
                    @if(isset($errors))
                        @if ($errors->has('email'))
                            <span class="help-block" style="color: red; text-align: center; font-size: 12px; width: 100%; display: block; margin-top: -20px; padding-bottom: 20px;">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    @endif
                    <div class="form-group has-feedback wrap-input100 validate-input @if(isset($errors)){{ $errors->has('email') ? ' has-error' : '' }}@endif " data-validate = "Valid email is required">
                        <input class="input100 {!! request()->email ?? ' has-val' !!}" type="email" name="email" value="{{ old('email') }}">
                        <span class="focus-input100"></span>
                        <span class="label-input100">Email</span>
                    </div>
                    
                    
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100 {!! request()->password ?? ' has-val' !!}" type="password" name="password">
                        <span class="focus-input100"></span>
                        <span class="label-input100">Password</span>
                    </div>

                    <div class="flex-sb-m w-full p-t-3 p-b-32">
                        <div class="contact100-form-checkbox">
                            <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} >
                            <label class="label-checkbox100" for="ckb1">
                                Remember me
                            </label>

                        </div>

                        <div>
                            <a href="{{ url('admin/password/email') }}" class="txt1">
                                Forgot Password?
                            </a>
                        </div>
                    </div>
            

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>

                    <div style="position: absolute; bottom: 0; right: 0;padding: 10px;">
                        <p>© Copyright 2017-{{date('Y')}} CODEMAN. All rights reserved. </p>
                    </div>
                    
                    

                    
                </form>

                <div class="login100-more" style="background-color: #02a0de" style="position: relative;">
                    <img src="{{ asset('admin-panel/login/images/codeman-logo-white.svg') }}" alt="CODEMAN LOGO" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 70%; border: 7px solid #fff; padding: 15px;">
                </div>
            </div>
        </div>
    </div>
    
    

    
    
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('admin-panel/login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('admin-panel/login/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('admin-panel/login/js/main.js') }}"></script>

</body>
</html>