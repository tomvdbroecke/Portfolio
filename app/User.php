<?php

namespace App;

use App\Project;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // IsAdmin() function
    public function IsAdmin() {
        return $this->permission_rank == "admin";
    }

    // IsActive() function
    public function IsActive() {
        return $this->active;
    }

    // Projects() function
    public function Projects() {
        if ($this->IsAdmin()) {
            return array("all");
        } else {
            return json_decode($this->permitted_projects);
        }
    }

    // ProjectPermission() function
    public function ProjectPermission($projectName) {
        $permissions = $this->Projects();
        $project = Project::where('name', $projectName)->first();

        if ($permissions[0] == "all") {
            return true;
        } else {
            foreach ($permissions as $perm) {
                if ($perm == $project->id) {
                    return true;
                }
            }

            return false;
        }
    }
}
