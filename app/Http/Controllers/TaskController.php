<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        $shared_tasks = Auth::user()->shared_tasks;
        return response()->json([
            'my_tasks' => $tasks,
            'shared_tasks' => $shared_tasks
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'datetime' => $request->datetime,
            'user_id' => Auth::id()
        ]);
        return response()->json([
            'message' => 'Successfulyy created task',
            'task' => $task
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'datetime' => $request->datetime
        ]);
        return response()->json([
            'message' => 'Successfulyy updated task',
            'task' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([
            'message' => 'Successfulyy deleted task',
            'task' => $task
        ]);
    }

    public function check(Request $request, $id){
        $checked = $request->checked;
        $task = Task::findOrFail($id);
        if($checked == "yes"){
            $task->update([
                'checked' => true
            ]);
            
        } else {
            $task->update([
                'checked' => false
            ]);
        }
        return response()->json([
            'message' => 'Successfully check task',
            'task' => $task
        ]);
    }

    public function share(Request $request, $id){
        $email = $request->email;
        $task = Task::findOrFail($id);
        $user = User::where('email', $email)->first();
        $task->update([
            'shared' => true
        ]);
        $task->shared_users()->attach($user->id);
        return response()->json([
            'message' => 'Successfully share your task',
            'task' => $task
        ]);
    }
}
