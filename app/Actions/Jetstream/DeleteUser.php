<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\File; // Add this line
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        // Retrieve all tasks of the user
        $tasks = $user->tasks;
    
        foreach ($tasks as $task) {
            // Retrieve all comments of the task
            $taskComments = $task->comments;
    
            foreach ($taskComments as $comment) {
                // Delete the associated image from the storage
                if ($comment->image) {
                    File::delete(public_path($comment->image));
                }
    
                // Delete the comment itself
                $comment->delete();
            }
    
            // Delete the task itself
            $task->delete();
        }
    
        // Retrieve all comments of the user
        $comments = Comment::where('user_id', $user->id)->get();
    
        foreach ($comments as $comment) {
            // Delete the associated image from the storage
            if ($comment->image) {
                File::delete(public_path($comment->image));
            }
    
            // Delete the comment itself
            $comment->delete();
        }
    
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}