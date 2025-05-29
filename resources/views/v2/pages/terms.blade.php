@extends('v2.layouts.app')
@section('title', '이용약관')
@section('content')

@php
  $html = file_get_contents(resource_path('v2/docs/terms.html'));
@endphp

<div class="container docs-container">
  <h1>이용약관</h1>
  <div class="terms-content">
    {!! $html !!}
  </div>
</div>

@endsection