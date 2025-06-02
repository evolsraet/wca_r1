@extends('errors.minimal')

@section('title', '401 - Unauthorized')
@section('code', '401')
@section('message', 'Unauthorized')
@section('description', 'You are not authorized to access this page.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
