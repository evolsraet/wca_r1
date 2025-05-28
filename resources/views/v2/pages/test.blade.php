@extends('v2.layouts.app')

@section('title', 'test')

@section('content')

<div class="container" x-data="{ message: 'Hello, Alpine.js!' }">
    <h1 x-text="message"></h1>
    <button @click="message = 'You clicked the button!'">Click me</button>
</div>
    
@endsection