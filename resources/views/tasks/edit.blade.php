<x-app-layout>
 <x-slot name="header">
  <h2 class="font-semibold text-xl text-white leading-tight">
      {{ __('Edit Task') }}
  </h2>
 </x-slot>

 <div class="py-12">
  <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">You're editing Task: {{ $task->name }}</h3>
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">With Description: {{ $task->description }}</h3>
          <h3 class="text-lg font-semibold mb-4 text-white font-weight-bold">And Due date: {{ $task->due_date }}</h3>

          <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')
            <label for="name"><p style="color: white;"> Name: </p>
</label>
            <input type="text" id="name" name="name" value="{{ $task->name }}">
            <br>
            <br>
            <br>


            <label for="description">            <label for="name"><p style="color: white;"> Description: </p>
</label>
            <textarea id="description" name="description">{{ $task->description }}</textarea>
            <br>
            <br>
            <br>

            <label for="due_date">            <label for="name"><p style="color: white;"> Due Date: </p>
</label>            

            
        <input type="datetime-local" id="due_date" name="due_date" value="{{ $task->due_date }}">
        <br>
        <br>
        <br>

        <button type="submit" style="background-color: blue; color: white; padding: 10px 20px; border: none;">Update</button>
          </form>
      </div>
  </div>
 </div>
</x-app-layout>
