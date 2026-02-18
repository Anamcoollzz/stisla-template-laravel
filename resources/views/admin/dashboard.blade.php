@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card card-hero">
            <div class="card-header">
              <div class="card-icon">
                <i class="fas fa-rocket"></i>
              </div>
              <h4>Welcome, {{ Auth::user()->name }}!</h4>
              <div class="welcome-badge">
                Akun Anda:
                @if (Auth::user()->plan === 'pro' && Auth::user()->plan_expires_at && Auth::user()->plan_expires_at->isFuture())
                  <span class="badge badge-primary">PRO PLAN (Dynamic)</span>
                  <div class="mt-2 small opacity-8">
                    <i class="fas fa-calendar-alt"></i> Berlaku hingga: {{ Auth::user()->plan_expires_at->translatedFormat('d F Y') }}
                  </div>
                @else
                  <span class="badge badge-warning">FREE PLAN</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-search"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Sisa Limit Hari Ini</h4>
              </div>
              <div class="card-body">
                @php
                  $hits = Cache::get('user_hits:' . Auth::id() . ':' . date('Y-m-d'), 0);
                  $limit = Auth::user()->getDailyLimit();
                  echo $limit - $hits;
                @endphp
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="fas fa-fire"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total Hits Hari Ini</h4>
              </div>
              <div class="card-body">
                {{ Cache::get('user_hits:' . Auth::id() . ':' . date('Y-m-d'), 0) }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-key"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>API Key</h4>
              </div>
              <div class="card-body">
                @if (Auth::user()->api_key)
                  <span class="badge badge-success">Active</span>
                @else
                  <span class="badge badge-warning">None</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
