<h4 class="mb-4 text-white">ADMIN</h4>
<h6 class="text-white-50 mb-3">DATA REPORT</h6>

<div class="mb-4">
    <a href="{{ route('dashboard') }}" class="text-decoration-none">
        <div class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </div>
    </a>
</div>

<h6 class="text-white-50 mb-3">Station</h6>
<div class="mb-3">
    <a href="{{ route('station.list') }}" class="text-decoration-none">
        <div class="sidebar-item {{ request()->routeIs('station.list') ? 'active' : '' }}">
            ▸ List Station
        </div>
    </a>
</div>

<div class="mb-3">
    <a href="{{ route('location.index') }}" class="text-decoration-none">
        <div class="sidebar-item {{ request()->routeIs('location.index') ? 'active' : '' }}">
            📍 Location
        </div>
    </a>
</div>
