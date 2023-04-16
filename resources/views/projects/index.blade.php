@extends('layouts.front')

@section('content')
@foreach($projects as $project)
    {{ $project->title }}
@endforeach

{{ $projects->onEachSide(2)->links() }}
@endsection