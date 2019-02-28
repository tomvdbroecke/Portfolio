@extends('layouts.dashboard_layout')

@section('content')
<div class="projectlist">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Version</th>
                <th>Status</th>
                <th></th>
                @if($User->IsAdmin())
                <th><a class="btn-project" href="/dashboard/projects/add"><i class="fas fa-plus"></i></i></a></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @if(sizeof($Projects) > 0)
            @foreach($Projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->version }}</td>
                    <td>{{ $project->status }}</td>
                    <td><a class="btn-project" href="/dashboard/projects/view/{{ $project->name }}"><i class="fas fa-eye"></i></a></td>
                    @if($User->IsAdmin())
                    <td><a class="btn-project" href="/dashboard/projects/edit/{{ $project->name }}"><i class="fas fa-pencil-alt"></i></a></td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                @if($User->IsAdmin())
                <td colspan="5">There are no projects for you to view.</td>
                @else
                <td colspan="4">There are no projects for you to view.</td>
                @endif
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
