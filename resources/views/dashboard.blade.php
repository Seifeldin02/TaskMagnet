<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Create Task Square -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create Task</h3>
                    <p class="text-gray-600 dark:text-gray-300">Click here to create a new task.</p>
                    <a href="{{ route('tasks.create') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Create Task</a>
                </div>

                <!-- View Tasks Square -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">View Tasks</h3>
                    <p class="text-gray-600 dark:text-gray-300">Click here to view existing tasks.</p>
                    <a href="{{ route('tasks.index') }}" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">View Tasks</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>