@extends('v2.layouts.app')
@section('title', $title)
@section('content')

<div class="container docs-container">
  <h1>{{ $title }}</h1>
  <div>
    {!! $html !!}
  </div>
</div>

@endsection