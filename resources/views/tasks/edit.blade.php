<x-app-layout>
 <x-slot name="header">
  <h2 class="font-semibold text-xl text-white leading-tight">
      {{ __('Edit Task') }}
  </h2>
 </x-slot>

 <div class="py-12">
  <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">Task: <span class="font-normal">{{ $task->name }}</span></h3>
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">Description: <span class="font-normal">{{ $task->description }}</span></h3>
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">Due date: <span class="font-normal">{{ $task->due_date }}</span></h3>

          <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
              <label for="name" class="block text-sm font-medium text-white">Name</label>
              <input type="text" id="name" name="name" value="{{ $task->name }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-white">Description</label>
              <textarea id="description" name="description" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $task->description }}</textarea>
            </div>

            <div>
              <label for="due_date" class="block text-sm font-medium text-white">Due Date</label>
              <input type="datetime-local" id="due_date" name="due_date" value="{{ $task->due_date }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Update
              </button>
            </div>
          </form>
      </div>
  </div>
 </div>
</x-app-layout>