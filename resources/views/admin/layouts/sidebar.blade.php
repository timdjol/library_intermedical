<div class="sidebar">
    <ul>
        @php
            $user_role = \Illuminate\Support\Facades\Auth::user()->role;
        @endphp
        @if($user_role == 'admin')
            <li @routeactive('admin.dashboard')><a href="{{ route('admin.dashboard')}}">Консоль</a></li>
            <li @routeactive('admin-cat*')><a href="{{ route('admin-categories.index')}}">Категории</a></li>
            <li @routeactive('admin-post*')><a href="{{ route('admin-posts.index')}}">Библиотека</a></li>
            <li @routeactive('admin-doc*')><a href="{{ route('admin-documents.index')}}">Документы</a></li>
            <li @routeactive('admin-user*')><a href="{{ route('admin-users.index')}}">Пользователи</a></li>
            <li @routeactive('prof*')><a href="{{ route('profile.edit') }}">Профиль</a></li>
            <li @routeactive('admin-event*')><a href="{{ route('admin-events.index')}}">История событий</a></li>
        @else
            <li @routeactive('agent.console')><a href="{{ route('agent.console')}}">Консоль</a></li>
            <li @routeactive('agent-artic*')><a href="{{ route('agent-articles.index')}}">Библиотека</a></li>
            <li @routeactive('agent-doc*') @routeactive('cat*')><a href="{{ route('agent-documents.index')
            }}">Документы</a></li>
            <li @routeactive('agent-post*')><a href="{{ route('agent-posts.index')}}">Мои записи</a></li>
            <li @routeactive('prof*')><a href="{{ route('profile.edit') }}">Профиль</a></li>
        @endif
    </ul>
</div>
