@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', 'Server Error')
@section('description', 'Something went wrong on our servers.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
