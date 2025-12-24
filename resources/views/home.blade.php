@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name', 'Laravel'))

@section('page-title', 'Dashboard')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <span>Home</span>
    </div>
    <div class="breadcrumb-separator">
        <i data-feather="chevron-right"></i>
    </div>
    <div class="breadcrumb-item active">
        <span>Dashboard</span>
    </div>
</nav>
@endsection

@section('content')
<div class="welcome-section">
    <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}!</h1>
    <p class="welcome-subtitle">Here's what's happening with your account today.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Products</div>
        <div class="stat-value">1,234</div>
        <div class="stat-description">Active products in catalog</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Revenue</div>
        <div class="stat-value">$12,345</div>
        <div class="stat-description">This month's earnings</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Orders</div>
        <div class="stat-value">567</div>
        <div class="stat-description">Completed this week</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Growth</div>
        <div class="stat-value">+23%</div>
        <div class="stat-description">Compared to last month</div>
    </div>
</div>

<div class="recent-activity">
    <h2 class="activity-title">Recent Activity</h2>
    <div class="activity-item">
        <div class="activity-icon">L</div>
        <div class="activity-content">
            <div class="activity-text">You logged in to your account</div>
            <div class="activity-time">2 minutes ago</div>
        </div>
    </div>
    <div class="activity-item">
        <div class="activity-icon">P</div>
        <div class="activity-content">
            <div class="activity-text">Product catalog updated</div>
            <div class="activity-time">1 hour ago</div>
        </div>
    </div>
    <div class="activity-item">
        <div class="activity-icon">S</div>
        <div class="activity-content">
            <div class="activity-text">Security settings changed</div>
            <div class="activity-time">2 days ago</div>
        </div>
    </div>
</div>

<style>
    .welcome-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .welcome-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        margin-bottom: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .stat-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .stat-description {
        font-size: 0.875rem;
        color: #64748b;
    }

    .recent-activity {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .activity-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: #3b82f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-weight: 600;
    }

    .activity-content {
        flex: 1;
    }

    .activity-text {
        font-size: 0.875rem;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.75rem;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .welcome-section {
            padding: 2rem;
        }

        .welcome-title {
            font-size: 1.75rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .welcome-title {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }
    }
</style>
@endsection