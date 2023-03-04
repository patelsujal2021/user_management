<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="{{ url('theme/uploads/profile/profile.jpg') }}" width="55" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</strong>
                            </span>
                            <span class="text-muted text-xs block">{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} <b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="">Profile</a></li>
                        <li><a href="">Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    {{ env('APP_NAME') }}
                </div>
            </li>
            <li>
                <a href=""><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            @if(Auth::user()->parent == 0)
            <li>
                <a href="{{ route('user.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Users</span></a>
            </li>
            @endif
            <li>
                <a href="{{ route('blog.index') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Blogs</span></a>
            </li>
        </ul>
    </div>
</nav>
