@extends('errors.minimal')

@section('title', '402 - Payment Required')
@section('code', '402')
@section('message', 'Payment Required')
@section('description', 'You need to pay to access this page.')
@section('button')
    <a href="{{ route('home') }}" class="btn-back-home">Go Home</a>
@endsection
