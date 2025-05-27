@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <div class="text-center">
        <h1>Welcome to MyApp</h1>
        <p>This is the home page of my Laravel application.</p>
        <a href="{{ url('/about') }}" class="btn btn-primary">Learn More</a>
    </div>
@endsection
