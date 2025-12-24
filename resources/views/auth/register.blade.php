@extends('layouts.auth')

@section('title', 'Create Account')
@section('subtitle', 'Join our platform today')
@section('left-title', 'Join Our Community')
@section('left-subtitle', 'Create your account and unlock access to powerful tools and features designed to streamline your workflow and boost productivity.')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control" placeholder="Enter your full name">
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="Enter your email address">
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" required class="form-control" placeholder="Create a strong password">
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control" placeholder="Confirm your password">
    </div>

    <button type="submit" class="btn-primary">
        Create Account
    </button>

    <div class="auth-links">
        <a href="{{ route('login') }}">Already have an account? Sign in</a>
    </div>
</form>
@endsection