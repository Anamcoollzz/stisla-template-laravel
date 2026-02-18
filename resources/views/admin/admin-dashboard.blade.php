@extends('layouts.app')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Admin Dashboard</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Admin</a></div>
          <div class="breadcrumb-item">Dashboard</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">System Overview</h2>
        <p class="section-lead">Monitoring system-wide user growth and plan distribution.</p>

        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="far fa-user"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Total Users</h4>
                </div>
                <div class="card-body">
                  {{ $stats['total_users'] }}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-crown"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Pro Plan Users</h4>
                </div>
                <div class="card-body">
                  {{ $stats['pro_users'] }}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="fas fa-user-clock"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Free Plan Users</h4>
                </div>
                <div class="card-body">
                  {{ $stats['free_users'] }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>System Status</h4>
              </div>
              <div class="card-body">
                <div class="alert alert-info">
                  <strong>Admin Notice:</strong> This panel is only accessible to users with the <code>admin</code> role. Use this area to monitor platform health and user activity.
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        Laravel Version
                        <span class="badge badge-secondary badge-pill">{{ app()->version() }}</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        PHP Version
                        <span class="badge badge-secondary badge-pill">{{ PHP_VERSION }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
