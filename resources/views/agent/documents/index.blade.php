@extends('admin.layouts.master')

@section('title', 'Документы')

@section('content')

    <div class="page admin documents">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <h1>Документы</h1>
                    <ul>
                        @foreach($categories as $category)
                            <li><a href="{{ route('categories.show', $category) }}"><img src="{{ route('index')
                            }}/img/folder.svg" alt="">{{ $category->title}}</a>
                                <ul>
                                    @foreach($category->children as $subcategory)
                                        <li><a href="{{ route('categories.show', $subcategory) }}"><img src="{{
                                            route('index')}}/img/folder.svg" alt="">{{ $subcategory->title}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection