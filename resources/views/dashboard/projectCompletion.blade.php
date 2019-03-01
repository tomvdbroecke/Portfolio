@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section" id="db_changepw">
        <p class="section_label">Project Added</p>
        <div class="newP_info">
            <p>Project ID: {{ $Project->id }}</p>
            <p>Secret Key: {{ $Project->secretKey }}</p>
            <p>Access User: {{ $Project->accessUser }}</p>
            <p>Access Pass: {{ $Project->accessPass }}</p>
        </div>
        <a style="position: absolute; bottom: 10px; right: 10px;" class="btn btn-primary" href="/dashboard/projects">Continue</a>
    </div>
</div>
@endsection