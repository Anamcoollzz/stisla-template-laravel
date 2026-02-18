@extends('layouts.app')
@section('title', 'API Key')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>API Key Management</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item">API Key</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">Manage Your API Key</h2>
        <p class="section-lead">Keep your API key secure. Do not share it with anyone.</p>

        <div class="row">
          <div class="col-12">
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

            <div class="card">
              <div class="card-header">
                <h4>Your Personal API Key</h4>
              </div>
              <div class="card-body">
                @if ($user->api_key)
                  <div class="form-group">
                    <label>Current API Key</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" value="{{ $user->api_key }}" id="apiKey" readonly>
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="copyApiKey()">
                          <i class="fas fa-copy"></i> Copy
                        </button>
                      </div>
                    </div>
                    <small class="form-text text-muted">Last generated: {{ $user->updated_at->diffForHumans() }}</small>
                  </div>

                  <div class="mt-4 d-flex">
                    <form action="{{ route('api-key.regenerate') }}" method="POST" id="regenerateForm" class="mr-2">
                      @csrf
                      <button type="button" class="btn btn-warning" onclick="confirmRegenerate()">
                        <i class="fas fa-redo"></i> Regenerate API Key
                      </button>
                    </form>
                    <form action="{{ route('api-key.delete') }}" method="POST" id="deleteForm">
                      @csrf
                      <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete API Key
                      </button>
                    </form>
                  </div>
                  <p class="mt-2 text-danger small">
                    <i class="fas fa-exclamation-triangle"></i> Warning: Regenerating or deleting your API key will immediately invalidate the current one.
                  </p>
              </div>
            @else
              <div class="empty-state" data-height="400">
                <div class="empty-state-icon">
                  <i class="fas fa-key"></i>
                </div>
                <h2>No API Key Generated</h2>
                <p class="lead">
                  You haven't generated an API key yet. Click the button below to generate one.
                </p>
                <form action="{{ route('api-key.generate') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-primary mt-4">Generate API Key</button>
                </form>
              </div>
              @endif
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
    function copyApiKey() {
      var copyText = document.getElementById("apiKey");
      copyText.select();
      copyText.setSelectionRange(0, 99999); /* For mobile devices */
      navigator.clipboard.writeText(copyText.value);

      Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'API Key copied to clipboard',
        showConfirmButton: false,
        timer: 1500
      });
    }

    function confirmRegenerate() {
      Swal.fire({
        title: 'Are you sure?',
        text: "Your current API key will be invalidated!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, regenerate it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('regenerateForm').submit();
        }
      })
    }

    function confirmDelete() {
      Swal.fire({
        title: 'Delete API Key?',
        text: "You will not be able to use the API until you generate a new key!",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('deleteForm').submit();
        }
      })
    }
  </script>
@endpush
