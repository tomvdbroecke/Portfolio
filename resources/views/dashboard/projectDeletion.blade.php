@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section" id="db_changepw">
        <p class="section_label">Project Deleted</p>
        <div style="clear: both; display: block; margin-top: 10px !important; text-align: left; margin-left: 5px;">
            <p>Your project has been successfully deleted.</p>
        </div>
        <a style="position: absolute; bottom: 10px; right: 10px;" class="btn btn-primary" href="/dashboard/projects">Continue</a>
    </div>
</div>
@endsection