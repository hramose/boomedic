@extends('adminlte::master')
<head>
    <meta name="google-signin-client_id" content="627103508601-mstgbse0thdiv2qcn2dop6pn0u28gc31.apps.googleusercontent.com">
</head>

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop





@section('body_class', 'login-page')





@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'medicalconsultations')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <!-- casilla de recordar usuario. -->
                        &nbsp;
                        <!-- <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                            </label>
                        </div> -->
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-secondary btn-block btn-flat">{{ trans('adminlte::adminlte.sign_in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <div class="auth-links">
            <div class="row">
                <div class="col-xs-6">
                    <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" class="text-center" >
                        {{ trans('adminlte::adminlte.i_forgot_my_password') }}
                    </a>
                </div>
                <div class="col-xs-6">
                    @if (config('adminlte.register_url', 'register'))
                    <a href="{{ url(config('adminlte.register_url', 'register')) }}" class="text-center">
                        {{ trans('adminlte::adminlte.register_a_new_membership') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.login-box-body -->
        <div class="row">
            <div class="col-xs-6">
                <div class="fb-login-button" data-size="medium" data-button-type="continue_with" 
                    data-scope="public_profile,email" onlogin="checkLoginState();"></div>
            </div>
            <div class="col-xs-6">
                <div class="g-signin2" data-width="165" data-height="27" data-clientid="627103508601-mstgbse0thdiv2qcn2dop6pn0u28gc31.apps.googleusercontent.com  "data-onsuccess="onSignInG"></div>
            </div>
        </div>
        <div class="row">
            <div>
                <div align="center"><script type="in/Login" data-width="165" data-height="27" ></script></div>
            </div>
        </div>
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
 