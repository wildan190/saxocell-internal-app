@extends('layouts.app')

@section('title', 'Products - ' . config('app.name', 'Laravel'))

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <div class="breadcrumb-separator">
        <i data-feather="chevron-right"></i>
    </div>
    <div class="breadcrumb-item">
        <i data-feather="package"></i>
        <a href="{{ route('products.index') }}">Products</a>
    </div>
    <div class="breadcrumb-separator">
        <i data-feather="chevron-right"></i>
    </div>
    <div class="breadcrumb-item active">
        <span>{{ $title ?? 'Index' }}</span>
    </div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">{{ $title ?? 'Products' }}</h1>
        <p class="page-subtitle">{{ $subtitle ?? 'Manage your product catalog' }}</p>
    </div>

    @yield('page-content')
</div>
@endsection