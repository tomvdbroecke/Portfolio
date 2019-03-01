<?php

namespace App;

use App\Project;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version', 'changes'
    ];

    // OwnerProject
    public function ownerProject() {
        $Projects = Project::all();
        foreach ($Projects as $Project) {
            if ($Project->changelogs != NULL) {
                $logs = json_decode($Project->changelogs);
                foreach ($logs as $log) {
                    if ($log == $this->id) {
                        return $Project;
                    }
                }
            }
        }
    }
}
