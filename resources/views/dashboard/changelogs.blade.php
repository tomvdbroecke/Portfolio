@extends('layouts.dashboard_layout')

@section('content')
<div class="projectlist">
    <table class="table">
        <thead>
            <tr>
                <th>Project</th>
                <th>Version</th>
                <th>Created</th>
                <th><a class="btn-project" href="/dashboard/changelogs/add"><i class="fas fa-plus"></i></i></a></th>
            </tr>
        </thead>
        <tbody>
        @if(sizeof($Changelogs) > 0)
            @foreach($Changelogs as $Changelog)
                <tr>
                    <td>{{ $Changelog->ownerProject()->name }}</td>
                    <td>{{ $Changelog->version }}</td>
                    <td>{{ date("d-m-Y", strtotime($Changelog->created_at)) }}</td>
                    <td><a class="btn-project" href="/dashboard/changelogs/edit/{{ $Changelog->id }}"><i class="fas fa-pencil-alt"></i></a></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">There are no changelogs for you to view.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
