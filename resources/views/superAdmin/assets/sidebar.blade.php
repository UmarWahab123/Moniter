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
                    <li class="">
                        <a href="{{ route('superAdmin.dashboard') }}" aria-expanded="true"><i
                                class="ti-dashboard"></i><span>dashboard</span></a>
                    </li>
                    <li class="">
                        <a href="{{ route('superAdmin.users') }}" aria-expanded="true"><i
                                class="ti-user"></i><span>Users</span></a>
                    </li>
                    <li class="">
                        <a href="{{ url('superAdmin/package') }}" aria-expanded="true"><i
                                class="ti-user"></i><span>Packages</span></a>
                    </li>
                    <li class="">
                        <a href="{{ url('superAdmin/system-features') }}" aria-expanded="true"><i
                                class="ti-user"></i><span>System Features</span></a>
                    </li>
                    <li class="">
                        <a href="{{ url('superAdmin/operating-system') }}" aria-expanded="true"><i
                                class="ti-user"></i><span>Operating System</span></a>
                    </li>
                     <li class="">
                         <a class="d-none" href="{{ url('admin/devices') }}" aria-expanded="true"><i
                                 class="ti-dashboard"></i><span>Devices</span></a>
                     </li>
                     <li class="">
                        <a href="{{ route('templates.index') }}" aria-expanded="true"><i
                                class="ti-world"></i><span>Email Templates</span></a>
                    </li>
                 </ul>
             </nav>
         </div>
     </div>
 </div>
