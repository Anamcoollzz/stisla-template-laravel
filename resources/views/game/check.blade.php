@extends('layouts.app')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Game ID Checker</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item">Game ID Checker</div>
        </div>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12 col-md-8 offset-md-2">
            <div class="card">
              <div class="card-header">
                <h4>Check Game ID / Username</h4>
              </div>
              <div class="card-body">
                <div class="alert alert-light border-info">
                  @php
                    $hits = Cache::get('user_hits:' . Auth::id() . ':' . date('Y-m-d'), 0);
                    $remaining = 50 - $hits;
                  @endphp
                  <i class="fas fa-info-circle text-info"></i> Limit harian: <strong><span id="currentHits">{{ $hits }}</span>/50</strong> (Tersisa: <span id="remainingHits">{{ $remaining }}</span> kali)
                </div>
                <form id="checkIdForm">
                  @csrf

                  <div class="form-group">
                    <label for="game">Select Game</label>
                    <select class="form-control" id="game" name="game" required>
                      <option value="">-- Select Game --</option>
                      <option value="ml">Mobile Legends</option>
                      <option value="freefire">Free Fire</option>
                      <option value="codm">Call of Duty Mobile</option>
                      <option value="genshin">Genshin Impact</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="id">User ID</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter User ID" required>
                  </div>

                  <div class="form-group" id="zoneidGroup" style="display: none;">
                    <label for="zoneid">Zone ID</label>
                    <input type="text" class="form-control" id="zoneid" name="zoneid" placeholder="Enter Zone ID (for Mobile Legends)">
                    <small class="form-text text-muted">Zone ID is required for Mobile Legends</small>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="checkBtn">
                      <i class="fas fa-search"></i> Check ID
                    </button>
                  </div>
                </form>

                <!-- Result Section -->
                <div id="resultSection" style="display: none;">
                  <hr>
                  <div class="alert alert-success" id="successResult" style="display: none;">
                    <h5><i class="fas fa-check-circle"></i> Player Found!</h5>
                    <p class="mb-0"><strong>Username:</strong> <span id="playerName"></span></p>
                  </div>
                  <div class="alert alert-danger" id="errorResult" style="display: none;">
                    <h5><i class="fas fa-times-circle"></i> Error</h5>
                    <p class="mb-0" id="errorMessage"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const gameSelect = document.getElementById('game');
        const zoneidGroup = document.getElementById('zoneidGroup');
        const zoneidInput = document.getElementById('zoneid');
        const checkForm = document.getElementById('checkIdForm');
        const checkBtn = document.getElementById('checkBtn');
        const resultSection = document.getElementById('resultSection');
        const successResult = document.getElementById('successResult');
        const errorResult = document.getElementById('errorResult');
        const playerName = document.getElementById('playerName');
        const errorMessage = document.getElementById('errorMessage');
        const currentHitsSpan = document.getElementById('currentHits');
        const remainingHitsSpan = document.getElementById('remainingHits');

        // Show/hide Zone ID field based on game selection
        gameSelect.addEventListener('change', function() {
          if (this.value === 'ml') {
            zoneidGroup.style.display = 'block';
            zoneidInput.setAttribute('required', 'required');
          } else {
            zoneidGroup.style.display = 'none';
            zoneidInput.removeAttribute('required');
            zoneidInput.value = '';
          }
        });

        // Handle form submission
        checkForm.addEventListener('submit', function(e) {
          e.preventDefault();

          // Reset results
          resultSection.style.display = 'none';
          successResult.style.display = 'none';
          errorResult.style.display = 'none';

          // Show loading state
          const originalText = checkBtn.innerHTML;
          checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
          checkBtn.disabled = true;

          // Get form data
          const formData = new FormData();
          formData.append('game', document.getElementById('game').value);
          formData.append('id', document.getElementById('id').value);
          formData.append('zoneid', document.getElementById('zoneid').value);

          // Make AJAX request
          fetch('{{ route('game.check-id') }}', {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
              },
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              resultSection.style.display = 'block';

              // Update hit counter dynamically (always update if data is available)
              if (data.hits !== undefined) {
                currentHitsSpan.textContent = data.hits;
                remainingHitsSpan.textContent = data.remaining;
              }

              if (data.data && data.data !== 'error') {
                // Handle both string and object responses
                let username = data.data;

                // If data is an object, extract the username property
                if (typeof data.data === 'object') {
                  username = data.data.username || data.data.name || data.data.nickname || JSON.stringify(data.data);
                }

                playerName.textContent = username;
                successResult.style.display = 'block';
              } else {
                errorMessage.textContent = data.message || 'Player not found or invalid ID';
                errorResult.style.display = 'block';
              }
            })
            .catch(error => {
              resultSection.style.display = 'block';
              errorMessage.textContent = 'An error occurred while checking the ID';
              errorResult.style.display = 'block';
              console.error('Error:', error);
            })
            .finally(() => {
              checkBtn.innerHTML = originalText;
              checkBtn.disabled = false;
            });
        });
      });
    </script>
  @endpush
@endsection
