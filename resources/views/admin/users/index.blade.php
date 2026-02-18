@extends('layouts.app')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>User Management</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Admin</a></div>
          <div class="breadcrumb-item">Users</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">Manage Users & Subscriptions</h2>
        <p class="section-lead">View and manage user access levels and subscription plans.</p>

        @if (session('success'))
          <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
              <button class="close" data-dismiss="alert"><span>&times;</span></button>
              {{ session('success') }}
            </div>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
              <button class="close" data-dismiss="alert"><span>&times;</span></button>
              {{ session('error') }}
            </div>
          </div>
        @endif

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>All Users</h4>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-striped table-md">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Plan</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $user)
                        <tr>
                          <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->email }}</td>
                          <td>
                            <div class="badge badge-{{ $user->isAdmin() ? 'danger' : 'secondary' }}">
                              {{ strtoupper($user->role ?? 'user') }}
                            </div>
                          </td>
                          <td>
                            <div class="badge badge-{{ $user->plan === 'pro' ? 'primary' : 'light' }}">
                              {{ strtoupper($user->plan ?? 'free') }}
                            </div>
                          </td>
                          <td>
                            @if ($user->plan_expires_at)
                              {{ $user->plan_expires_at->translatedFormat('d F Y') }}
                              @if ($user->plan_expires_at->isPast())
                                <span class="text-danger small">(Expired)</span>
                              @endif
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            @if ($user->plan === 'pro' && $user->id !== auth()->id())
                              <form action="{{ route('admin.users.cancel', $user) }}" method="POST" class="d-inline cancel-form">
                                @csrf
                                <button type="button" class="btn btn-warning btn-sm btn-cancel-subscription" data-name="{{ $user->name }}">
                                  <i class="fas fa-times-circle"></i> Cancel Subscription
                                </button>
                              </form>
                            @elseif($user->id === auth()->id())
                              <span class="text-muted small">Current Admin</span>
                            @else
                              -
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer text-right">
                <nav class="d-inline-block">
                  {{ $users->links() }}
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
      $('.btn-cancel-subscription').click(function() {
        const userName = $(this).data('name');
        const form = $(this).closest('form');

        Swal.fire({
          title: 'Cancel Subscription?',
          text: `Are you sure you want to cancel the Pro subscription for ${userName}? They will be reverted to the Free plan immediately.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ffa426',
          cancelButtonColor: '#6777ef',
          confirmButtonText: 'Yes, cancel it!',
          cancelButtonText: 'No, keep it'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
@endpush
