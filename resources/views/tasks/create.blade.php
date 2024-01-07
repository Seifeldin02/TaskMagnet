<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form action="{{ route('tasks.store') }}" method="post">
    @csrf

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Task Name
        </label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input mt-1 block w-full rounded p-2 @error('name') border-red-500 @enderror" required>
        @error('name')
            <div class="text-red-500 mt-2 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Task Description
        </label>
        <textarea name="description" id="description" class="form-input mt-1 block w-full rounded p-2 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
        @error('description')
            <div class="text-red-500 mt-2 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Task Due Date and Time
        </label>
        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" class="form-input mt-1 block w-full rounded p-2 @error('due_date') border-red-500 @enderror" required>
        @error('due_date')
            <div class="text-red-500 mt-2 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400 transition duration-200 ease-in-out mt-4">
        Create Task
    </button>
</form>
                <Livewire:Tasks.Create />
            </div>
        </div>
    </div>
</x-app-layout>