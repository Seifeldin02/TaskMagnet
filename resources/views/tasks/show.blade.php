<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
            
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2><strong>Task Name:</strong> {{ $task->name }}</h2>
                    <p><strong>Task Description:</strong> {{ $task->description }}</p>
                    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
                    <p><strong>Due Date Remaining:</strong> {{ $task->dueDateRemaining() }}</p>
                    <p><strong>Completion Rate:</strong> <span style="color:black;">{{ $task->completion_rate }}/100</span></p>                    <div class="relative pt-1">
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                            <div style="width:{{ $task->completion_rate }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('tasks.updateCompletion', $task->id) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="change" value="10">
    <button type="submit"><div class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">+</button>
</form>
<form method="POST" action="{{ route('tasks.updateCompletion', $task->id) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="change" value="-10">
    <button type="submit"><div class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">-</button>
</form>
<br>
<div class="mt-4">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">Edit Task</a>
                    </div>
                    @if(auth()->id() === $task->user_id)
    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
            Delete Task
        </button>
    </form>
@endif
                        <br>
                        <br>
                        <br>
<h3><strong>Active Users: </strong></h3>
<ul>
@foreach($users as $user)
    <li class="flex items-center">
        <img class="inline-block h-6 w-6 rounded-full mr-2" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}'s profile photo">
        {{ $user->name }}
        @if($task->user_id == auth()->id() && $user->id != $task->user_id)
            <form method="POST" action="{{ route('tasks.removeShare', [$task, $user]) }}" class="inline">
                @csrf
                @method('DELETE')
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10"><small>Delete From List</small></button>
            </form>
        @endif
    </li>
@endforeach
</ul>
<br>
@if($task->shares->pluck('user_id')->contains(auth()->id()))
    <form method="POST" action="{{ route('shares.destroy', $task->shares->where('user_id', auth()->id())->first()) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-500 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Remove Shared</button>    </form>
@endif

                    <div class="mt-4">

                    </div>
                    <form method="POST" action="{{ route('tasks.share', $task->id) }}" class="mt-4">
    @csrf
    <input type="email" name="email" placeholder="Enter email to share with" required>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">Share Task</button>
</form>
<br>
<br>
<br>


<h3>Comments:</h3>
@foreach($comments as $comment)
<div class="p-4 mt-4 bg-gray-100 rounded shadow">
        <div class="flex items-center"> <!-- Add this line -->
            <p>
            <img class="inline-block h-12 w-12 rounded-full mr-2" src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}'s profile photo"> <!-- Add this line -->

                <strong>{{ $comment->user->name }}</strong>: <br>
                {{ $comment->comment }}
                <br>
                @if($comment->image)
                    <img src="{{ asset($comment->image) }}" alt="Image">
                @endif
                <small>Commented at: {{ $comment->created_at }}</small><br>
                @if($comment->updated_at != $comment->created_at)
                    <small>Edited at: {{ $comment->updated_at }}</small>
                @endif
            </p>
        </div> <!-- Add this line -->
        @if(auth()->id() === $comment->user_id)
            <a href="{{ route('comments.edit', $comment->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">Edit</a>
            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">Delete</button>
            </form>
        @endif
    </div>
@endforeach
    <form method="POST" action="{{ route('tasks.comments.store', $task->id) }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        <textarea name="comment" placeholder="Add a comment..." class="w-full"></textarea>
        <button type="submit" class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 z-10">Add Comment</button>

        <input type="file" name="image" accept="image/*">
    </form>
                </div>
            </div>
        </div>
    </div>
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

</x-app-layout>