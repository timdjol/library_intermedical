@php
    $extension = strtolower(pathinfo($document->path ?? '', PATHINFO_EXTENSION));
    $canDownload = auth()->user()->role === 'admin' || (int) $document->access === 1;
@endphp

@if(in_array($extension, ['pdf', 'txt'], true))
    <iframe src="{{ route('documents.file', $document) }}" frameborder="0" width="100%" height="600px"></iframe>
@else
    <p>Предпросмотр этого формата отключён, чтобы файл не уходил на сторонний сервис.</p>
@endif

@if($canDownload)
    <a href="{{ route('documents.file', ['document' => $document, 'download' => 1]) }}" class="btn btn-success">Скачать документ</a>
@endif
