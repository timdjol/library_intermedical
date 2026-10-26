@extends('admin.layouts.master')

@section('title', 'Категория ' . $category->title)

@section('content')

    <div class="page admin documents">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <h1>{{ $category->title }}</h1>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url()->previous() }}" class="btn btn-warning">Назад</a>
                        </div>
                    </div>
                    @if(!$cats->isEmpty())
                        <ul>
                            @foreach($cats as $cat)
                                @php
                                    $agent_document = \App\Models\Document::where('id', $cat->document_id)->first();
                                @endphp
                                @isset($agent_document->id)
                                    <li><a href="{{ route('agent-documents.show', $agent_document->id) }}"><img src="{{
                           route('index')}}/img/file.svg" alt=""> {{ $agent_document->title }}</a></li>
                                @endisset
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-danger">Документы не найдены</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

