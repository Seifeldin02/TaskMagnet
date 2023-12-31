<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        $comments = $task->comments()->with('user')->get();
        return view('show', ['comments' => $comments]);
    }

    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user_id = auth()->id();
        $comment->task_id = $task->id;
    
        if ($request->hasFile('image')) {
            $comment->image = $request->file('image')->store('comments', 'public');
        }
    
        $comment->save();
    
        return back();
    }
    
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }
    
        $comment->comment = $request->comment;
    
        if ($request->hasFile('image')) {
            $comment->image = $request->file('image')->store('comments', 'public');
        }
    
        $comment->save();
    
        return redirect()->route('tasks.show', $comment->task->id);
    }

    public function edit(Comment $comment)
    {
        return view('edit', ['comment' => $comment]);
    }

    public function destroy(Comment $comment)
    {
        // Check if the authenticated user is the owner of the comment
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back();
    }

}