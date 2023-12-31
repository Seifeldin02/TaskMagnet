<?php

// app/Http/Livewire/CreateTask.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Task;

class CreateTask extends Component
{
    public $name;
    public $description;
    public $due_date;

    public function saveTask()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        Task::create([
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'user_id' => auth()->id(),
        ]);

        $this->reset(); // Reset form fields

        session()->flash('success', 'Task created successfully');

        return redirect()->route('tasks.index');
    }

    public function render()
    {
        return view('livewire.create-task');
    }
}

