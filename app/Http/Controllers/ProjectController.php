<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;

class ProjectController extends Controller
{
    public function list()
    {
        $projects = Project::withSum('tasks', 'work_time')->get();
        return response()->json($projects, 200);
    }
    public function create(Request $request)
    {
        $project = new Project();

        $project->title = $request->title;
        $project->description = $request->description;

        $project->save();

        return response()->json($project, 200);
    }

    public function get(Request $request)
    {
        $project = Project::where('id', $request->id)->with('tasks.user', 'tasks.userWorking')->first();

        return response()->json($project, 200);
    }
    function edit(Request $request)
    {
        $project = Project::where('id', $request->id)->first();

        $project->title = $request->title;
        $project->description = $request->description;
        $project->save();

        return response()->json($project, 200);
    }
}
