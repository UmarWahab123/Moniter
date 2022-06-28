@php
if (Auth::user()) {
      $user_permissions = unserialize(Auth::user()->permissions);
  }
@endphp
 <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="{{ url('user/home') }}"><img src="{{ asset('public/images/icon/logo.png') }}" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            @if ($user_permissions == false)
                            <li class="active">
                                <a href="{{ url('user/home') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                            <li class="">
                                <a href="{{ url('user/websites') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Websites</span></a>
                            </li>
                            <li class="">
                                <a href="{{ url('user/subscription') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Subscription</span></a>
                            </li>
                            <li class="">
                                <a href="{{ url('user/settings') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Settings</span></a>
                            </li>
                            @endif
                            @if ($user_permissions && in_array('dashboard',$user_permissions))
                            <li class="active">
                                <a href="{{ url('user/home') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                            @endif
                            @if ($user_permissions && in_array('websites',$user_permissions))
                            <li class="">
                                <a href="{{ url('user/websites') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Websites</span></a>
                            </li>
                            @endif
                            @if ($user_permissions && in_array('email_templates',$user_permissions))
                            <li class="">
                                <a href="{{ route('users.templates.index') }}" aria-expanded="true"><i
                                        class="ti-world"></i><span>Email Templates</span></a>
                            </li>
                            @endif
                            @if ($user_permissions && in_array('users',$user_permissions))
                            <li class="">
                                <a href="{{ route('users.users') }}" aria-expanded="true"><i
                                        class="ti-user"></i><span>Users</span></a>
                            </li>
                            @endif
                            @if ($user_permissions && in_array('settings',$user_permissions))
                            <li class="">
                                <a href="{{ url('user/settings') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Settings</span></a>
                            </li>
                            @endif
                            <li class="">
                                <a class="d-none" href="{{ url('user/devices') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>Devices</span></a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
