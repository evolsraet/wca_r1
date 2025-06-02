@extends('errors.custom')

@section('title', '404 - Page not found')
@section('code', '404')
@section('message', 'Opps! Page not found.')
@section('description', 'The page you’re looking for doesn’t exist.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection