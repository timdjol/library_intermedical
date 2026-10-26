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
                    <div class="frame_btn">
                        @if($ext === 'docx')
                            <iframe width="100%" height="600px" src="https://view.officeapps.live.com/op/embed.aspx?src={{ Storage::url($agent_document->path) }}">
                        @else
                            <iframe src="{{ Storage::url($agent_document->path) }}#toolbar=0" frameborder="0" width="100%"
                            height="600px"></iframe>
                        @endif
                            
                        
</iframe>
                        <div class="iframe"></div>
                        
                    </div>
                    
                    @if($agent_document->access === 1)
                            <a target="_blank" href="{{ Storage::url($agent_document->path) }}" class="btn btn-success">Скачать
                                документ</a>
                        @endif
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
    document.oncontextmenu=function(e){return false};
    </script>

<!-- <style>
    .frame_btn{
        position: relative;
    }
    .frame_btn .iframe{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: transparent;
        z-index: 5;
    }
</style> -->


@endsection

