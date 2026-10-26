@extends('admin.layouts.master')

@section('title', 'Документ ' . $admin_document->title)

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    @include('documents.preview', ['document' => $admin_document])
                </div>
            </div>
        </div>
    </div>

@endsection

