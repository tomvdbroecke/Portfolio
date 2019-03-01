@extends('layouts.login_layout')

@section('content')
<div class="centered login_form" data-aos="fade-up">
    <div class="login_header">
        <i class="fas fa-unlock"></i>
        <h2>Reset Password</h2>
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <div>
                <i class="fas fa-user login_input_label"></i>
                <input id="email" placeholder="E-Mail Address" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <div>
                <div class="fa fa-lock login_input_label"></div>
                <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <div>
                <div class="fa fa-lock login_input_label"></div>
                <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group">
            <div style="margin-top: 40px; margin-bottom: -10px;">
                <button type="submit" class="btn btn-primary btn-login">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
