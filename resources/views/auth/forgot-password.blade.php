@extends('admin/layouts.master')

@section('title', 'Сброс пароля')

@section('content')

    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Забыли пароль? Нет проблем. Просто сообщите нам свой адрес электронной почты, и мы вышлем вам
                        ссылку для сброса пароля, которая позволит вам выбрать новый.
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')"/>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="btn btn-primary">Сброс пароля
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection