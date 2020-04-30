@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (\Session::has('success'))
                    <div class="bd-example">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! \Session::get('success') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ $ticket->theme }}</h2>
                <div class="mb-5">
                    {{ $ticket->message }}
                </div>

                @if (\Auth::user()->is_manager)
                    @if ($ticket->status)
                        <button type="button" class="btn btn-success mb-3" disabled="true" style="cursor: default;">Заявка принята на выполнение</button>
                        @else
                        <button type="button" class="btn btn-success mb-3 apply">Принять заявку на выполнение</button>
                    @endif
                @endif

                @foreach ($ticket->messages as $message)
                    <div class="card mb-3">
                        <div class="card-header">
                            {{ $message->theme }} @if ($message->user->is_manager)<b style="cursor: help;" title="Менеджер">@endif{{ $message->user->name }}@if ($message->user->is_manager)</b>@endif, {{ $message->created_at }}
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $message->message }}</p>
                        </div>
                    </div>
                @endforeach

                @if ($ticket->is_closed)
                <!-- Сообщение о том, что заявка закрыта -->
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Заявка закрыта!</h4>
                    <p>Данная заявка закрыта пользователем</p>
                    <hr>
                    <p class="mb-0">Вы можете открыть новую заявку в панели управления</p>
                </div>
                    @else
                        <!-- Форма отправки сообщения -->
                        <h4>Ответить на заявку</h4>
                        <form method="POST" enctype="multipart/form-data" action="{{ route('messages.store') }}">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <div class="form-group">
                                <label for="theme">Тема</label>
                                <input name="theme" class="form-control" id="theme" placeholder="Введите тему..." required>
                            </div>

                            <div class="form-group">
                                <label for="message">Сообщение</label>
                                <textarea name="message" class="form-control" id="message" rows="3" placeholder="Введите сообщение..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="file">Прикрепите файл</label>
                                <input type="file" name="attachment" class="form-control-file" id="file">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                        </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('.apply').click(function() {
                var btn = $(this);
                let destination = '{{ route('ticket.apply', $ticket->id) }}';
                $.get(destination, function(data) {
                    btn.text('Заявка принята на выполнение');
                    btn.attr('disabled', 'true').css({'cursor': 'default'});
                });
            });
        });
    </script>

@endsection