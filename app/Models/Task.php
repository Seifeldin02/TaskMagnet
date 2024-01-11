<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage; 
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'priority',
        'completion_rate',
    ];
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
    public function shares()
    {
        return $this->hasMany(Share::class);
    }
    public function comments()
{
    return $this->hasMany(Comment::class);
}
public function sharedUser()
{
    return $this->hasOneThrough(User::class, Share::class);
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            // Delete all comments and their related images
            if ($task->comments) {
                foreach ($task->comments as $comment) {
                    // Delete the comment's image
                    if ($comment->image) {
                        Storage::delete($comment->image);
                    }
            
                    // Delete the comment
                    $comment->delete();
                }
            }
        });
    }
    public function dueDateRemaining()
    {
        $now = Carbon::now();
        $dueDate = Carbon::parse($this->due_date);

        if ($dueDate->isFuture()) {
            $diff = $now->diff($dueDate);

            $years = $diff->y > 0 ? $diff->y . ' years, ' : '';
            $months = $diff->m > 0 ? $diff->m . ' months, ' : '';
            $weeks = floor($diff->d / 7) > 0 ? floor($diff->d / 7) . ' weeks, ' : '';
            $days = $diff->d % 7 > 0 ? $diff->d % 7 . ' days, ' : '';
            $hours = $diff->h > 0 ? $diff->h . ' hours, ' : '';
            $minutes = $diff->i > 0 ? $diff->i . ' minutes, ' : '';
            $seconds = $diff->s > 0 ? $diff->s . ' seconds' : '';

            return 'Due date is in ' . rtrim("$years$months$weeks$days$hours$minutes$seconds", ', ');
        } else {
            $diff = $dueDate->diff($now);
            return 'Due date has passed by ' . $diff->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
        }
    
        $daysUntilDue = $now->diffInDays($dueDate, false);
    
        return [
            'dayss' => $daysUntilDue,
            'isPastDue' => $dueDate->isPast(),
        ];
    }

    public function dueDateDetails()
{
    $now = Carbon::now();
    $dueDate = Carbon::parse($this->due_date);

    $daysUntilDue = $now->diffInDays($dueDate, false);
    $isPastDue = $dueDate->isPast();

    return [
        'days' => $daysUntilDue,
        'isPastDue' => $isPastDue,
    ];
}
}
