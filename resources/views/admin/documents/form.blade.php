@extends('admin.layouts.master')

@isset($admin_document)
    @section('title', 'Редактировать  ' . $admin_document->title)
@else
    @section('title', 'Добавить документ')
@endisset

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-6">
                    @isset($admin_document)
                        <h1>Редактировать {{ $admin_document->title }}</h1>
                    @else
                        <h1>Добавить документ</h1>
                    @endisset
                    <form method="post" enctype="multipart/form-data"
                          @isset($admin_document)
                              action="{{ route('admin-documents.update', $admin_document) }}"
                          @else
                              action="{{ route('admin-documents.store') }}"
                            @endisset
                    >
                        @isset($admin_document)
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $admin_document->user_id }}">
                        @else
                            <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                        @endisset

                        <input type="hidden" name="user_ip" value="{{ request()->getClientIp() }}">
                        @isset($admin_document)
                            <input type="hidden" name="old_title" value="{{ $admin_document->title }}">
                        @else
                            <input type="hidden" name="title_event" value="Создан документ">
                        @endif
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="">Заголовок</label>
                            <input type="text" name="title" value="{{ old('title', isset($admin_document) ? $admin_document->title :
                             null) }}">
                        </div>
                        <div class="form-group">
                            @error('category_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Выберите категорию</label>
                            <select class="selectpicker" id="category" multiple name="categories_id[]"
                                    data-live-search="true" placeholder="Не выбрано">
                                @isset($admin_document)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($admin_document->categories->contains($category->id)) selected
                                                @endif
                                        >{{ $category->title }}</option>
                                    @endforeach
                                @else
                                    <option value="">Выбрать</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (collect(old('categories_id'))->contains
                                        ($category->id)) ? 'selected': '' }}>{{ $category->title }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            @error('path')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Документ</label>
                            <input type="file" name="path">
                        </div>
                        <div class="form-group">
                            <label for="">Доступ к скачиванию</label>
                            <select name="access" id="">
                                @isset($admin_document)
                                    @if($admin_document->access == 1)
                                        <option value="{{ $admin_document->access }}">Доступен</option>
                                        <option value="0">Недоступен</option>
                                    @else
                                        <option value="{{ $admin_document->access }}">Недоступен</option>
                                        <option value="1">Доступен</option>
                                    @endif
                                @else
                                    <option value="1">Доступен</option>
                                    <option value="0">Недоступен</option>
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Статус</label>
                            <select name="status" id="">
                                @isset($admin_document)
                                    @if($admin_document->status == 1)
                                        <option value="{{ $admin_document->status }}">Включен</option>
                                        <option value="0">Отключен</option>
                                    @else
                                        <option value="{{ $admin_document->status }}">Отключен</option>
                                        <option value="1">Включен</option>
                                    @endif
                                @else
                                    <option value="1">Включен</option>
                                    <option value="0">Отключен</option>
                                @endisset
                            </select>
                        </div>
                        @csrf
                        <button class="btn btn-primary">Отправить</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Отмена</a>
                    </form>
                    @isset($admin_document)
                        <iframe src="{{ Storage::url($admin_document->path) }}"
                                width="100%"
                                height="500px">
                    @endisset
                </div>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

    <style>
        form select#category {
            height: auto;
        }

        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 100%;
        }

        a {
            text-decoration: none;
        }
    </style>

@endsection
