@extends('layouts.app')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>API Playground</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item">API Tester</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">Test Your API Integration</h2>
        <p class="section-lead text-danger">
          <i class="fas fa-exclamation-circle"></i> <strong>Note:</strong> Requests made here use your real API Key and <strong>will</strong> count towards your daily hit limit ({{ auth()->user()->getDailyLimit() }}/day).
        </p>

        <div class="row">
          <div class="col-12 col-md-5 col-lg-5">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Request Configuration</h4>
              </div>
              <div class="card-body">
                <form id="apiTestForm">
                  <div class="form-group">
                    <label>Endpoint URL</label>
                    <input type="text" class="form-control" value="{{ url('/api/check-id') }}" readonly>
                  </div>
                  <div class="form-group">
                    <label>API Key (X-API-KEY)</label>
                    <input type="text" class="form-control" id="formApiKey" value="{{ auth()->user()->api_key }}" placeholder="Your API Key">
                    <small class="text-muted">Will be sent in the header.</small>
                  </div>
                  <div class="form-group">
                    <label>Game</label>
                    <select class="form-control" id="formGame">
                      @foreach ($games as $game)
                        <option value="{{ $game['code'] }}">{{ $game['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Player ID</label>
                    <input type="text" class="form-control" id="formId" placeholder="e.g. 12345678">
                  </div>
                  <div class="form-group d-none" id="zoneIdGroup">
                    <label>Zone ID</label>
                    <input type="text" class="form-control" id="formZoneId" placeholder="e.g. 1234">
                  </div>
                  <button type="submit" class="btn btn-primary btn-block btn-lg" id="btnSend">
                    <i class="fas fa-paper-plane"></i> Send Request
                  </button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-7 col-lg-7">
            <div class="card card-dark">
              <div class="card-header bg-dark text-white">
                <h4>Response</h4>
                <div class="card-header-action">
                  <span class="badge badge-secondary" id="respStatus">Waiting...</span>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="response-viewer">
                  <pre><code class="language-json" id="respBody">{ }</code></pre>
                </div>
              </div>
              <div class="card-footer bg-whitesmoke">
                <div class="row text-center">
                  <div class="col-6 border-right">
                    <div class="font-weight-bold text-muted small">HITS TODAY</div>
                    <div class="h5 mb-0" id="statHits">-</div>
                  </div>
                  <div class="col-6">
                    <div class="font-weight-bold text-muted small">REMAINING</div>
                    <div class="h5 mb-0 text-primary" id="statRemaining">-</div>
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

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
  <style>
    .response-viewer {
      background: #2d2d2d;
      max-height: 500px;
      overflow-y: auto;
      border-radius: 0;
    }

    .response-viewer pre {
      margin: 0;
      padding: 20px;
      background: transparent !important;
    }

    .card-dark .card-header {
      border-bottom: 1px solid #444;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-json.min.js"></script>
  <script>
    $(document).ready(function() {
      // Show/hide Zone ID for ML
      $('#formGame').on('change', function() {
        if ($(this).val() === 'ml') {
          $('#zoneIdGroup').removeClass('d-none');
        } else {
          $('#zoneIdGroup').addClass('d-none');
        }
      });

      $('#apiTestForm').on('submit', function(e) {
        e.preventDefault();

        const apiKey = $('#formApiKey').val();
        const game = $('#formGame').val();
        const id = $('#formId').val();
        const zoneid = $('#formZoneId').val();

        if (!apiKey) {
          alert('API Key is required');
          return;
        }

        $('#btnSend').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        $('#respStatus').removeClass('badge-secondary badge-success badge-danger').addClass('badge-warning').text('Processing...');

        $.ajax({
          url: '{{ url('/api/check-id') }}',
          method: 'POST',
          headers: {
            'X-API-KEY': apiKey,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          data: JSON.stringify({
            game: game,
            id: id,
            zoneid: zoneid
          }),
          success: function(response, textStatus, xhr) {
            updateUI(response, xhr.status, 'badge-success');
          },
          error: function(xhr) {
            const response = xhr.responseJSON || {
              status: 'error',
              message: 'Unknown error occurred'
            };
            updateUI(response, xhr.status, 'badge-danger');
          },
          complete: function() {
            $('#btnSend').attr('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Request');
          }
        });
      });

      function updateUI(response, code, badgeClass) {
        $('#respStatus').removeClass('badge-warning').addClass(badgeClass).text('HTTP ' + code);

        // Pretty print JSON
        const jsonStr = JSON.stringify(response, null, 2);
        $('#respBody').text(jsonStr);
        Prism.highlightElement($('#respBody')[0]);

        // Update stats if available
        if (response.hits !== undefined) {
          $('#statHits').text(response.hits);
          $('#statRemaining').text(response.remaining);
        }
      }
    });
  </script>
@endpush
