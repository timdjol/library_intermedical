@extends('admin.layouts.master')

@section('title', 'Библиотека')

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
                        <div class="col-md-12">
                            <h1>Библиотека</h1>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Автор</th>
                            <th>Статус</th>
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
                                <td>{{ $post->user->name ?? 'Админстратор' }}</td>
                                <td>
                                    @if($post->status == 1)
                                        Включен
                                    @else
                                        Отключен
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('agent-posts.show', $post) }}" class="btn
                                        btn-primary">Открыть</a>
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