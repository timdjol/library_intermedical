@extends('admin.layouts.master')

@section('title', 'Книга ' . $agent_post->title)

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                    @if($agent_post->imageUrl())
                    <img src="{{ $agent_post->imageUrl() }}" alt="">
                    @endif
                    <h4>
                        @foreach($agent_post->categories as $category)
                            @if($loop->last)
                                {{ $category->title }}
                            @else
                                {{ $category->title }},
                            @endif
                        @endforeach
                    </h4>
                </div>
                <div class="col-md-9">
                    <h1>{{ $agent_post->title }}</h1>
                    {!! $agent_post->description !!}
                </div>
            </div>
        </div>
    </div>

@endsection

