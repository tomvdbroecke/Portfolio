@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section">
        <form action="/dashboard/projects/edit" method="post" enctype="multipart/form-data" onsubmit="smallLoader()">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Edit Project</p>
                    @if($Project->folder_structure == "laravel")
                    <a href="/dashboard/projects/editEnv/{{ $Project->name }}" class="btn btn-env">ENV</a>
                    @endif
                    <button onclick="return confirm('Are you sure you want to delete this Project?');" class="btn btn-primary btn-delete" type="submit" name="delete_project">Delete Project</button>
                </div>
                <input type="hidden" name="project_id" value="{{ $Project->id }}">
                <div class="input_block">
                    <i class="fas fa-font input_label"></i>
                    <input name="project_name" type="text" minlength="4" placeholder="Project Name" value="{{ $Project->name }}" disabled>
                    <i class="fas fa-code-branch input_label"></i>
                    <input name="project_version" type="text" minlength="4" placeholder="Version" value="{{ $Project->version }}" required>
                    <i class="fas fa-question-circle input_label"></i>
                    <input name="project_status" type="text" minlength="4" placeholder="Status" value="{{ $Project->status }}" required>
                    <select class="form-control" name="project_folder_structure" disabled>
                        <option value="" disabled selected>{{ ucwords($Project->folder_structure) }}</option>
                    </select>
                    <label class="project_files_label">Project Files Upload</label>
                    <input name="project_data" type="file" class="form-control-file" accept="file_extension">
                    <textarea class="form-control" name="project_additional_info" rows="3" placeholder="Additional Info">{{ $Project->additional_info }}</textarea>
                </div>
                <div class="submit-block">
                    @if (Session::has('submitError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error" id="submitError">
                                <strong>{{ Session::get('submitError') }}</strong>
                            </span>
                        </div>
                    @endif
                    <div class="smallLoader"></div>
                    <button class="btn btn-primary" type="submit" name="update_project">Update</button>
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