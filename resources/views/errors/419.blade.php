@extends('errors.minimal')

@section('title', '419 - Page Expired')
@section('code', '419')
@section('message', 'Page Expired')
@section('description', 'The page you’re looking for has expired.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
