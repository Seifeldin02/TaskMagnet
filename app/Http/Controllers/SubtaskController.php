<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subtask;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
{
    $subtask = new Subtask($request->all());
    $task->subtasks()->save($subtask);
    return back();
}

public function edit(Subtask $subtask)
{
    return view('subtasks.edit', compact('subtask'));
}

public function update(Request $request, Subtask $subtask)
{
    $subtask->update($request->all());
    return back();
}

public function destroy(Subtask $subtask)
{
    $subtask->delete();
    return back();
}

public function updateCompletion(Request $request, Subtask $subtask)
{
    $subtask->completion_rate = max(0, min(100, $subtask->completion_rate + $request->change));
    $subtask->save();
    return back();
}
}
