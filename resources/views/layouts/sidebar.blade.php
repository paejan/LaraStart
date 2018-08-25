<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="./img/logo.png" alt="LaraStart" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">LaraStart</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/users" class="nav-link">
                        <i class="nav-icon fas fa-users"></i><p>Users</p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/permissions" class="nav-link">
                        <i class="nav-icon fas fa-key"></i><p>Permissions</p>
                    </router-link>
                </li>
            </ul>
        </nav> <!-- /.sidebar-menu -->
    </div> <!-- /.sidebar -->
</aside>
<!-- / Main Sidebar -->