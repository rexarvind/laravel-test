@extends('layouts.front')

@section('title', '404 Error Page Not Found')
@section('description', '404 Error Page Not Found')

@section('content')
<section>
    <h1>Page Not Found</h1>
    <p>{{ $exception->getMessage() }}</p>
</section>
@endsection
