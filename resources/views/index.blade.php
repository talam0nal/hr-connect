@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Панель для отображение успешных эвентов --}}
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

    {{-- Панель для отображения ошибок --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (\Session::has('error'))
                <div class="bd-example">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! \Session::get('error') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (!$isManager)
        {{-- Форма отправки заявки --}}
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

        {{-- Таблица с заявками для пользователей --}}
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
                          <th>Файл</th>
                          <th>Управление</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>

                              <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}">
                                    {{ $ticket->theme }}
                                </a>
                              </td>

                              <td class="status-{{ $ticket->id }}">
                                @if ($ticket->is_closed)
                                    Заявка закрыта
                                    @elseif ($ticket->status == 1)
                                        Принята в обработку
                                    @elseif ($ticket->status == 0)
                                        Ждёт обработки
                                @endif
                              </td>

                              <td>
                                {{ $ticket->messages->count() }}
                              </td>

                              <td>
                                @if ($ticket->file)
                                    <a href="{{ Storage::url($ticket->file) }}" target="blank">
                                        Загрузить
                                    </a>
                                    @else
                                    N/A
                                @endif
                                
                              </td>

                              <td>
                                @if ($ticket->is_closed)
                                    <button type="button" class="btn btn-info btn-sm close-ticket" disabled="true" style="cursor: not-allowed;">
                                        Заявка закрыта
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-info btn-sm close-ticket" data-id="{{ $ticket->id }}">
                                        Закрыть заявку
                                    </button>
                                @endif
                             </td>

                            </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        @endif

        @else

        {{-- Таблица заявок для менеджеров --}}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Текущие заявки</h2>
                @if (count($tickets))
                    <a href="{{ route('main') }}" class="btn btn-primary btn-sm mb-3">Все</a>
                    <a href="{{ route('main', ['filter' => 'viewed']) }}" class="btn btn-primary btn-sm mb-3">Просмотренные</a>
                    <a href="{{ route('main', ['filter' => 'unviewed']) }}" class="btn btn-primary btn-sm mb-3">Непросмотренные</a>
                    <a href="{{ route('main', ['filter' => 'closed']) }}" class="btn btn-primary btn-sm mb-3">Закрытые</a>
                    <a href="{{ route('main', ['filter' => 'unclosed']) }}" class="btn btn-primary btn-sm mb-3">Незакрытые</a>
                    <a href="{{ route('main', ['filter' => 'hasAnswer']) }}" class="btn btn-primary btn-sm mb-3">С ответом менеджера</a>
                    <a href="{{ route('main', ['filter' => 'withoutAnswer']) }}" class="btn btn-primary btn-sm mb-3">Без ответа менеджера</a>
                @endif
                @if (!count($tickets))
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Заявок нет</h4>
                        <p>На данный момент пользователями ещё не создано ни одной заявки</p>
                    </div>
                @endif

                @if (count($tickets))
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Тема</th>
                          <th>Статус</th>
                          <th>Сообщений</th>
                          <th>Файл</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>

                              <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}">
                                    {{ $ticket->theme }}
                                </a>
                              </td>

                              <td class="status-{{ $ticket->id }}">
                                @if ($ticket->is_closed)
                                    Заявка закрыта
                                    @elseif ($ticket->status == 1)
                                        Принята в обработку
                                    @elseif ($ticket->status == 0)
                                        Ждёт обработки
                                @endif
                              </td>

                              <td>
                                {{ $ticket->messages->count() }}
                              </td>

                              <td>
                                @if ($ticket->file)
                                    <a href="{{ Storage::url($ticket->file) }}" target="blank">
                                        Загрузить
                                    </a>
                                    @else
                                    N/A
                                @endif
                                
                              </td>

                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                @endif

            </div>
        </div>
    @endif

</div>

<script>

    $(function() {
        $('.close-ticket').click(function() {
            var btn = $(this);
            let id = btn.data('id');
            let destination = '/closeticket/'+id;
            $.get(destination, function(data) {
                btn.text('Заявка закрыта');
                btn.removeClass('btn-info').addClass('btn-success').attr('disabled', 'true').css({'cursor': 'not-allowed'});
                $('.status-'+id).text('Заявка закрыта');
            });
        });
    });

</script>
@endsection