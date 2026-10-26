@extends('admin.layouts.master')

@section('title', 'Документы')

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    @if(session()->has('success'))
                        <p class="alert alert-success">{{ session()->get('success') }}</p>
                    @endif
                    @if(session()->has('warning'))
                        <p class="alert alert-warning">{{ session()->get('warning') }}</p>
                    @endif
                    <div class="row">
                        <div class="col-md-7">
                            <h1>Документы</h1>
                        </div>
                        <div class="col-md-5">
                            <a href="{{ route('admin-documents.create') }}" class="btn btn-success">Добавить</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Автор</th>
                            <th>Доступ к скачиванию</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->title }}</td>
                                <td>
                                    @foreach($document->categories as $category)
                                        @if($loop->last)
                                            {{ $category->title }}
                                        @else
                                            {{ $category->title }},
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $document->user->name ?? 'Администратор'}}</td>
                                <td>
                                    @if($document->access == 1)
                                        <div class="alert alert-success">Доступен</div>
                                    @else
                                        <div class="alert alert-danger">Недоступен</div>
                                    @endif
                                </td>
                                <td>
                                    @if($document->status == 1)
                                        <div class="alert alert-success">
                                            Включен
                                        </div>
                                    @else
                                        <div class="alert alert-danger">Отключен</div>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin-documents.destroy', $document) }}" method="post">
                                        <a href="{{ route('admin-documents.show', $document) }}" class="btn
                                        btn-primary">Открыть</a>
                                        <a class="btn btn-warning" href="{{ route('admin-documents.edit', $document)
                                            }}">Редактировать</a>
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger"
                                                onclick="return confirm('Вы уверены, что хотите удалить?')">Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $documents->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection