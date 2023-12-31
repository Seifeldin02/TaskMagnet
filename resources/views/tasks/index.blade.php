
<x-app-layout>
 <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Tasks') }}
    </h2>
 </x-slot>

 <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">Your Tasks</h3>

            @if(count($tasks) > 0)
              <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 font-weight-bold text-white">Name</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Description</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Due Date</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Time until Due Date</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Completion Rate</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Priority</th>
                        <th class="px-4 py-2 font-weight-bold text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
    <tr>
        <td class="border px-4 py-2 text-white">
            <a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a>
            @if(isset($task->shared_user_id))
                <small class="text-gray-500">Shared Task</small>
                <small class="text-gray-500">{{ $task->user->name }}</small>
            @endif
        </td>
        <td class="text-white border px-4 py-2">{{ $task->description }}</td>
        <td class="text-white border px-4 py-2">{{ $task->due_date }}</td>
        <td class="text-white border px-4 py-2">{{ $task->dueDateRemaining() }}</td>
        <td class="text-white border px-4 py-2"> {{ $task->completion_rate }}/100</td>

        <td class="text-white border px-4 py-2">{{ $task->priority }}</td>
        <td class="text-white border px-4 py-2">
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">|Delete|</button>
            </form>
            <form action="{{ route('tasks.priorityUp', $task) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">|Up|</button>
            </form>
            <form action="{{ route('tasks.priorityDown', $task) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-warning">|Down|</button>
            </form>
        </td>
    </tr>
@endforeach
                </tbody>
              </table>
            @else
              <p class="text-gray-700 dark:text-gray-300">No tasks found.</p>
            @endif
            
            <div class="container">
 <a href="{{ route('tasks.create') }}" class="btn" style="color: white; background-color: red; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;">Create Task</a>
</div>

</div>
 </div>
</x-app-layout>
