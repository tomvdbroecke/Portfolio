<?php

namespace App\Http\Controllers;

use Auth;
use App;
use Artisan;
use Config;
use DB;
use Validator;
use App\User;
use Hash;

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
        for ($i = max(0, count($file)-50); $i < count($file); $i++) {
            array_push($commands, $file[$i]);
        }

        return view('dashboard.console', [
            'User' => Auth::user(),
            'activePage' => 'console',
            'commands' => $commands
        ]);
    }

    // Console log
    public function viewConsoleLog() {
        return view('dashboard.logView');
    }

    // Enter console
    public function enterConsole(Request $request) {
        $input = $request->input('consoleInput');
        $cutInput = explode(" ", $input);
        $command = $cutInput[0];

        $timestamp = date("d-m-Y H:i:s");

        if (Self::isArtisan($command)) {
            $results = Self::executeCustomCommand($command, $cutInput);

            if (substr($results[0], 0, 5) === "rdir:") {
                return redirect(substr($results[0], 5));
            }

            $stream = fopen(storage_path('consoleLog.txt'), 'a');
            fwrite($stream, $timestamp . " Input: ". $input . "\n");
            foreach($results as $result) {
                fwrite($stream, $timestamp . " " . $result ."\n");
            }
            fclose($stream);
        } else {
            // Check if command is whitelisted
            if (in_array($command, Config::get('commands.whitelist'))) {
                $commandArray = Self::returnValidCommandArray($cutInput);

                $stream = fopen(storage_path('consoleLog.txt'), 'a');
                $tempFile = fopen(storage_path('tempConsoleLog.txt'), 'w');
                Artisan::call($command, $commandArray, new StreamOutput($tempFile));
                fclose($tempFile);
                $lines = file(storage_path('tempConsoleLog.txt'), FILE_IGNORE_NEW_LINES);
                fwrite($stream, $timestamp . " Input: ". $input . "\n");
                foreach($lines as $line) {
                    fwrite($stream, $timestamp . " " . $line . "\n");
                }
                fclose($stream);
                unlink(storage_path('tempConsoleLog.txt'));
            } else {
                $stream = fopen(storage_path('consoleLog.txt'), 'a');
                fwrite($stream, $timestamp . " Input: ". $input . "\n");
                fwrite($stream, $timestamp . " Error: Artisan command not whitelisted!\n");
                fclose($stream);
            }
        }

        return redirect('/dashboard/console');
    }

    // Check if command is custom
    public function isArtisan($command) {
        if (in_array($command, Config::get('commands.whitelist'))) {
            return false;
        } else {
            return true;
        }
    }

    // Return valid command array
    public function returnValidCommandArray($cutInput) {
        $commandArray = array();

        // MAKE:CONTROLLER
        if ($cutInput[0] == "make:controller") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($i == 1) {
                    $commandArray["name"] = $cutInput[$i];
                }
            }
        }

        // MAKE:MODEL
        if ($cutInput[0] == "make:model") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($i == 1) {
                    $commandArray["name"] = $cutInput[$i];
                }
                if ($cutInput[$i] == "--migration" || $cutInput[$i] == "-m") {
                    $commandArray["--migration"] = true;
                }
                if ($cutInput[$i] == "--controller"  || $cutInput[$i] == "-c") {
                    $commandArray["--controller"] = true;
                }
                if ($cutInput[$i] == "--resource"  || $cutInput[$i] == "-r") {
                    $commandArray["--resource"] = true;
                }
            }
        }

        // ANY MIGRATE
        if ($cutInput[0] == "migrate" || $cutInput[0] == "migrate:fresh" || $cutInput[0] == "migrate:install" || $cutInput[0] == "migrate:refresh" || $cutInput[0] == "migrate:reset" || $cutInput[0] == "migrate:rollback" || $cutInput[0] == "migrate:status") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($cutInput[$i] == "--force"  || $cutInput[$i] == "-f") {
                    $commandArray["--force"] = true;
                }
            }
        }

        return $commandArray;
    }

    // Execute custom command
    public function executeCustomCommand($command, $cutInput) {
        switch ($command) {
            case "test":
                return array("Custom commands are enabled!");
                break;
            case "log:clear":
                file_put_contents(storage_path('consoleLog.txt'), "");
                return array("Contents of the Console Log have been cleared!");
                break;
            case "log:view":
                return array("rdir:/dashboard/consoleLog");
                break;
            case "setbatch":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["id"] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray["number"] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: setbatch [migration ID] [batch number]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'number' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $entry = DB::table('migrations')->where('id', $commandArray['id'])->first();
                    if ($entry != NULL) {
                        DB::table('migrations')
                            ->where('id', $commandArray['id'])
                            ->update(['batch' => $commandArray['number']]);
                        return array("Batch number of $entry->migration has been set to ".$commandArray["number"]);
                        break;
                    } else {
                        return array("Error: No migration with ID: ".$commandArray['id']." was found.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: setbatch [migration ID] [batch number]");
                    break;
                }
            case "getbatch":
                $entries = DB::table('migrations')->get();
                if ($entries != NULL) {
                    $returnArray = array();
                    foreach ($entries as $entry) {
                        array_push($returnArray, "$entry->id | $entry->migration | $entry->batch");
                    }
                    return $returnArray;
                    break;
                } else {
                    return array("No migrations found.");
                    break;
                }
            case "commands":
                $returnArray = array();
                array_push($returnArray, "Artisan Commands:");
                foreach (Config::get('commands.whitelist') as $command) {
                    array_push($returnArray, "-$command");
                }
                array_push($returnArray, "Custom Commands:");
                foreach (Config::get('commands.custom') as $command) {
                    array_push($returnArray, "-$command");
                }
                return $returnArray;
            case "user:get":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["searchInput"] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:get [searchInput/id/'all']");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'searchInput' => 'required',
                ]);

                if (!$validation->fails()) {
                    if ($commandArray['searchInput'] == 'all') {
                        $entries = User::all();
                    } else {
                        $search = $commandArray['searchInput'];
                        $entries = User::where('id', $commandArray['searchInput'])->orWhere(function ($query) use ($search) {
                            $query->where('email', 'LIKE', "%$search%")
                                  ->orWhere('name', 'LIKE', "%$search%");
                        })->get();
                    }

                    if (sizeof($entries) > 0) {
                        $returnArray = array();
                        foreach ($entries as $entry) {
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }
                            array_push($returnArray, "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        }
                        return $returnArray;
                        break;
                    } else {
                        return array("No users found.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:get [searchInput/id/'all']");
                    break;
                }
            case "user:create":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["name"] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray["email"] = $cutInput[$i];
                    }
                    if ($i == 3) {
                        $commandArray["password"] = $cutInput[$i];
                    }
                    if ($i == 4) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["active"] = true;
                        } else {
                            $commandArray["active"] = false;
                        }
                    }
                    if ($i == 5) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["autoverify"] = true;
                        } else {
                            $commandArray["autoverify"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:create [name] [email] [password] [active:true/false] [autoverify:true/false]");
                        break;
                    }
                }

                if (!array_key_exists('autoverify', $commandArray)) {
                    $commandArray['autoverify'] = false;
                }
                if (!array_key_exists('active', $commandArray)) {
                    $commandArray['active'] = false;
                }
                $validation = Validator::make($commandArray, [
                    'name' => 'required|min:2',
                    'email' => 'required|email',
                    'password' => 'required|min:4',
                    'active' => 'required|boolean',
                    'autoverify' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = new User();
                    $user->name = $commandArray['name'];
                    $user->password = Hash::make($commandArray['password']);
                    $user->email = $commandArray['email'];
                    $user->active = $commandArray['active'];
                    $result = $user->Save();

                    if ($result) {
                        if ($commandArray['autoverify']) {
                            $user->markEmailAsVerified();
                        } else {
                            $user->sendEmailVerificationNotification();
                        }

                        $entry = User::where('id', $user->id)->first();
                        $active = "inactive";
                        $verified = "unverified";
                        if ($entry->active) {
                            $active = "active";
                        }
                        if ($entry->email_verified_at != NULL) {
                            $verified = "verified";
                        }

                        return array("Success: User has been created.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        break;
                    } else {
                        return array("Error: User could not be created.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:create [name] [email] [password] [active:true/false] [autoverify:true/false]");
                    break;
                }
            case "user:edit:active":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["active"] = true;
                        } else {
                            $commandArray["active"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:active [id] [active:true/false]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'active' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->active = $commandArray['active'];
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:active [id] [active:true/false]");
                    break;
                }
            case "user:edit:verified":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["verified"] = true;
                        } else {
                            $commandArray["verified"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:verified [id] [verified:true/false]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'verified' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        if ($commandArray['verified']) {
                            $user->markEmailAsVerified();
                        } else {
                            $user->email_verified_at = NULL;
                            $result = $user->Save();
                        }

                        $entry = User::where('id', $user->id)->first();
                        $active = "inactive";
                        $verified = "unverified";
                        if ($entry->active) {
                            $active = "active";
                        }
                        if ($entry->email_verified_at != NULL) {
                            $verified = "verified";
                        }

                        return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        break;
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:active [id] [verified:true/false]");
                    break;
                }
            case "user:edit:name":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['name'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:name [id] [name]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'name' => 'required|min:2',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->name = $commandArray['name'];
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:name [id] [name]");
                    break;
                }
            case "user:edit:email":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['email'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:email [id] [email]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'email' => 'required|email',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->email = $commandArray['email'];
                        $user->email_verified_at = NULL;
                        $result = $user->Save();

                        if ($result) {
                            $user->sendEmailVerificationNotification();

                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:email [id] [email]");
                    break;
                }
            case "user:edit:password":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['password'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:password [id] [password]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'password' => 'required|min:4',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->password = Hash::make($commandArray['password']);
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:password [id] [password]");
                    break;
                }
            case "user:delete":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:delete [id]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:delete [id]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $result = $user->delete();

                        if ($result) {
                            return array("Success: User has been removed.");
                            break;
                        } else {
                            return array("Error: User could not be removed.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:delete [id]");
                    break;
                }
            default:
                return array("Error: Command not recognised.");
                break;
        }
    }
}
