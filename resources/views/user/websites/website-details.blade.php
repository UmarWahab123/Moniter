@extends('layouts.app')
@section('content')
<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            {{-- <h2 class="text-uppercase">{{($website->getSiteDetails !=null )?$website->getSiteDetails->title:'N/A'}} <small></small></h2> --}}
            <table class="table table-sm table-borderless w-50 custom-table">
                <thead>
                    <th class="text-uppercase">
                        <h2> {{ $website->getSiteDetails != null ? $website->getSiteDetails->title : 'N/A' }}
                        </h2>
                    </th>

                    <th>
                        @if ($website->uptime_status == 'up')
                            <span class="badge badge-success text-white px-4 text-uppercase ">Up</span>
                        @elseif($website->uptime_status == 'down')
                            <span class="badge badge-danger text-white px-4 text-uppercase">Down</span>
                        @else
                            <span class="badge badge-warning text-white">Not Yet Checked</span>
                        @endif
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <th>Website</th>
                        <td>{{ $website->url }}</td>
                    </tr>
                    <tr>
                        <th>Certificate Check</th>
                        <td>
                            @if ($website->certificate_check_enabled == 1)
                                <span class="badge badge-success "><i class="fa fa-check"></i></span>
                            @else
                                <span class="badge badge-danger "> <i class="fa fa-times"></i> </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Certificate Expiry Date</th>
                        <td>{{ $website->certificate_expiration_date }}</td>
                    </tr>
                    @php
                        if ($website->getSiteLogs != null) {
                            $logs = $website->getSiteLogs->first();
                        }
                    @endphp
                    <tr>
                        <th>Last Down</th>
                        <td>{{ $logs != null ? date('Y-m-d H:i:s', strtotime($logs->down_time)) : '--' }}</td>
                    </tr>
                    <tr>
                        <th>Last Up</th>
                        <td>{{ $logs != null ? date('Y-m-d H:i:s', strtotime($logs->up_time)) : '--' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="card">
                <div class="card-body">
                    <span class="h5 ">Website Logs</span>
                    <div class="table-responsive mt-3">

                        <table id="websitesDetailsDataTable" class="table table-stripped text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Down Time </th>
                                    <th>Up Time </th>
                                    <th>Down Reason </th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="downReasonModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Down Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body down-reason-div">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="downReasonImageModel" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Down Reason Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body down-reason-image-div">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var table = $('#websitesDetailsDataTable').DataTable({
        // "bAutoWidth": false,
        serverSide: true,
        processing: true,
        searching: false,
        ordering: true,
        pageLength: {{ 50 }},
        "processing": true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },
        scrollCollapse: true,
        ajax: {
            url: "{{ url('user/website-logs') }}" + "/" + {{ $website_id }},
            {{-- beforeSend:function()
        {
        },
        success:function()
        {
        } --}}
        },
        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'down_time',
                name: 'down_time'
            },
            {
                data: 'up_time',
                name: 'up_time'
            },
            {
                data: 'down_reason',
                name: 'down_reason'
            },
            {
                data: 'down_reason_image',
                name: 'down_reason_image'
            },

        ],

    });

    // $(document).ready(function(){
    //         reloadDatatable();
    // });

    // function reloadDatatable()
    // {
    //      setTimeout(function(){
    //         $('#websitesDetailsDataTable').DataTable().ajax.reload();
    //         reloadDatatable();

    //     }, 65000);

    // }

    $(document).on('click', '.down-reason', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url('user/get-down-reason') }}',
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('.down-reason-div').text(data.down_reason);
                $('#downReasonModel').modal('show');
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        });
    });

    $(document).on('click', '.view-image', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url('user/get-down-reason-image') }}',
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('.down-reason-image-div').html(data.html_string);
                $('#downReasonImageModel').modal('show');
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        });
    });
</script>
@endsection
