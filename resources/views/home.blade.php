@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Сообщение</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Вы успешно вошли в систему!
                </div>
            </div>
        </div>
    </div>


    <!-- Форма отправки заявки -->
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <h2>Добавить новую заявку</h2>
            <form method="POST" enctype="multipart/form-data" action="">
                @csrf
                <div class="form-group">
                    <label for="theme">Тема</label>
                    <input type="theme" class="form-control" id="theme" placeholder="Введите тему...">
                </div>

                <div class="form-group">
                    <label for="message">Сообщение</label>
                    <textarea name="message" class="form-control" id="message" rows="3" placeholder="Введите сообщение..."></textarea>
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
