<x-mail::message>
# Hello,

{{ $task->user->name }} has shared their task "{{ $task->name }}" with you, click the button below to accept!

<x-mail::button :url="$url">
Accept Task
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>