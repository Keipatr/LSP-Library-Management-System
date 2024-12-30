@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <h1>Welcome to the Library</h1>
        @if(Auth::check())
            <p>Hello, {{ Auth::user()->name }}! You are logged in.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        @else
            <p>You are a guest. Please <a href="{{ route('login') }}">Login</a>.</p>
        @endif
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
@endsection
