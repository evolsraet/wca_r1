@extends('v2.layouts.app')
@section('title', '개인정보 처리방침')
@section('content')

@php
  $html = file_get_contents(resource_path('v2/docs/privacy.html'));
@endphp

<div class="container docs-container">
  <h1>개인정보 처리방침</h1>
  <div class="privacy-content">
    {!! $html !!}
  </div>
</div>

@endsection