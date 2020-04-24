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

    @if (!$isManager)
        <!-- Форма отправки заявки -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Добавить новую заявку</h2>
                <form method="POST" enctype="multipart/form-data" action="{{ route('tickets.store') }}">
                    @csrf
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

        @if (count($tickets))
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <h2>Ваши заявки</h2>

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Тема</th>
                          <th>Статус</th>
                          <th>Сообщений</th>
                          <th>Управление</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                              <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->theme }}</a></td>
                              <td>@if ($ticket->status)Принята в обработку @else Ждёт обработки @endif</td>
                              <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->messages->count() }}</a></td>
                              <td><button type="button" class="btn btn-info">Закрыть заявку</button></td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        @endif

        @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Заявки</h2>
            </div>
        </div>
    @endif


</div>
@endsection
