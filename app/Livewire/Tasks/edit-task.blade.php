<!-- resources/views/livewire/tasks/edit-task.blade.php -->

<div>
    <form wire:submit.prevent="updateTask">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Task Name
            </label>
            <input wire:model="name" type="text" id="name" class="form-input mt-1 block w-full" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Task Description
            </label>
            <textarea wire:model="description" id="description" class="form-input mt-1 block w-full" required></textarea>
        </div>

        <div class="mb-4">
            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Task Due Date and Time
            </label>
            <input wire:model="due_date" type="datetime-local" id="due_date" class="form-input mt-1 block w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update Task
        </button>
    </form>
</div>
