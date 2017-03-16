@extends('layouts.app')

@section('content')
    <form role="form" method="POST" action="/login">
        {{ csrf_field() }}
        <input type="email" name="email" placeholder="email">
        <input type="password" name="password" placeholder="password">
        <button type="submit">Submit</button>
    </form>
@endsection
