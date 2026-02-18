@extends('layouts.app')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Profile Settings</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item">Edit Profile</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">Hi, {{ $user->name }}!</h2>
        <p class="section-lead">Update your profile information and account security.</p>

        <div class="row mt-sm-4">
          <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
              <div class="profile-widget-header">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                <div class="profile-widget-items">
                  <div class="profile-widget-item">
                    <div class="profile-widget-item-label">Joined</div>
                    <div class="profile-widget-item-value">{{ $user->created_at->format('M Y') }}</div>
                  </div>
                </div>
              </div>
              <div class="profile-widget-description">
                <div class="profile-widget-name">{{ $user->name }} <div class="text-muted d-inline font-weight-normal">
                    <div class="slash"></div> {{ $user->email }}
                  </div>
                </div>
                Akun ini terdaftar sebagai anggota platform <b>Game ID Checker</b>. Gunakan API Key Anda untuk integrasi layanan cek ID game ke dalam sistem Anda.
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
              <form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="card-header">
                  <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                  @if (session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        {{ session('success') }}
                      </div>
                    </div>
                  @endif

                  <div class="row">
                    <div class="form-group col-md-12 col-12">
                      <label>Full Name</label>
                      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required="">
                      @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12 col-12">
                      <label>Email Address</label>
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required="">
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <hr>
                  <p class="text-muted">Kosongkan jika tidak ingin mengganti password.</p>
                  <div class="row">
                    <div class="form-group col-md-6 col-12">
                      <label>New Password</label>
                      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6 col-12">
                      <label>Confirm Password</label>
                      <input type="password" name="password_confirmation" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
