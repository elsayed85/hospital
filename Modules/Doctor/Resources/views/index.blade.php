@extends('doctor::layouts.master')

@section('content')
    <h1>Hello {{ auth_doctor()->name }}</h1>

    <p>
        This view is loaded from module: {!! config('doctor.name') !!}
    </p>
@endsection
