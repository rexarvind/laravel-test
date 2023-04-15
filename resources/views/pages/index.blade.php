@extends('layouts.front')

@section('title', 'Hello')
@section('description', 'Hello 2')

@push('css')
<style>body {font-family: sans-serif;}</style>
@endpush


@section('content')
helo
@endsection

@push('js')
<script>
    console.log(1)
</script>
@endpush
