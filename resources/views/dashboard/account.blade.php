@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section" id="db_changepw">
        <form action="/dashboard/account" method="post">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Change Password</p>
                </div>
                <i class="fas fa-lock input_label"></i>
                <input name="current_password" type="password" placeholder="Current Password" required>
                <i class="fas fa-unlock input_label"></i>
                <input name="new_password" type="password" placeholder="New Password" minlength="4" required>
                <i class="fas fa-unlock input_label"></i>
                <input name="new_password_repeat" type="password" placeholder="Repeat New Password" minlength="4" required>
                <div class="submit-block">
                    @if (Session::has('passwordError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error">
                                <strong>{{ Session::get('passwordError') }}</strong>
                            </span>
                        </div>
                    @endif
                    @if (Session::has('passwordSuccess'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block success">
                                <strong>{{ Session::get('passwordSuccess') }}</strong>
                            </span>
                        </div>
                    @endif
                    <button class="btn btn-primary" type="submit" name="change_password">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="db_section" id="db_changeemail">
        <form action="/dashboard/account" method="post">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Change Email</p>
                </div>
                <i class="fas fa-envelope input_label"></i>
                <input name="current_email" type="email" value="{{ $User->email }}" disabled>
                <i class="fas fa-envelope-open input_label"></i>
                <input name="new_email" type="email" placeholder="New Email Password" required>
                <i class="fas fa-envelope-open input_label"></i>
                <input name="new_email_repeat" type="email" placeholder="Repeat Email Password" required>
                <div class="submit-block">
                    @if (Session::has('emailError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error">
                                <strong>{{ Session::get('emailError') }}</strong>
                            </span>
                        </div>
                    @endif
                    <button class="btn btn-primary" type="submit" name="change_email">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if (Session::has('emailError'))
<script>
    document.getElementById("db_changeemail").scrollIntoView();
</script>
@endif
@endsection
