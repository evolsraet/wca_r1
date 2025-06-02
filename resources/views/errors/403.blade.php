@extends('errors.minimal')

@section('title', '403 - Forbidden')
@section('code', '403')
@section('message', 'Forbidden')
@section('description', 'You are not allowed to access this page.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
