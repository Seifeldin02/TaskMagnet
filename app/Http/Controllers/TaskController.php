<?php

// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;
use App\Notifications\DueDateApproaching;
use Illuminate\Support\Facades\Notification;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShareTask;
use App\Models\User;
use App\Models\Share;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{


    public function accept($taskId)
    {
        // Ensure the user is logged in. If not, redirect them to the registration page.
        if (!auth()->check()) {
            return redirect()->route('register');
        }
    
        $userId = auth()->id();
    
        // Check if the user already has the task
        $user = User::find($userId);
        if ($user->tasks->contains($taskId) || $user->shares->contains('task_id', $taskId)) {
            return back()->withErrors(['error' => 'You already have this task.']);
        }
    
        // Create the Share
        $share = new Share;
        $share->task_id = $taskId;
        $share->user_id = $userId;
        $share->accepted = true;
        $share->save();
    
        return redirect()->route('tasks.index');
    }

    
    public function storeComment(Request $request, Task $task)
{
    $validatedData = $request->validate([
        'content' => 'required|string',
    ]);

    $comment = new Comment;
    $comment->content = $validatedData['content'];
    $comment->task_id = $task->id;
    $comment->save();

    return back();
}
public function updateCompletion(Request $request, Task $task)
{
    $task->completion_rate += $request->input('change');
    $task->completion_rate = max(0, min(100, $task->completion_rate)); // Ensure it stays between 0 and 100
    $task->save();

    return back();
}
public function removeShare(Task $task, User $user)
{
    $task->shares()->where('user_id', $user->id)->delete();

    return back();
}
public function share(Request $request, Task $task)
{
    $validatedData = $request->validate([
        'email' => 'required|email',
    ]);

    try {
        $userEmail = $validatedData['email'];
        $currentUserEmail = auth()->user()->email;

        // Check if the user is trying to share the task with themselves
        if ($userEmail === $currentUserEmail) {
            return back()->withErrors(['email' => 'You cannot share a task with yourself.']);
        }

        $user = User::where('email', $userEmail)->first();

        // Check if the user exists
        if (!$user) {
            return back()->withErrors(['email' => 'This user does not exist.']);
        }

        // Check if the user already has the task
        if ($user->tasks->contains($task->id) || $user->shares->contains('task_id', $task->id)) {
            return back()->withErrors(['email' => 'This user already has the task.']);
        }

        // Send the email
        Mail::to($user->email)->send(new ShareTask($task, $task->id));

        return back()->with('success', 'Task shared successfully.');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return back()->withErrors(['error' => 'An error occurred while sharing the task.']);
    }
}
    public function checkDueDates()
    {
        // Fetch tasks due today
        $tasks = Task::where('due_date', Carbon::today())->get();
    
        // Loop through the tasks and notify the associated user
        foreach ($tasks as $task) {
            $task->user->notify(new TaskDueNotification($task));
        }
    }
    public function markAsRead($id)
    {
        $task = Task::findOrFail($id);

        $task->unreadNotifications->markAsRead();

        return redirect()->back();
    }
    public function show(Task $task)
    {
            // Check if the authenticated user is the owner of the task or the task has been shared with them
    if (Auth::id() !== $task->user_id && !$task->shares->pluck('user_id')->contains(Auth::id())) {
        return redirect()->route('tasks.index')->withErrors(['error' => 'You do not have permission to view this task.']);
    }
        $comments = Comment::where('task_id', $task->id)->get();
        $users = User::where('id', $task->user_id)->get(); // Original user
        $users = $users->concat($task->shares->map->user); // Shared users

        return view('tasks.show', compact('task', 'comments', 'users'));
    }
    

    public function update(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        // Update task details
        $task->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
        ]);

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task updated successfully');
    }
    public function edit($id)
{
   // Get the task
   $task = Task::find($id);

   // Return the edit form with the task
   return view('tasks.edit')->with('task', $task);
}
    public function getRemainingTime($id)
    {
        $task = Task::findOrFail($id);

        $remainingTime = $task->dueDateRemaining();

        return response()->json(['remainingTime' => $remainingTime]);
    }


    public function destroy(Task $task)
    {
        foreach ($task->comments as $comment) {
            if ($comment->image) {
                File::delete(public_path($comment->image));
            }
        }
    
        $task->delete();

        Task::where('user_id', $task->user_id)
            ->where('priority', '>', $task->priority)
            ->decrement('priority');
  
  
        return redirect()->route('tasks.index');
    }
    public function index()
    {
        $userId = auth()->id();
    
        // Get the tasks that the user has created
        $userTasks = Task::where('user_id', $userId)->orderBy('priority', 'asc')->get();
    
        // Get the tasks that have been shared with the user
        $sharedTasks = Task::join('shares', 'tasks.id', '=', 'shares.task_id')
            ->where('shares.user_id', $userId)
            ->select('tasks.*', 'shares.user_id as shared_user_id')
            ->get();
    
        // Merge the two collections into one
        $tasks = $userTasks->concat($sharedTasks);
    
        return view('tasks.index', compact('tasks'));
    }

    
    public function store(Request $request)
    {
      $request->validate([
          'name' => 'required|string|max:255',
          'description' => 'required|string',
          'due_date' => 'required|date',
      ]);
    
      // Format the due_date to the correct format
      $formattedDueDate = Carbon::parse($request->input('due_date'))->format('Y-m-d H:i:s');
    
      // Get the number of tasks the user already has
      $user = \app\Models\User::withCount('tasks')->find(auth()->id());
      $taskCount = \app\Models\Task::where('user_id', auth()->id())->count();
      $priority = $user->tasks_count;
    
      // Create a new task using the authenticated user's ID
      $task = auth()->user()->tasks()->create([
          'name' => $request->input('name'),
          'description' => $request->input('description'),
          'due_date' => $formattedDueDate,
          'priority' => $priority,
          'comment' => [],
      ]);
    
      // Redirect to the task index or show view
      return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }
    
    public function create()
{
    return view('tasks.create');
}
    public function priorityUp(Task $task)
    {
       DB::transaction(function () use ($task) {
           $taskAbove = Task::where('user_id', $task->user_id)
               ->where('priority', '<', $task->priority)
               ->orderBy('priority', 'desc')
               ->first();
    
           if ($taskAbove) {
               $temp = $taskAbove->priority;
               $taskAbove->priority = -99999; // Temporarily set to a value not used in 'priority'
               $taskAbove->save();
    
               $task->priority = $temp;
               $task->save();
    
               $taskAbove->priority = $task->priority + 1;
               $taskAbove->save();
           }
       });
    
       return redirect()->route('tasks.index');
    }
    
    public function priorityDown(Task $task)
    {
       DB::transaction(function () use ($task) {
           $taskBelow = Task::where('user_id', $task->user_id)
               ->where('priority', '>', $task->priority)
               ->orderBy('priority', 'asc')
               ->first();
    
           if ($taskBelow) {
               $temp = $taskBelow->priority;
               $taskBelow->priority = -99999; // Temporarily set to a value not used in 'priority'
               $taskBelow->save();
    
               $task->priority = $temp;
               $task->save();
    
               $taskBelow->priority = $task->priority - 1;
               $taskBelow->save();
           }
       });
    
       return redirect()->route('tasks.index');
    }
}