@extends('admin.layouts.master')

@section('title', 'Пользователи')

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
                            <h2>Пользователи</h2>
                        </div>
                        <div class="col-md-5">
                            <a href="{{ route('admin-users.create') }}" class="btn btn-success">Добавить</a>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ФИО</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Действия</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td>
                                    @if($user->role == 'admin')
                                        Администратор
                                    @else
                                        Пользователь
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin-users.destroy', $user) }}" method="post">
                                        <a class="btn btn-warning" href="{{ route('admin-users.edit', $user)
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
                </div>
            </div>
        </div>
    </div>

@endsection
