Пользователь {{ $user->name }} оставил новую заявку с темой <a href="{{ route('tickets.show', $ticket->id) }}" target="_blank">«{{ $ticket->theme }}»</a><br>
@if ($ticket->message)
    Сообщение пользователя:<br>
    {{ $ticket->message }}
@endif