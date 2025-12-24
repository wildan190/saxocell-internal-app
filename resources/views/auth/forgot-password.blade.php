@extends('layouts.auth')

@section('title', 'Reset Password')
@section('subtitle', 'We\'ll send you a reset link')
@section('left-title', 'Password Recovery')
@section('left-subtitle', 'Don\'t worry! It happens to the best of us. Enter your email address and we\'ll send you a secure link to reset your password.')

@section('content')
<p class="form-description">
    Enter your email address and we'll send you a link to reset your password.
</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="Enter your email address">
    </div>

    <button type="submit" class="btn-primary">
        Send Reset Link
    </button>

    <div class="auth-links">
        <a href="{{ route('login') }}">Back to sign in</a>
    </div>
</form>
@endsection