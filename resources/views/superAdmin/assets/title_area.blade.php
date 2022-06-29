            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix d-none">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index.html">Home</a></li>
                                <li><span>Dashboard</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12 clearfix ">
                        <div class="user-profile pull-right col-md-2">
                            <img class="avatar user-thumb" src="{{ asset('public/images/author/avatar.png') }}" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}<i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu  mx-0">
                                <a class="dropdown-item" href="#"><a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
