@extends('layouts.dashboard_layout')

@section('content')
<div class="console">
    <div id="ch" class="commandHolder">
        <table class="table table-console">
            <tbody>
                @foreach($commands as $command)
                    <?php $cutCommand = substr($command, 20); ?>

                    @if($command == $commands[sizeof($commands) - 1])
                    <tr id="lastEntry">
                    @else
                    <tr>
                    @endif
                        @if(substr($cutCommand, 0, 6) === "Input:")
                            <td class="yellow">&gt;{{ $cutCommand }}</td>
                        @elseif(substr($cutCommand, 0, 6) === "Error:")
                            <td class="red">{{ $cutCommand }}</td>
                        @elseif(substr($cutCommand, 0, 7) === "Format:")
                            <td class="blue">{{ $cutCommand }}</td>
                        @elseif(substr($cutCommand, 0, 8) === "Success:")
                            <td class="green">{{ $cutCommand }}</td>
                        @else
                            <td>{{ $cutCommand }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <form action="/dashboard/console" method="post">
        @csrf
        <div class="form-inline">
            <div class="col-12" style="padding: 0px; padding-right: 10px;">
                <i class="fas fa-angle-right input_label"></i>
                <input class="consoleInput form-control" type="text" minlength="3" name="consoleInput" autofocus>
            </div>
            <div style="display: none;" style="padding: 0px; padding-right: 3px;">
                <button class="btn btn-primary" type="submit" name="consoleSubmit"><i class="fas fa-step-forward"></i></button>
            </div>
        </div>
    </form>
</div>
<script>
    var consoleWindow = document.getElementById("ch");
    consoleWindow.scrollTop = consoleWindow.scrollHeight;
</script>
@endsection