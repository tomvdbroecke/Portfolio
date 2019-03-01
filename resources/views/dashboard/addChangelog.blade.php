@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section">
        <form action="/dashboard/changelogs/add" method="post" onsubmit="smallLoader()">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Add Changelog</p>
                </div>
                <select style="margin-bottom: 5px;" class="form-control" name="project_selection" required>
                    <option value="" disabled selected>Project</option>
                    @foreach($Projects as $Project)
                    <option value="{{ $Project->id }}">{{ $Project->name." | ".$Project->version }}</option>
                    @endforeach
                </select>
                <i class="fas fa-code-branch input_label"></i>
                <input name="project_version" type="text" minlength="4" placeholder="Version" required>
                <textarea class="form-control" name="changelog_text" rows="3" placeholder="Changelog (in html)" required></textarea>
                <div class="submit-block">
                    @if (Session::has('submitError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error" id="submitError">
                                <strong>{{ Session::get('submitError') }}</strong>
                            </span>
                        </div>
                    @endif
                    <div class="smallLoader"></div>
                    <button class="btn btn-primary" type="submit" name="add_changelog">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection