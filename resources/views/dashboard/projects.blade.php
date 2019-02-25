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
            </tr>
        </thead>
        <tbody>
        @if(sizeof($Projects) > 0)
            @foreach($Projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->version }}</td>
                    <td>{{ $project->status }}</td>
                    <td><a class="btn-project" href="/dashboard/projects/{{ $project->name }}"><i class="fas fa-eye"></i></a></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">There are no projects for you to view.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
