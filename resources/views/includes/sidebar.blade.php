<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.html">Game ID Checker</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html">GIC</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ Request::is('dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
      {{-- <li class="{{ Request::is('game/check') ? 'active' : '' }}"><a class="nav-link" href="{{ route('game.check') }}"><i class="fas fa-id-card"></i> <span>Game ID Checker</span></a></li> --}}
      <li class="{{ Request::is('api-key*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('api-key.index') }}"><i class="fas fa-key"></i> <span>API Key</span></a></li>
      <li class="{{ Request::is('pricing') ? 'active' : '' }}"><a class="nav-link" href="{{ route('pricing') }}"><i class="fas fa-tag"></i> <span>Pricing</span></a></li>
      <li class="{{ Request::is('api-tester') ? 'active' : '' }}"><a class="nav-link" href="{{ route('api-tester') }}"><i class="fas fa-flask"></i> <span>API Tester</span></a></li>

      @if (auth()->user()->isAdmin())
        <li class="menu-header">Admin Center</li>
        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i> <span>Admin Dashboard</span></a></li>
        <li class="{{ Request::is('admin/users*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>User Management</span></a></li>
      @endif
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="{{ route('download-template') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Documentation
      </a>
    </div>
  </aside>
</div>
