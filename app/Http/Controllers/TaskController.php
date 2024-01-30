<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    public function start(Request $request) {
        $task = Task::find($request->id);

        $task->isWorking = true;
        $task->start_work_time = \Carbon\Carbon::now();
        $task->save();

        return response()->json($task,200);
    }
    public function pause(Request $request) {
        $task = Task::find($request->id);

        $ndate = \Carbon\Carbon::parse($task->start_work_time);
        $olddate = \Carbon\Carbon::now();

        $task->isWorking = false;
        $task->work_time = $task->work_time + abs(strtotime($ndate->toDateTimeLocalString()) - strtotime($olddate->toDateTimeLocalString()));
        
        $task->save();

        return response()->json($task,200);
    }

    public function create(Request $request) {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->user_id = $request->user()->id;
        $task->project_id = $request->project_id;

        $task->save();
        
        $task = Task::where('id',$task->id)->with('user','userWorking')->first();

        return response()->json($task,200);
    }
    public function setWorkUser(Request $request) {
        $task = Task::find($request->id);

        $task->user_working_id = $request->user()->id;
        $task->save();

        return response()->json($task,200);
    }
    public function done(Request $request) { 
        $task = Task::find($request->id);

        $task->isDone = true;
        $task->save();

        return response()->json($task,200);
     }
}
