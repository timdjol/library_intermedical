@extends('admin.layouts.master')

@isset($agent_post)
    @section('title', 'Редактировать  ' . $agent_post->title)
@else
    @section('title', 'Добавить книгу')
@endisset

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    @isset($agent_post)
                        <h1>Редактировать {{ $agent_post->title }}</h1>
                    @else
                        <h1>Добавить книгу</h1>
                    @endisset
                    <form method="post" enctype="multipart/form-data"
                          @isset($agent_post)
                              action="{{ route('agent-posts.update', $agent_post) }}"
                          @else
                              action="{{ route('agent-posts.store') }}"
                            @endisset
                    >
                        @isset($agent_post)
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $agent_post->user_id }}">
                        @else
                            <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                        @endisset

                        <input type="hidden" name="user_ip" value="{{ request()->getClientIp() }}">
                        @isset($agent_post)
                            <input type="hidden" name="old_title" value="{{ $agent_post->title }}">
                        @else
                            <input type="hidden" name="title_event" value="Создана книга">
                        @endif
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label for="">Заголовок</label>
                            <input type="text" name="title" value="{{ old('title', isset($agent_post) ? $agent_post->title :
                             null) }}">
                        </div>
                        <div class="form-group">
                            @error('categories_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Выберите категорию</label>
                            <select class="selectpicker" id="category" multiple name="categories_id[]"
                                    data-live-search="true" placeholder="Не выбрано">
                                @isset($admin_post)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($admin_post->categories->contains($category->id)) selected @endif
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
                            @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Описание</label>
                            <textarea name="description" id="editor" rows="6">{{ old('description', isset
                                ($agent_post) ? $agent_post->description : null) }}</textarea>
                        </div>
                        <script src="https://cdn.tiny.cloud/1/yxonqgmruy7kchzsv4uizqanbapq2uta96cs0p4y91ov9iod/tinymce/6/tinymce.min.js"
                                referrerpolicy="origin"></script>
                        <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .catch(error => {
                                    console.error(error);
                                });
                        </script>
                        <div class="form-group">
                            @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label for="">Изображение</label>
                            @isset($agent_post)
                                <img src="{{ Storage::url($agent_post->image) }}" alt="">
                            @endisset
                            <input type="file" name="image">
                        </div>
                        <div class="form-group">
                            <label for="">Статус</label>
                            <select name="status" id="">
                                @isset($agent_post)
                                    @if($agent_post->status == 1)
                                        <option value="{{ $agent_post->status }}">Включен</option>
                                        <option value="0">Отключен</option>
                                    @else
                                        <option value="{{ $agent_post->status }}">Отключен</option>
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
                </div>
            </div>
        </div>
    </div>

    <style>
        img {
            max-width: 300px;
            display: block;
            margin-bottom: 10px;
        }
        form select {
            height: auto;
        }
    </style>

@endsection
