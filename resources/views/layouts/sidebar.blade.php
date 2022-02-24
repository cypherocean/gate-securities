<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item {{ Request::is('dashboard*') ? 'selected' : '' }}"> 
                    <a class="sidebar-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @canany(['role-create', 'role-edit', 'role-view', 'role-delete', 'permission-create', 'permission-edit', 'permission-view', 'permission-delete', 'access-edit', 'access-view'])
                    <li class="sidebar-item {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'selected' : '' }}"> 
                        <a class="sidebar-link has-arrow {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
                            <i class="fas fa-lock-open"></i>
                            <span class="hide-menu">Access Control </span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level base-level-line {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'in' : '' }}">
                            @canany(['role-create', 'role-edit', 'role-view', 'role-delete'])
                                <li class="sidebar-item {{ Request::is('role*') ? 'active' : '' }}">
                                    <a class="sidebar-link {{ Request::is('role*') ? 'active' : '' }}" href="{{ route('role') }}" aria-expanded="false">
                                        <i class="fas fa-check"></i>
                                        <span class="nav-label">Roles</span>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['permission-create', 'permission-edit', 'permission-view', 'permission-delete'])
                                <li class="sidebar-item {{ Request::is('permission*') ? 'active' : '' }}">
                                    <a class="sidebar-link {{ Request::is('permission*') ? 'active' : '' }}" href="{{ route('permission') }}" aria-expanded="false">
                                        <i class="fas fa-key"></i>
                                        <span class="nav-label">Permissions</span>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['access-edit', 'access-view'])
                                <li class="sidebar-item {{ Request::is('access*') ? 'active' : '' }}">
                                    <a class="sidebar-link {{ Request::is('access*') ? 'active' : '' }}" href="{{ route('access') }}" aria-expanded="false">
                                        <i class="fas fa-lock"></i>
                                        <span class="nav-label">Access</span>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['user-create', 'user-edit', 'user-view', 'user-delete'])
                    <li class="sidebar-item {{ Request::is('user*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ Request::is('user*') ? 'active' : '' }}" href="{{ route('user') }}" aria-expanded="false">
                            <i class="fas fa-users"></i>
                            <span class="hide-menu">Users</span>
                        </a>
                    </li>
                @endcanany
                @canany(['setting-edit', 'setting-view'])
                    <li class="sidebar-item {{ Request::is('settings*') ? 'selected' : '' }}">
                        <a class="sidebar-link {{ Request::is('settings*') ? 'active' : '' }}" href="{{ route('settings') }}" aria-expanded="false">
                            <i class="fas fa-sun"></i>
                            <span class="hide-menu">Settings</span>
                        </a>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>