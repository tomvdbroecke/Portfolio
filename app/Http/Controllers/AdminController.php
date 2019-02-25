<?php

namespace App\Http\Controllers;

use Auth;
use App;
use Artisan;

use Illuminate\Http\Request;
use Illuminate\Console\Application;
use Symfony\Component\Console\Output\StreamOutput;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isVerified');
        $this->middleware('active');
        $this->middleware('isAdmin');
    }

    // Console
    public function viewConsole() {
        $commands = array();
        $file = file(storage_path('consoleLog.txt'));
        for ($i = max(0, count($file)-11); $i < count($file); $i++) {
            array_push($commands, $file[$i]);
        }

        return view('dashboard.console', [
            'User' => Auth::user(),
            'activePage' => 'console',
            'commands' => $commands
        ]);
    }

    // Enter console
    public function enterConsole(Request $request) {
        $stream = fopen(storage_path('consoleLog.txt'), 'a');
        $tempFile = fopen(storage_path('tempConsoleLog.txt'), 'w');
        Artisan::call($request->input('consoleInput'), array(), new StreamOutput($tempFile));
        fclose($tempFile);
        $lines = file(storage_path('tempConsoleLog.txt'), FILE_IGNORE_NEW_LINES);
        foreach($lines as $line) {
            fwrite($stream, $line . "\n");
        }
        fclose($stream);
        unlink(storage_path('tempConsoleLog.txt'));

        return redirect('/dashboard/console');
    }
}
