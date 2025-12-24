@extends('layouts.auth')

@section('title', 'New Password')
@section('subtitle', 'Choose a strong password')
@section('left-title', 'Secure Your Account')
@section('left-subtitle', 'Create a strong, unique password to keep your account secure. Make sure it\'s something you can remember but others can\'t guess.')

@section('content')
<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus class="form-control" placeholder="Enter your email address">
    </div>

    <div class="form-group">
        <label for="password" class="form-label">New Password</label>
        <input id="password" type="password" name="password" required class="form-control" placeholder="Enter your new password">
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control" placeholder="Confirm your new password">
    </div>

    <button type="submit" class="btn-primary">
        Reset Password
    </button>

    <div class="auth-links">
        <a href="{{ route('login') }}">Back to sign in</a>
    </div>
</form>
@endsection