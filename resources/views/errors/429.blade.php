@extends('errors.minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', 'Too Many Requests')
@section('description', 'You have made too many requests to our servers.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
