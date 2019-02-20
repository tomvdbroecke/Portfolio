@extends('layouts.login_layout')

@section('content')
<div class="centered login_form" data-aos="fade-up">
    <div class="login_header">
        <i class="fas fa-unlock"></i>
        <h2>Reset Password</h2>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group" style="margin-top: 20px;">
            <div>
                <i class="fas fa-user login_input_label"></i>
                <input id="email" placeholder="E-Mail Address" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="submit_btn_group">
            <div>
                <button type="submit" class="btn btn-primary btn-login">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
