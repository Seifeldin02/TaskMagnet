<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;
use App\Models\Share;

class NotifyDueTasks extends Command
{
    protected $signature = 'notify:duetasks';

    protected $description = 'Notify users of tasks due within a day';

    public function __construct()
    {
        parent::__construct();
    }
    public function via($notifiable)
    {
        return ['mail', 'database']; // specify here the channels you want to use
    }
    
    public function handle()
    {
        // Fetch tasks due within a day
        $tasks = Task::whereBetween('due_date', [Carbon::now(), Carbon::now()->addDay()])->get();
    
        // Loop through the tasks and notify the associated user
        foreach ($tasks as $task) {
            $task->user->notify(new TaskDueNotification($task));
    
            // Get the shared users
            $sharedUsers = $task->shares;
    
            // Notify the shared users
            foreach ($sharedUsers as $share) {
                $share->user->notify(new TaskDueNotification($task));
            }
        }
    }
}
?>