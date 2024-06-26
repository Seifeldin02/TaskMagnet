<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">Your Tasks</h3>
 
                @if(count($tasks) > 0)
                <form action="{{ route('tasks.toggleAutoSort') }}" method="POST" class="mb-4">
    @csrf
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ session('autoSort', false) ? 'Turn Off Automatic Sorting' : 'Turn On Automatic Sorting' }}
    </button>
</form>
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 font-semibold text-white">Name</th>
                                <th class="px-4 py-2 font-semibold text-white">Description</th>
                                <th class="px-4 py-2 font-semibold text-white">Due Date</th>
                                <th class="px-4 py-2 font-semibold text-white">Time until Due Date</th>
                                <th class="px-4 py-2 font-semibold text-white">Completion Rate</th>
                                <th class="px-4 py-2 font-semibold text-white">Priority</th>
                                <th class="px-4 py-2 font-semibold text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
    @php
        $isCompleted = $task->completion_rate == 100;
        $dueDateDetails = $task->dueDateDetails(); // Get the due date details
        $days = $dueDateDetails['days']; // Get the number of days remaining
        $isPastDue = $dueDateDetails['isPastDue']; // Check if the due date has passed
        $timeUntilDue = $task->dueDateRemaining(); // Get the time until the due date
    @endphp
    <tr class="{{ $isCompleted ? 'line-through-thick' : '' }} hover:bg-gray-600 transition ease-in-out duration-150">        <td class="border px-4 py-2 text-white hover:text-gray-900 relative group">
            <a href="{{ route('tasks.show', $task) }}" class="text-blue-500 hover:text-blue-700">{{ $task->name }}</a>
            @if($task->user)
                <img class="inline-block h-6 w-6 rounded-full ml-2" src="{{ $task->user->profile_photo_url }}" alt="{{ $task->user->name }}'s profile photo">
            @endif
            @foreach($task->shares as $share)
                @if($share->user)
                    <img class="inline-block h-6 w-6 rounded-full ml-2" src="{{ $share->user->profile_photo_url }}" alt="{{ $share->user->name }}'s profile photo">
                @endif
            @endforeach
        </td>
        <td class="border px-4 py-2 text-white">{{ $task->description }}</td>
        <td class="border px-4 py-2 text-white">{{ $task->due_date }}</td>
        <td class="border px-4 py-2 relative">
            <span class="{{ $isPastDue ? 'text-gray-400' : ($days <= 1 ? 'text-red-600' : ($days <= 7 ? 'text-red-400' : 'text-white')) }}">
                {{ $timeUntilDue }}
            </span>
        </td>
        <td class="border px-4 py-2 text-white text-center">
            {{ $task->completion_rate }}/100
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                    <div style="width:{{ $task->completion_rate }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                </div>
            </div>
        </td>
        <td class="border px-4 py-2 text-white relative">
            <div class="w-full h-6 bg-red-200">
                <div class="h-full" style="width:{{ $task->priority >= 11 ? 0 : (10 - $task->priority) * 10 }}%; background-color: red;"></div>
            </div>
        </td>
        <td class="border px-4 py-2 text-white">
            @if(auth()->user()->id == $task->user_id)
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                </form>
            @endif
            <form action="{{ route('tasks.priorityUp', $task) }}" method="POST" class="inline-block">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Up</button>
            </form>
            <form action="{{ route('tasks.priorityDown', $task) }}" method="POST" class="inline-block">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Down</button>
            </form>
        </td>
    </tr>
@endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-700 dark:text-gray-300">No tasks found.</p>
                @endif

                <div class="mt-4">
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-4">Create Task</a>                </div>

            </div>
        </div>
    </div>
</x-app-layout>