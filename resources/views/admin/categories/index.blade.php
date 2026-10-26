@extends('admin.layouts.master')

@section('title', 'Категории')

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
                    <div class="row aic">
                        <div class="col-md-7">
                            <h1>Категории</h1>
                        </div>
                        <div class="col-md-5">
                            <a class="btn btn-success" href="{{ route('admin-categories.create') }}">Добавить</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Родительская категория</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->title }}</td>
                                <td>{{ $category->parent->title ?? '' }}</td>

                                <td>
                                    <form action="{{ route('admin-categories.destroy', $category) }}" method="post">
                                        <a class="btn btn-warning" href="{{ route('admin-categories.edit', $category)
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
                    {{ $categories->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection
