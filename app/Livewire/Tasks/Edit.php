<?php



namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class Edit extends Component
{

    public $task;
    public function render()
    {
        return view('livewire.tasks.edit');
    }


    public $name;
    public $description;
    public $due_date;

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->name = $task->name;
        $this->description = $task->description;
        $this->due_date = $task->due_date;
    }

    public function updateTask()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $this->task->update([
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
        ]);

        session()->flash('success', 'Task updated successfully');
        $this->emit('taskUpdated'); // Emit event for updating the UI

        // Optionally, you can redirect to another page after the update
        // return redirect()->route('tasks.index');
    }

}
