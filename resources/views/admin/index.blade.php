@extends('layouts.app')

@section('content')
    <div class="page-container">
        <!-- sidebar menu area start -->
        @if (Auth::user() && Auth::user()->role_id == 1)
        @include('admin.assets.sidebar')
        @else
        @include('user.assets.sidebar')
        @endif
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            {{-- @include('admin.assets.header') --}}

            <!-- header area end -->
            <!-- page title area start -->
            @if (Auth::user() && Auth::user()->role_id == 1)
            @include('admin.assets.title_area')
            @else
            @include('user.assets.title_area')
            @endif

            <!-- page title area end -->
            <div class="main-content-inner">
                <!-- sales report area start -->
                @if (!$monitors->isEmpty())
                    <div class="sales-report-area sales-style-two">
                        <div class="row">
                            @foreach ($monitors as $monitor)
                                <a href="{{ url('admin/website-logs/' . $monitor->id) }}"
                                    class="col-xl-3 col-ml-3 col-md-3 mt-5">
                                    <div class="single-report">
                                        <div class="s-sale-inner pt--30 mb-3">
                                            <div class=" d-flex justify-content-between">
                                                <h5 class="header-title mb-0">{{ @$monitor->getSiteDetails->title }}</h5>
                                                @if ($monitor->uptime_status == 'up')
                                                    <span class="badge badge-success text-white px-4 ">Up</span>
                                                @elseif($monitor->uptime_status == 'down')
                                                    <span class="badge badge-danger text-white px-4">Down</span>
                                                @else
                                                    <span class="badge badge-warning text-white">Not Yet Checked</span>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <p class="bg-white pl-0">{{ $monitor->url }}</p>

                                            </div>
                                            <div class=" d-flex justify-content-between mt-2">
                                                <p class="bg-white pl-0">SSL Expiry Date</p>
                                                <p class="bg-info badge text-white">
                                                    @if ($monitor->certificate_expiration_date != null)
                                                        {{ date('Y-m-d', strtotime($monitor->certificate_expiration_date)) }}
                                                    @else
                                                        {{ '--' }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class=" d-flex justify-content-between mt-2">
                                                <p class="bg-white pl-0">Last Down</p>
                                                <p class="bg-dark badge text-white">
                                                    @php
                                                        if ($monitor->getSiteLogs != null) {
                                                            $logs = $monitor->getSiteLogs->first();
                                                        }
                                                    @endphp
                                                    {{ $logs != null ? date('Y-m-d', strtotime($logs->down_time)) : '--' }}
                                                </p>

                                            </div>
                                            <div class=" d-flex justify-content-between mt-2">
                                                <p class="bg-white pl-0">Last Up</p>
                                                <p class="bg-success badge text-white ">
                                                    {{ $logs != null ? date('Y-m-d', strtotime($logs->up_time)) : '--' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                @endif
                <!-- sales report area end -->
                <!-- visitor graph area start -->
                <div class="card mt-5 d-none">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-5">
                            <h4 class="header-title mb-0">Website Graph</h4>
                            <select class="custome-select border-0 pr-3">
                                <option selected="">Last 7 Days</option>
                                <option value="0">Last 7 Days</option>
                            </select>
                        </div>
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                            <p class="highcharts-description">
                                Chart showing data loaded dynamically. The individual data points can
                                be clicked to display more information.
                            </p>
                        </figure>
                    </div>
                </div>
                <!-- visitor graph area end -->
                <!-- order list area start -->
                <div class="card mt-5 d-none">
                    <div class="card-body">
                        <h4 class="header-title">Todays Order List</h4>
                        <div class="table-responsive">
                            <table class="dbkit-table">
                                <tbody>
                                    <tr class="heading-td">
                                        <td>Product Name</td>
                                        <td>Product Code</td>
                                        <td>Order Status</td>
                                        <td>Client Number</td>
                                        <td>Zip Code</td>
                                        <td>View Order</td>
                                    </tr>
                                    <tr>
                                        <td>Ladis Sunglass</td>
                                        <td>#894750374</td>
                                        <td><span class="pending_dot">Pending</span></td>
                                        <td>01976 74 92 00</td>
                                        <td>9241</td>
                                        <td>View Order</td>
                                    </tr>
                                    <tr>
                                        <td>Ladis Sunglass</td>
                                        <td>#894750374</td>
                                        <td><span class="shipment_dot">Shipment</span></td>
                                        <td>01976 74 92 00</td>
                                        <td>9241</td>
                                        <td>View Order</td>
                                    </tr>
                                    <tr>
                                        <td>Ladis Sunglass</td>
                                        <td>#894750374</td>
                                        <td><span class="pending_dot">Pending</span></td>
                                        <td>01976 74 92 00</td>
                                        <td>9241</td>
                                        <td>View Order</td>
                                    </tr>
                                    <tr>
                                        <td>Ladis Sunglass</td>
                                        <td>#894750374</td>
                                        <td><span class="confirmed _dot">Confirmed </span></td>
                                        <td>01976 74 92 00</td>
                                        <td>9241</td>
                                        <td>View Order</td>
                                    </tr>
                                    <tr>
                                        <td>Ladis Sunglass</td>
                                        <td>#894750374</td>
                                        <td><span class="pending_dot">Pending</span></td>
                                        <td>01976 74 92 00</td>
                                        <td>9241</td>
                                        <td>View Order</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination_area pull-right mt-5">
                            <ul>
                                <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- order list area end -->
                <div class="row d-none">
                    <!-- product sold area start -->
                    <div class="col-xl-8 col-lg-7 col-md-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4 class="header-title mb-0">Product Slod</h4>
                                    <select class="custome-select border-0 pr-3">
                                        <option selected="">Today</option>
                                        <option value="0">Last 7 Days</option>
                                    </select>
                                </div>
                                <div class="table-responsive">
                                    <table class="dbkit-table">
                                        <tbody>
                                            <tr class="heading-td">
                                                <td>Product Name</td>
                                                <td>Revenue</td>
                                                <td>Sold</td>
                                                <td>Discount</td>
                                            </tr>
                                            <tr>
                                                <td>Ladis Sunglass</td>
                                                <td>$56</td>
                                                <td>$160</td>
                                                <td>$20</td>
                                            </tr>
                                            <tr>
                                                <td>Ladis Sunglass</td>
                                                <td>$26</td>
                                                <td>$500</td>
                                                <td>$20</td>
                                            </tr>
                                            <tr>
                                                <td>Ladis Sunglass</td>
                                                <td>$26</td>
                                                <td>$500</td>
                                                <td>$20</td>
                                            </tr>
                                            <tr>
                                                <td>Ladis Sunglass</td>
                                                <td>$56</td>
                                                <td>$250</td>
                                                <td>$10</td>
                                            </tr>
                                            <tr>
                                                <td>Ladis Sunglass</td>
                                                <td>$56</td>
                                                <td>$125</td>
                                                <td>$50</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination_area pull-right mt-5">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                        <li><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product sold area end -->
                    <!-- team member area start -->
                    <div class="col-xl-4 col-lg-5 col-md-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-sm-flex flex-wrap justify-content-between mb-4 align-items-center">
                                    <h4 class="header-title mb-0">Team Member</h4>
                                    <form class="team-search">
                                        <input type="text" name="search" placeholder="Search Here">
                                    </form>
                                </div>
                                <div class="member-box">
                                    <div class="s-member">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('public/images/team/team-author1.jpg') }}"
                                                class="d-block ui-w-30 rounded-circle" alt="">
                                            <div class="media-body ml-5">
                                                <p>Amir Hamza</p><span>Manager</span>
                                            </div>
                                            <div class="tm-social">
                                                <a href="#"><i class="fa fa-phone"></i></a>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="s-member">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('public/images/team/team-author2.jpg') }}"
                                                class="d-block ui-w-30 rounded-circle" alt="">
                                            <div class="media-body ml-5">
                                                <p>Anamul Kabir</p><span>UI design</span>
                                            </div>
                                            <div class="tm-social">
                                                <a href="#"><i class="fa fa-phone"></i></a>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="s-member">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('public/images/team/team-author3.jpg') }}"
                                                class="d-block ui-w-30 rounded-circle" alt="">
                                            <div class="media-body ml-5">
                                                <p>Animesh Mondol</p><span>UI design</span>
                                            </div>
                                            <div class="tm-social">
                                                <a href="#"><i class="fa fa-phone"></i></a>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="s-member">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('public/images/team/team-author4.jpg') }}"
                                                class="d-block ui-w-30 rounded-circle" alt="">
                                            <div class="media-body ml-5">
                                                <p>Faruk Hasan</p><span>UI design</span>
                                            </div>
                                            <div class="tm-social">
                                                <a href="#"><i class="fa fa-phone"></i></a>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="s-member">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('public/images/team/team-author5.jpg') }}"
                                                class="d-block ui-w-30 rounded-circle" alt="">
                                            <div class="media-body ml-5">
                                                <p>Sagor Chandra</p><span>Motion Designer</span>
                                            </div>
                                            <div class="tm-social">
                                                <a href="#"><i class="fa fa-phone"></i></a>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- team member area end -->
                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        @if (Auth::user() && Auth::user()->role_id == 1)
        @include('admin.assets.footer')
        @else
        @include('user.assets.footer')
        @endif
        <!-- footer area end-->
    </div>
    @if (Auth::user() && Auth::user()->role_id == 1)
    @include('admin.assets.javascript')
    @else
    @include('user.assets.javascript')
    @endif
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Weekely Website Status'
            },
            subtitle: {
                text: 'Source: www.akhtarsitsolutions.com/'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ]
            },
            yAxis: {
                title: {
                    text: 'Temperature'
                },
                labels: {
                    formatter: function() {
                        return this.value + '°';
                    }
                }
            },
            tooltip: {
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                name: 'Tokyo',
                marker: {
                    symbol: 'square'
                },
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, {
                    y: 26.5,

                }, 23.3, 18.3, 13.9, 9.6]

            }, {
                name: 'London',
                marker: {
                    symbol: 'diamond'
                },
                data: [{
                    y: 3.9,

                }, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]
        });
    </script>
@endsection
