@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section" id="db_changepw">
        <p class="section_label">ENV File Updated</p>
        <div style="clear: both; display: block; margin-top: 10px !important; text-align: left; margin-left: 5px;">
            <p>Your project's ENV file has been succesfully updated.</p>
        </div>
        <a style="position: absolute; bottom: 10px; right: 10px;" class="btn btn-primary" href="/dashboard/projects/edit/{{ $Project->name }}">Continue</a>
    </div>
</div>
@endsection