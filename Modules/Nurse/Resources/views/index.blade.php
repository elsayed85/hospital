@extends('nurse::layouts.master')

@section('content')
    <h1>Hello {{ current_nurse()->name }}</h1>

    <p>
        This view is loaded from module: {!! config('nurse.name') !!}
    </p>
@endsection
