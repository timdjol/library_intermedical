@extends('admin.layouts.master')

@isset($admin_category)
    @section('title', 'Редактировать категорию ' . $admin_category->title)
@else
    @section('title', 'Создать категорию')
@endisset

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    @isset($admin_category)
                        <h1>Редактировать категорию {{ $admin_category->title }}</h1>
                    @else
                        <h1>Создать категорию</h1>
                    @endisset
                    <form method="post"
                          @isset($admin_category)
                              action="{{ route('admin-categories.update', $admin_category) }}"
                          @else
                              action="{{ route('admin-categories.store') }}"
                            @endisset
                    >
                        @isset($admin_category)
                            @method('PUT')
                        @endisset
                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                        <input type="hidden" name="user_ip" value="{{ request()->getClientIp() }}">
                        @isset($admin_category)
                            <input type="hidden" name="old_title" value="{{ $admin_category->title }}">
                        @else
                            <input type="hidden" name="title_event" value="Создана категория">
                        @endif
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="">Заголовок</label>
                            <input type="text" name="title" value="{{ old('title', isset($admin_category) ? $admin_category->title :
                             null) }}">
                        </div>
                        <div class="form-group">
                            <label for="parent">Родительская категория</label>
                            <select name="parent_id" id="parent">
                                @isset($admin_category)
                                    <option value="{{ $admin_category->parent_id }}">{{ $admin_category->parent->title
                                    }}</option>
                                @endisset
                                <option value="0">--Без родительской категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @isset($admin_category)@if($admin_category->id ==
                                    $category->id)
                                        disabled @endif @endisset>{{
                                            $category->title }}</option>
                                @endforeach
                            </select>
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