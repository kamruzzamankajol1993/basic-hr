<nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3 class="fw-bold text-white mb-0">HR System</h3>
            <span class="text-white-50 small">Admin Panel</span>
        </div>

        <ul class="list-unstyled components">
            <p class="sidebar-title">MAIN MENU</p>
            <li class="{{ Route::is('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        </li>
            <li class="{{ Route::is('employees.*') ? 'active' : '' }}">
            <a href="#employeeSubmenu" 
               data-bs-toggle="collapse" 
               aria-expanded="{{ Route::is('employees.*') ? 'true' : 'false' }}" 
               class="dropdown-toggle">
                <i class="fas fa-users me-2"></i> Employees
            </a>
            <ul class="collapse list-unstyled {{ Route::is('employees.*') ? 'show' : '' }}" id="employeeSubmenu">
                {{-- View All (Index) --}}
                <li class="{{ Route::is('employees.index') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}">View All</a>
                </li>
                {{-- Add New (Create) --}}
                <li class="{{ Route::is('employees.create') ? 'active' : '' }}">
                    <a href="{{ route('employees.create') }}">Add New</a>
                </li>
            </ul>
        </li>
            <li class="{{ Route::is('departments.*') ? 'active' : '' }}">
            <a href="{{ route('departments.index') }}"><i class="fas fa-building me-2"></i> Departments</a>
        </li>
            <li class="{{ Route::is('skills.*') ? 'active' : '' }}">
            <a href="{{ route('skills.index') }}"><i class="fas fa-wrench me-2"></i> Skills Management</a>
        </li>
           
        </ul>

        @include('admin.include.footer')
        
    </nav>