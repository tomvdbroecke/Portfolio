@extends('layouts.dashboard_layout')

@section('content')
<div class="account_content">
    <div class="db_section">
        <form action="/dashboard/projects/add" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="label_head">
                    <p class="section_label">Add Project</p>
                </div>
                <i class="fas fa-font input_label"></i>
                <input name="project_name" type="text" minlength="4" placeholder="Project Name" required>
                <i class="fas fa-code-branch input_label"></i>
                <input name="project_version" type="text" minlength="4" placeholder="Version" required>
                <i class="fas fa-question-circle input_label"></i>
                <input name="project_status" type="text" minlength="4" placeholder="Status" required>
                <select class="form-control" name="project_folder_structure" required>
                    <option value="" disabled selected>Folder Structure</option>
                    <option value="standard">Standard</option>
                    <option value="laravel">Laravel</option>
                </select>
                <label class="project_files_label">Project Files Upload</label>
                <input name="project_data" type="file" class="form-control-file" accept="file_extension" required>
                <textarea class="form-control" name="project_additional_info" rows="3" placeholder="Additional Info"></textarea>
                <div class="submit-block">
                    @if (Session::has('submitError'))
                        <div class="col-8 col-sm-9">
                            <span class="help-block error" id="submitError">
                                <strong>{{ Session::get('submitError') }}</strong>
                            </span>
                        </div>
                    @endif
                    <button class="btn btn-primary" type="submit" name="add_project">Create</button>
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