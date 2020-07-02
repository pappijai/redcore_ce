
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>RedCore Solutions</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper" id="app">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-orange" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                    </li>
                </ul>
                <!-- SEARCH FORM -->
                <div class="form-inline ml-3">
                    <div class="input-group input-group-sm">
                        <h4 class="text-orange">Coding Exam</h4>
                    </div>
                </div>
                <form class="form-inline ml-3" @submit.prevent="search_it">
                    <div class="input-group input-group-sm">
                        <input type="text" v-model="search" @keyup="search_it" class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" @click="search_it">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false"><i class="fa fa-cog"></i> Settings</a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); 
                                    document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off mr-2 text-red"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                </ul>

            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->


                <!-- Sidebar -->
                <div class="sidebar">
                    <a href="" class="brand-link">
                        <img src="/images/logo/image001.png" alt="AdminLTE Logo" class="img-circle elevation-3"
                            style="opacity: .8;width: 95%;">
                    </a>
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="/images/people.png" class="img-circle elevation-3" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">
                                {{ Auth::user()->name}}
                                <br/>
                                <small><i class="fas fa-circle fa-sm text-green"></i> admin</small>
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            {{-- dashboard --}}
                            <li class="nav-item">
                                <router-link to="/dashboard" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt text-white"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </router-link>
                            </li>

                            {{-- blogs --}}
                            <li class="nav-item">
                                <router-link to="/blogs" class="nav-link">
                                    <i class="nav-icon fas fa-newspaper text-white"></i>
                                    <p>
                                        Blogs
                                    </p>
                                </router-link>
                            </li>

                            {{-- recycle bin --}}
                            <li class="nav-item">
                                <router-link to="/recycle_bin" class="nav-link">
                                    <i class="nav-icon fas fa-trash text-white"></i>
                                    <p>
                                        Recycle Bin
                                    </p>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <router-view></router-view>
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <!-- Default to the left -->
                <strong>Copyright &copy; 2019-2020 <a href="#">RedCore Solutions</a>.</strong> All rights reserved.
            </footer>
        </div>

        <script src="/js/app.js"></script>
    </body>
</html>
