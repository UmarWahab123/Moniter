@extends('layouts.app')
@section('content')
    <style>
    .dashboard-boxes-shadow{
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .dashboard-boxes-font{
        font-size: 16px;
    }

    .box3{border-top:5px solid #6AA84F;}
    .box2{border-top:5px solid #3D85C6;}
    .box1{border-top:5px solid #F1C232;}
    </style>
        <div class="main-content-inner">
            <div class="row">
                <div class="col-md-4">
                    <label for="user">Select User to see their records</label>
                    <select name="user" id="user_select" class="form-control" style="min-height:45px;">
                        <option value="" selected disabled>Choose User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="bg-white box1 pt-4 pb-4 h-100 dashboard-boxes-shadow phone-boxes">
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font">
                                Total Servers
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font" id="total_servers">
                                0
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white box2 pt-4 pb-4 h-100 dashboard-boxes-shadow phone-boxes">
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font">
                                Total Websites
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font" id="total_websites">
                                0
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white box3 pt-4 pb-4 h-100 dashboard-boxes-shadow phone-boxes">
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font">
                                Total Sub Users
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center w-100 mt-3">
                            <span class="span-color font-weight-bold dashboard-boxes-font" id="total_users">
                                0
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <canvas id="myChart" style="max-width:800px"></canvas>
                </div>
            </div>
        </div>

        @endsection
        @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script>
            $(document).on('change', '#user_select', function() {
                let user_id = $(this).val();
                $.ajax({
                    url: "{{ route('superAdmin.get-users-total-records') }}",
                    data: { user_id: user_id},
                    success: function(data){
                        if (data.success) {
                            $('#total_users').html(data.total_users);
                            $('#total_servers').html(data.total_servers);
                            $('#total_websites').html(data.total_websites);
                            refreshChart();
                        }
                    }
                });
            })
            $(document).ready(function() {
                refreshChart();
            });

            function refreshChart() {
                var xValues = ['Total Servers', 'Total Websites', 'Total Sub Users'];
                var yValues = [$('#total_servers').text(), $('#total_websites').text(), $('#total_users').text()];
                var barColors = ["red", "green","blue","orange","brown"];

                new Chart("myChart", {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                    }]
                },
                options: {
                    legend: {display: false},
                    title: {
                    display: true,
                    text: $( "#user_select option:selected" ).text()+" Records"
                    }
                }
                });
            }
        </script>
    @endsection
