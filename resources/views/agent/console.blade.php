@extends('admin/layouts.master')

@section('title', 'Консоль')

@section('content')

    <div class="page admin dashboard">
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
                    {{--                    <h1>Добро пожаловать {{ $user->name }}</h1>--}}
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="dashboard-item">
                                <div class="num">{{ count($posts) }}</div>
                                <h5>Количество <br> записей</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dashboard-item">
                                <div class="num">{{ count($categories) }}</div>
                                <h5>Количество <br> категорий</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


