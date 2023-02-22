@extends('doctor::layouts.master')

@section('content')
    <h1>Hello {{ current_doctor()->name }}</h1>

    <p>
        This view is loaded from module: {!! config('doctor.name') !!}
    </p>
@endsection
