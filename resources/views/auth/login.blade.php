@extends('layouts.login_layout')

@section('content')
<div class="centered login_form" data-aos="fade-up">
                <div class="login_header">
                    <i class="fas fa-user-circle"></i>
                    <h2>LOG IN</h2>
                </div>
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    @if ($errors->has('email'))
                        <span class="help-block{{ $errors->has('email') ? ' has-error' : '' }}">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    @if (Session::has('logoutMessage'))
                        <span class="help-block success-logout">
                            <strong>{{ Session::get('logoutMessage') }}</strong>
                        </span>
                    @endif
                    @if (Session::has('logoutInactive'))
                        <span class="help-block has-error">
                            <strong>{{ Session::get('logoutInactive') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('password'))
                        <span class="help-block{{ $errors->has('password') ? ' has-error' : '' }}">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif

                    <div class="form-group" style="margin-top: 20px;">
                        <div>
                            <i class="fas fa-user login_input_label"></i>
                            <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <div class="fa fa-lock login_input_label"></div>
                            <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="remember_me">
                        <input type="checkbox" id="remember_me" name="remember" value="Remember me" {{ old('remember') ? 'checked' : '' }}></input>
                        <label for="remember_me">Remember me</label>
                    </div>

                    <div class="form-group login_btn_group">
                        <button type="submit" class="btn btn-primary btn-login">
                            Login
                        </button>
                    </div>
                    <div class="form-group forgot_password_group">
                        <a class="forgot_pass" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
@endsection
