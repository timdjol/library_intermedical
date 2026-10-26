@extends('admin.layouts.master')

@section('title', 'Мои записи')

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
                            <h1>Библиотека</h1>
                        </div>
                        <div class="col-md-5">
                            <a href="{{ route('agent-posts.create') }}" class="btn btn-success">Добавить</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td><img src="{{ Storage::url($post->image) }}" alt=""></td>
                                <td>{{ $post->title }}</td>
                                <td>
                                    @foreach($post->categories as $category)
                                        @if($loop->last)
                                            {{ $category->title }}
                                        @else
                                            {{ $category->title }},
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <form action="{{ route('agent-posts.destroy', $post) }}" method="post">
                                        <a href="{{ route('agent-posts.show', $post) }}" class="btn
                                        btn-primary">Открыть</a>
                                        <a class="btn btn-warning" href="{{ route('agent-posts.edit', $post)
                                            }}">Редактировать</a>
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить?')">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $posts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection

<style>
    .admin table img {
        max-width: 100px !important;
    }
</style>