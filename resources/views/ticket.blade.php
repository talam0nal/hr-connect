@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ $ticket->theme }}</h2>

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


            </div>
        </div>
    </div>

@endsection