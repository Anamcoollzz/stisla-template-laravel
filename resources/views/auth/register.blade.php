@extends('layouts.app-auth')

@section('content')
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="login-brand">
            <img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h4>Register</h4>
            </div>

            <div class="card-body">
              <form method="POST" action="{{ route('register') }}">
                @csrf

                @if ($errors->any())
                  <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                      <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                      </button>
                      @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                      @endforeach
                    </div>
                  </div>
                @endif

                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                  @error('name')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                  @error('email')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password" class="d-block">Password</label>
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                  @error('password')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password_confirmation" class="d-block">Password Confirmation</label>
                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="agree" class="custom-control-input" id="agree" required>
                    <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Register
                  </button>
                </div>
              </form>
              <div class="mt-4 text-muted text-center">
                Already have an account? <a href="{{ route('login') }}">Login</a>
              </div>
            </div>
          </div>
          <div class="simple-footer">
            Copyright &copy; Stisla 2018
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
