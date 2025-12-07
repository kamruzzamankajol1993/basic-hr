<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-primary btn-sm me-3" style="background-color: var(--bd-green); border-color: var(--bd-green);">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="h5 mb-0 fw-bold text-dark d-none d-md-block">
                    HR System - Admin Panel
                </div>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small fw-bold"> {{ Auth::user()->name }}</span>
                            <i class="fas fa-user-circle fa-lg text-primary"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user fa-sm fa-fw me-2 text-primary"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-danger"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>