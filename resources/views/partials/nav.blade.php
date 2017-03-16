@if (!Auth::guest())
    <span>{{ $user->name }}</span>
    <a href="/">dashboard</a>
    <a href="/logout">logout</a>
@endif