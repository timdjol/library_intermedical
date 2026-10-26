@extends('admin.layouts.master')

@isset($admin_user)
    @section('title', 'Редактировать пользователя')
@else
    @section('title', 'Добавить пользователя')
@endisset

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    @isset($admin_user)
                        <h1>Редактировать пользователя</h1>
                    @else
                        <h1>Добавить пользователя</h1>
                    @endisset
                    <form method="post"
                          @isset($admin_user)
                              action="{{ route('admin-users.update', $admin_user) }}"
                          @else
                              action="{{ route('admin-users.store') }}"
                            @endisset
                    >
                        @isset($admin_user)
                            @method('PUT')
                        @endisset
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="">ФИО</label>
                            <input type="text" name="name"
                                   value="{{ old('name', isset($admin_user) ? $admin_user->name : null) }}">
                        </div>

                        <div class="form-group">
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Email</label>
                            <input type="text" name="email" value="{{ old('value', isset($admin_user) ? $admin_user->email :
                             null) }}">
                        </div>

                        <div class="form-group">
                            @error('role')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Роль пользователя</label>
                            <select name="role">
                                @isset($admin_user)
                                    <option value="{{ $admin_user->role }}">{{ $admin_user->role }}</option>
                                @endisset
                                <option value="agent">Пользователь</option>
                                <option value="admin">Администратор</option>
                            </select>
                        </div>
                        <div class="form-group">
                            @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Пароль</label>
                            <input type="password" name="password"
                                   value="{{ old('password', isset($admin_user) ? $admin_user->password : null) }}">
                        </div>
                        @csrf
                        <button class="btn btn-primary">Отправить</button>
                        <a href="{{url()->previous()}}" class="btn btn-danger">Отмена</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
