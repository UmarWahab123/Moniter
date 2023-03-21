@extends('layouts.app')

@section('content')
<div class="main-content-inner">
    <!-- sales report area start -->
    @if (!$monitors->isEmpty())
        <div class="sales-report-area sales-style-two">
            <div class="row">
                @foreach ($monitors as $monitor)
                    <a href="{{ url('user/website-logs/' . $monitor->id) }}"
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
                                        {{ $logs != null ? date('Y-m-d', strtotime($logs->up_time)) : '--' }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    @endif
</div>
@endsection
@section('scripts')

@endsection
