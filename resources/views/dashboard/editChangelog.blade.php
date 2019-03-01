@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section">
        <form action="/dashboard/changelogs/edit" method="post" onsubmit="smallLoader()">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Edit Changelog</p>
                    <button onclick="return confirm('Are you sure you want to delete this Changelog?');" class="btn btn-primary btn-delete" type="submit" name="delete_changelog">Delete Changelog</button>
                </div>
                <input type="hidden" name="changelog_id" value="{{ $Changelog->id }}">
                <i class="fas fa-code-branch input_label"></i>
                <input name="changelog_version" type="text" minlength="4" placeholder="Version" value="{{ $Changelog->version }}" required>
                <textarea class="form-control" name="changelog_text" rows="3" placeholder="Changelog (in html)" required>{{ $Changelog->changes }}</textarea>
                <div class="submit-block">
                    @if (Session::has('submitError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error" id="submitError">
                                <strong>{{ Session::get('submitError') }}</strong>
                            </span>
                        </div>
                    @endif
                    <div class="smallLoader"></div>
                    <button class="btn btn-primary" type="submit" name="update_changelog">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if (Session::has('submitError'))
<script>
    document.getElementById("submitError").scrollIntoView();
</script>
@endif
@endsection