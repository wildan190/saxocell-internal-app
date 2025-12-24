@extends('layouts.auth')

@section('title', 'Sign In')
@section('subtitle', 'Welcome back! Please sign in to your account')
@section('left-title', 'Secure Access Portal')
@section('left-subtitle', 'Access your enterprise dashboard with confidence. Our advanced security measures ensure your data stays protected while providing seamless authentication.')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="Enter your email address">
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" required class="form-control" placeholder="Enter your password">
    </div>

    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Remember me</label>
        </div>
    </div>

    <button type="submit" class="btn-primary">
        Sign In
    </button>

    <div class="auth-links">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        @endif
        <span class="divider">•</span>
        <a href="{{ route('register') }}">Create new account</a>
    </div>
</form>
@endsection