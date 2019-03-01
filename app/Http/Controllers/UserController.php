<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Project;
use App\Changelog;
use Hash;
use URL;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('verified');
        $this->middleware('isVerified')->except('verified');
        $this->middleware('active')->except('verified');
    }

    // Verified
    public function verified(Request $request) {
        $verified = false;
        if (Session()->has('verified')) {
            $verified = Session()->get('verified');
        }

        if ($verified) {
            return view('auth.verified');
        } else {
            return redirect('/login');
        }
    }

    // To dashboard
    public function dashboard() {
        return redirect('/dashboard/projects');
    }

    // To account
    public function account(Request $request) {
        return view('dashboard.account', [
            'User' => Auth::user(),
            'activePage' => 'account'
        ]);
    }

    // Post account
    public function editAccount(Request $request) {
        // If password needs to be changed
        if ($request->has('change_password')) {
            $validation = Validator::make($request->all(), [
                'current_password' => 'required|min:4',
                'new_password' => 'required|min:4',
                'new_password_repeat' => 'required|min:4',
            ]);

            if (!$validation->fails()) {
                $cPass = $request->input('current_password');
                $nPass = $request->input('new_password');
                $nPassR = $request->input('new_password_repeat');

                // Check if old password was correct
                if (Hash::check($cPass, Auth::user()->password)) {

                    // Check if both passwords are the same
                    if ($nPass == $nPassR) {

                        // Change password and attempt to save
                        Auth::user()->password = Hash::make($nPass);
                        $result = Auth::user()->Save();

                        // Redirect properly
                        if ($result) {
                            Session::flash('passwordSuccess', "Your password has been changed.");
                            return redirect('/dashboard/account');
                        } else {
                            Session::flash('passwordError', "Your new password could not be saved. Please try again later.");
                            return redirect('/dashboard/account');
                        }

                    } else {
                        Session::flash('passwordError', "The 'New Passwords' you've enter did not match.");
                        return redirect('/dashboard/account');
                    }

                } else {
                    Session::flash('passwordError', "Your 'Current Password' was incorrect.");
                    return redirect('/dashboard/account');
                }
            } else {
                Session::flash('passwordError', "Your inputs could not be validated.");
                return redirect('/dashboard/account');
            }
        }

        // If email needs to be changed
        if ($request->has('change_email')) {
            $validation = Validator::make($request->all(), [
                'new_email' => 'required|email',
                'new_email_repeat' => 'required|email',
            ]);

            if (!$validation->fails()) {
                $nEmail = $request->input('new_email');
                $nEmailR = $request->input('new_email_repeat');

                // Check if entered emails are valid
                if (filter_var($nEmail, FILTER_VALIDATE_EMAIL) && filter_var($nEmailR, FILTER_VALIDATE_EMAIL)) {

                    // Check if entered emails are the same
                    if ($nEmail == $nEmailR) {

                        // Attempt email change
                        Auth::user()->email = $nEmail;
                        Auth::user()->email_verified_at = NULL;
                        $result = Auth::user()->Save();

                        // Redirect properly
                        if ($result) {
                            Auth::user()->SendEmailVerificationNotification();
                            Session::flash('logoutMessage', "Your e-mail address has been changed to: $nEmail. Please verify your e-mail address before proceeding.");
                            Auth::logout();
                            return redirect('/login');
                        } else {
                            Session::flash('emailError', "Your new e-mail address could not be saved. Please try again later.");
                            return redirect('/dashboard/account');
                        }

                    } else {
                        Session::flash('emailError', "The e-mail addresses you have entered do not match.");
                        return redirect('/dashboard/account');
                    }

                } else {
                    Session::flash('emailError', "Please enter a valid e-mail address.");
                    return redirect('/dashboard/account');
                }
            } else {
                Session::flash('emailError', "Your inputs could not be validated.");
                return redirect('/dashboard/account');
            }
        }

        return view('dashboard.account', [
            'User' => Auth::user(),
            'activePage' => 'account'
        ]);
    }

    // To projects
    public function projects(Request $request) {
        $projects =  Auth::user()->Projects();

        if ($projects[0] == "all") {
            return view('dashboard.projects', [
                'User' => Auth::user(),
                'activePage' => 'projects',
                'Projects' => Project::all()
            ]);
        } else {
            if ($projects == NULL) {
                $projectList = array();
            } else {
                $projectList = Project::whereIn('id', $projects)->get();
            }

            return view('dashboard.projects', [
                'User' => Auth::user(),
                'activePage' => 'projects',
                'Projects' => $projectList
            ]);
        }
    }

    // View project
    public function viewProject(Request $request, $projectName) {
        $perm = Auth::user()->ProjectPermission($projectName);
        $project = Project::where('name', $projectName)->first();

        if ($project != NULL) {
            if ($perm && $projectName == $project->name) {
                $cl_array = json_decode($project->changelogs);
                if ($cl_array == NULL) {
                    $cLogs = NULL;
                } else {
                    $cLogs = Changelog::whereIn('id', $cl_array)->orderBy('created_at', 'desc')->get();
                }

                //return redirect('https://'.$project->accessUser.':'.$project->accessPass.'@tomvdbroecke.com/Projects/'.$project->name.'_public_'.$project->secretKey);
                return view('dashboard.viewProject', [
                    'User' => Auth::user(),
                    'Project' => $project,
                    'Changelogs' => $cLogs
                ]);
            }
        }

        return redirect('/dashboard');
    }

    // Embed project
    public function embedProject(Request $request, $projectName) {
        $perm = Auth::user()->ProjectPermission($projectName);
        $project = Project::where('name', $projectName)->first();

        if ($project != NULL) {
            if ($perm && $projectName == $project->name) {

                return redirect('https://'.$project->accessUser.':'.$project->accessPass.'@tomvdbroecke.com/Projects/'.$project->name.'_public_'.$project->secretKey);
            }
        }

        return redirect('/dashboard');
    }
}
