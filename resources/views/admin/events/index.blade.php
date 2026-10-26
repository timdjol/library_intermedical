@extends('admin.layouts.master')

@section('title', 'История событий')

@section('content')

    <div class="page admin">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>История событий</h2>
                            @if(session()->has('success'))
                                <p class="alert alert-success">{{ session()->get('success') }}</p>
                            @endif
                            @if(session()->has('warning'))
                                <p class="alert alert-warning">{{ session()->get('warning') }}</p>
                            @endif
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>IP адрес</th>
                            <th>Событие</th>
                            <th>Дата/время</th>
                            <th>Действия</th>
                        </tr>
                        @foreach($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>
                                    @php
                                    $user = \App\Models\User::where('id', $event->user_id)->firstOrFail();
                                    @endphp
                                    {{ $user->name }}
                                </td>
                                <td>{{ $event->user_ip }}</td>
                                <td>{{ $event->title_event }}</td>
                                <td>{{ $event->showDate() }}</td>
                                <td>
                                    <form action="{{ route('admin-events.destroy', $event) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить?')">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $events->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection
