@extends('layouts.dashboard_layout')

@section('content')
<div class="console">
    <table class="table table-console">
        <tbody>
            @foreach($commands as $command)
            <tr>
                <td>&gt;{{ $command }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <form action="/dashboard/console" method="post">
        @csrf
        <div class="form-inline">
            <i class="fas fa-angle-right input_label"></i>
            <input class="consoleInput form-control" type="text" name="consoleInput">
            <button class="btn btn-primary" type="submit" name="consoleSubmit">Submit</button>
        </div>
    </form>
</div>
@endsection