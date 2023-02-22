@extends('employee::layouts.master')

@section('content')
    <h1>Hello {{ auth_employee()->name }}</h1>

    <p>
        This view is loaded from module: {!! config('employee.name') !!}
    </p>
@endsection
