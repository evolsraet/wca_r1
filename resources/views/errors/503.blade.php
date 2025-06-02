@extends('errors.minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', 'Service Unavailable')
@section('description', 'We’re down for maintenance. Please try again later.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
