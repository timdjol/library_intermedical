@extends('admin.layouts.master')

@section('title', 'Документ ' . $agent_document->title)

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <h1>{{ $agent_document->title }}</h1>
                    @include('documents.preview', ['document' => $agent_document])
                </div>
            </div>
        </div>
    </div>

@endsection

