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
                    <iframe src="{{ Storage::url($admin_document->path) }}#toolbar=0" frameborder="0" width="100%"
                            height="600px"></iframe>
                    @if($admin_document->access === 1)
                        <a target="_blank" href="{{ Storage::url($admin_document->path) }}" class="btn
                        btn-success">Скачать документ</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

