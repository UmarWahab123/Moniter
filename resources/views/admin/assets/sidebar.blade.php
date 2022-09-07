 @php
      if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
        }
 @endphp
 <div class="sidebar-menu">
     <div class="sidebar-header">
         <div class="logo">
             <a href="{{ url('admin/home') }}"><img src="{{ asset('public/images/icon/logo.png') }}"
                     alt="logo"></a>
         </div>
     </div>
     <div class="main-menu">
         <div class="menu-inner">
             <nav>
                 <ul class="metismenu" id="menu">
                    @if ($user_permissions == false)
                    <li class="active">
                        <a href="{{ url('admin/home') }}" aria-expanded="true"><i
                                class="ti-dashboard"></i><span>dashboard</span></a>
                    </li>
                    <li class="">
                        <a href="{{ url('admin/websites') }}" aria-expanded="true"><i
                                class="ti-world"></i><span>Websites</span></a>
                    </li>
                    <li class="">
                        <a href="{{ route('servers.dashboard') }}" aria-expanded="true"><i
                                class="ti-server"></i><span>Servers</span></a>
                    </li>
                    <li class="">

                        <a href="{{ url('admin/package') }}" aria-expanded="true"><i class="ti-server"></i><span>Plans</span></a>
                    </li>

                    <li class="">
                        <a href="{{ url('admin/users') }}" aria-expanded="true"><i
                                class="ti-user"></i><span>Users</span></a>
                    </li>
                    <li class="">
                        <a href="{{ url('admin/settings') }}" aria-expanded="true"><i
                                class="ti-settings"></i><span>Settings</span></a>
                    </li>
                    @endif
                    @if ($user_permissions && in_array('dashboard',$user_permissions))
                     <li class="active">
                         <a href="{{ url('admin/home') }}" aria-expanded="true"><i
                                 class="ti-dashboard"></i><span>dashboard</span></a>
                     </li>
                     @endif
                     @if ($user_permissions && in_array('websites',$user_permissions))
                     <li class="">
                         <a href="{{ url('admin/websites') }}" aria-expanded="true"><i
                                 class="ti-world"></i><span>Websites</span></a>
                     </li>
                     @endif
                     @if ($user_permissions && in_array('servers',$user_permissions))
                     <li class="">
                         <a href="{{ route('servers.dashboard') }}" aria-expanded="true"><i
                                 class="ti-server"></i><span>Servers</span></a>
                     </li>
                     @endif
                     @if ($user_permissions && in_array('plans',$user_permissions))
                     <li class="">
                        <a href="{{ url('admin/package') }}" aria-expanded="true"><i class="ti-server"></i><span>Plans</span></a>
                    </li>
                     @endif
                     @if ($user_permissions && in_array('users',$user_permissions))
                     <li class="">
                         <a href="{{ url('admin/users') }}" aria-expanded="true"><i
                                 class="ti-user"></i><span>Users</span></a>
                     </li>
                     @endif
                     @if ($user_permissions && in_array('settings',$user_permissions))
                     <li class="">
                         <a href="{{ url('admin/settings') }}" aria-expanded="true"><i
                                 class="ti-settings"></i><span>Settings</span></a>
                     </li>
                     @endif
                     <li class="">
                         <a class="d-none" href="{{ url('admin/devices') }}" aria-expanded="true"><i
                                 class="ti-dashboard"></i><span>Devices</span></a>
                     </li>
                 </ul>
             </nav>
         </div>
     </div>
 </div>
