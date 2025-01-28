<li class="nav-item">
    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Pacientes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('medicos.index') }}" class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-md"></i>
        <p>Médicos</p>
    </a>
</li> 