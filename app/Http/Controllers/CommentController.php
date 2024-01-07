<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\File; // Add this line

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
        if (!$request->filled('comment') && !$request->hasFile('image')) {
            return back()->with('error', 'You must provide either a comment or an image.');
        }
    
        $request->validate([
            'comment' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $comment = new Comment;
        if ($request->has('comment')) {
            $comment->comment = $request->comment;
        } else {
            $comment->comment = '';  // Set a default value
        }
        $comment->user_id = auth()->id();
        $comment->task_id = $task->id;
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images/comments'), $imageName);
            $comment->image = 'images/comments/'.$imageName;
        }
    
        $comment->save();
    
        return back();
    }
    
    public function update(Request $request, Comment $comment)
    {
        if (!$request->filled('comment') && !$request->hasFile('image')) {
            return back()->with('error', 'You must provide either a comment or an image.');
        }
    
        $request->validate([
            'comment' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }
    
        if ($request->has('comment')) {
            $comment->comment = $request->comment;
        } else {
            $comment->comment = '';  // Set a default value
        }
        // Remove image
        if ($request->remove_image) {
            File::delete(public_path($comment->image));
            $comment->image = null;
        }
    
        // Upload new image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($comment->image) {
                File::delete(public_path($comment->image));
            }
    
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images/comments'), $imageName);
            $comment->image = 'images/comments/'.$imageName;
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
        if ($comment->image) {
            File::delete(public_path($comment->image));
        }
        $comment->delete();

        return back();
    }

}