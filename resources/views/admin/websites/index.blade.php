@extends('layouts.app')

@section('content')
<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        {{-- <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <figure class="highcharts-figure">
                                <div id="container"></div>
                                <p class="highcharts-description">
                                    Website up and down status logs.
                                </p>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
        <div class="col-12 mt-5">
            <div class="card">

                <div class="card-body">
                    <button class="btn btn-primary btn-sm float-left mb-2 d-none" id="assign_to_users">Assign User</button>
                    <button class="btn btn-primary btn-sm float-right mb-2" id="addWebsiteBtn"> <i
                            class="fa fa-plus"></i> Add Website</button>
                    <div class="table-responsive">

                        <table id="websitesDataTable" class="table table-stripped text-center">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="checkbox" name="all_checkboxes" id="all_checkboxes" value="all_checkboxes">
                                    </th>
                                    <th>Title </th>
                                    <th>Website </th>
                                    <th>Status Changed On </th>
                                    <th>Last Checked On </th>
                                    <th>SSL Cerificate Check </th>
                                    <th>Certificate Expiry Date </th>
                                    <th>Certificate Issuer </th>
                                    <th>Assigned Users </th>
                                    <th>Domain Created At </th>
                                    <th>Domain Updated At </th>
                                    <th>Domain Expiry Date</th>
                                    <th>Status </th>
                                    <th style="min-width:12%">Action</th>

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
  <!-- to store no of servers in package and user servers count -->
<input type="hidden" class="user_website_added" value={{ $user_website_added }}>
<input type="hidden" class="no_of_website_allowed" value={{ $no_of_websites_allowed }}>
<div class="modal fade" id="addWebsiteModal">
    <div class="modal-dialog" style="max-width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Website</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addWebsiteForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="m-0">Choose <span class="text-danger">*</span></label>
                            <select name="protocol" id="protocol" class="form-control" style="min-height:45px;">
                                <option value="" selected disabled>Choose</option>
                                <option value="Http://">Http</option>
                                <option value="Https://">Https</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="m-0">URL <span class="text-danger">*</span></label>
                            <input type="text" name="url" class="form-control" id="url"
                                placeholder="Enter Url">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" id="title"
                                placeholder="Enter Title">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Email <span class="text-danger">*</span></label><small
                                class="text-info float-right d-none"> (Emails should be comma seprated)</small>
                            <input type="text" value="{{ auth()->user()->userdetail->primary_notification_email }}" name="emails" class="form-control" id="email"
                                placeholder="Enter Email">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Developer Email </label>
                            <input type="text" value="{{ auth()->user()->userdetail->developer_email }}" name="developer_email" class="form-control"
                                id="developer_email" placeholder="Enter Developer Email">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Owner Email </label>
                            <input type="text" value="{{ auth()->user()->userdetail->secondary_notification_email  }}" name="owner_email" class="form-control" id="owner_email"
                                placeholder="Enter Owner Email">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Choose Server </label>
                            <select name="server_id" id="add_server" class="form-control" style="min-height:45px;">
                                <option value="" selected disabled>Choose Server</option>
                                @foreach ($servers as $server)
                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Domain Expiry Date </label>
                            <input type="date" name="domain_expiry_date" class="form-control"  id="domain_expiry_date"
                                placeholder="Select Domain Date">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Domain Registrar </label>
                            <input type="text" name="domain_registrar" class="form-control" id="domain_registrar"
                                placeholder="Enter Domain Registrar">
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-check mt-4">
                                <input name="ssl" type="checkbox" class="form-check-input"
                                    id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">SSL Check</label>
                            </div>
                        </div>

                    </div>
                    <button type="submit" id="websiteSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="editWebsiteModal">
    <div class="modal-dialog" style="max-width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Website</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editWebsiteForm">
                    @csrf
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                    <div class="col-md-6">
                            <label class="m-0">Choose <span class="text-danger">*</span></label>
                            <select name="protocol" id="edit_protocol_option" class="form-control" style="min-height:45px;">
                                <option value="" selected disabled>Choose</option>
                                <option value="http://">Http</option>
                                <option value="https://">Https</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="m-0">URL <span class="text-danger">*</span></label>
                            <input type="text" name="url" class="form-control" id="edit_url"
                                placeholder="Enter Url">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0 mt-2">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" id="editTitle"
                                aria-describedby="emailHelp" placeholder="Enter Title">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0 mt-2">Email <span class="text-danger">*</span></label>
                            <input type="text" name="emails" class="form-control" id="editEmails"
                                aria-describedby="emailHelp" placeholder="Enter Title">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0 mt-2">Developer Email </label>
                            <input type="text" name="developer_email" class="form-control"
                                id="edit_developer_email" placeholder="Enter Developer Email">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0 mt-2">Owner Email </label>
                            <input type="text" name="owner_email" class="form-control"
                                id="edit_owner_email" placeholder="Enter Owner Email">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Choose Server </label>
                            <select name="server_id" id="edit_server" class="form-control" style="min-height:45px;">
                                <option value="" selected disabled>Choose Server</option>
                                @foreach ($servers as $server)
                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Domain Expiry Date </label>
                            <input type="date" name="domain_expiry_date" class="form-control"  id="edit_domain_expiry_date"
                                placeholder="Select Domain Date">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="m-0">Domain Registrar </label>
                            <input type="text" name="domain_registrar" class="form-control" id="edit_domain_registrar"
                                placeholder="Enter Domain Registrar">
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-check ">
                                <input name="ssl" type="checkbox" class="form-check-input"
                                    id="editSsl">
                                <label class="form-check-label" for="editSsl">Ssl Check</label>
                            </div>
                        </div>

                    </div>
                    <button type="submit" id="editWebsiteSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="assign_user_Modal">
    <div class="modal-dialog" style="max-width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Website and Permission to User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="users">Select User</label>
                        <select name="sub_users" id="sub_users" class="form-control" style="height:45px !important;">
                            <option value="" selected disabled>Select User</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="permission">Select Permission</label>
                        <select name="permission" id="permission" class="form-control" style="height:45px !important;">
                            <option value="" selected disabled>Select Permission</option>
                            <option value="0">Read</option>
                            <option value="1">Read/Write</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn_save_assign_user">Assign User</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="show_assign_user_Modal">
    <div class="modal-dialog" style="max-width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigned Users</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">

                        <table id="websitesDataTable" class="table table-stripped text-center">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Permission</th>
                                </tr>
                            </thead>
                            <tbody class="assigned_user_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> 
@endsection
@section('scripts')   
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var table = $('#websitesDataTable').DataTable({
        // "bAutoWidth": false,
        serverSide: true,
        processing: true,
        searching: true,
        ordering: true,
        pageLength: {{ 50 }},
        "processing": true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },
        scrollCollapse: true,
        ajax: {
            url: "{{ url('admin/websites') }}",
            {{-- beforeSend:function()
        {
        },
        success:function()
        {
        } --}}
        },
        columns: [


            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'url',
                name: 'url'
            },
            {
                data: 'status_change_on',
                name: 'status_change_on'
            },

            {
                data: 'last_status_check',
                name: 'last_status_check'
            },
            {
                data: 'certificate_check',
                name: 'certificate_check'
            },
            {
                data: 'certificate_expiry_date',
                name: 'certificate_expiry_date'
            },
            {
                data: 'certificate_issuer',
                name: 'certificate_issuer'
            },
            {
                data: 'assigned_users',
                name: 'assigned_users'
            },
            {
                data: 'domain_creation_date',
                name: 'domain_creation_date'
            },
            {
                data: 'domain_updated_date',
                name: 'domain_updated_date'
            },
            {
                data: 'domain_expiry_date',
                name: 'domain_expiry_date'
            },

            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action'
            },


        ],

    });
    $(document).on('keyup', '.form-control', function() {
        $(this).removeClass('is-invalid');
        $(this).next().remove();
    });
    $('#addWebsiteBtn').on('click', function() {
        @if (Auth::user()->email_verified_at == null)
            toastr.info('Info!', 'Please Verify your account first', {
                "positionClass": "toast-bottom-right"
            });
            return;
        @endif
          // check if user exceeding limit
        var no_of_websites_allowed = parseInt($('.no_of_website_allowed').val());
        var user_website_added = parseInt($('.user_website_added').val());
        if(no_of_websites_allowed <= user_website_added){
            toastr.info('Info!', 'Adding website limit reached. Please upgrade your package', {
                "positionClass": "toast-bottom-right"
            });
            return;
        }

        $('#addWebsiteModal').modal('show');
    });
    $('#addWebsiteForm').on('submit', function(e) {
        e.preventDefault();
        var form = $('#addWebsiteForm').serialize();

        $.ajax({
            url: '{{ url('admin/add-website') }}',
            method: 'post',
            data: form,
            beforeSend: function() {
                $('#websiteSubmitBtn').prop('disabled', true);
                $('#websiteSubmitBtn').html('Please wait...');
            },
            success: function(data) {
                if (data.success == true) {
                    $('.no_of_website_allowed').val(data.no_of_websites_allowed);
                    $('.user_website_added').val(data.user_websites_added);
                    $('#addWebsiteModal').modal('hide');
                    $('#addWebsiteForm')[0].reset();
                    toastr.success('Success!',
                        'Website added successfully and being monitored', {
                            "positionClass": "toast-bottom-right"
                        });
                    $('#websitesDataTable').DataTable().ajax.reload();
                    $('#websiteSubmitBtn').prop('disabled', false);
                    $('#websiteSubmitBtn').html('Submit');
                } else if (data.success == false) {
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#websiteSubmitBtn').prop('disabled', false);
                    $('#websiteSubmitBtn').html('Submit');
                }

            },
            error: function(request, status, error) {
                // toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                $('#websiteSubmitBtn').prop('disabled', false);
                $('#websiteSubmitBtn').html('Submit');
                $('.form-control').removeClass('is-invalid');
                $('.form-control').next().remove();
                json = $.parseJSON(request.responseText);
                $.each(json.errors, function(key, value) {
                    $('input[name="' + key + '"]').after(
                        '<span class="invalid-feedback" role="alert"><strong>' +
                        value +
                        '</strong>');
                    $('input[name="' + key + '"]').addClass('is-invalid');
                });
            },
        })
    });
    $(document).on('click', '.delete-site', function(e) {

        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ url('admin/delete-website') }}',
                    method: 'get',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if(data.success==true){
                            toastr.success('Success!', 'Website deleted successfully', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('.no_of_website_allowed').val(data.no_of_websites_allowed);
                            $('.user_website_added').val(data.user_websites_added);
                            $('#websitesDataTable').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong', {
                            "positionClass": "toast-bottom-right"
                        });
                    },
                });
            }
        })
    });
    $(document).on('click', '.edit-site', function(e) {
        var website_id = $(this).val();
        $.ajax({
            url: "{{ url('admin/website/edit-website') }}",
            method: 'get',
            data: {
                website_id: website_id
            },
            success: function(data) {
                $('#editWebsiteModal').modal('show');
                $('#edit_protocol_option').val(data.data['protocol']);
                $('#edit_url').val(data.data['url']);
                $('#editTitle').val(data.data['title']);
                $('#editEmails').val(data.data['emails']);
                $('#edit_developer_email').val(data.data['developer_email']);
                $('#edit_owner_email').val(data.data['owner_email']);
                $('#edit_domain_expiry_date').val(data.data['domain_expiry_date'])
                $('#edit_domain_registrar').val(data.data['domain_registrar'])
                $('#editId').val(website_id);
                $('#edit_server').val(data.data['server_id']).prop('selected', true);
                if (data.data['ssl'] == 1) {
                    $('#editSsl').prop('checked', true);
                } else {
                    $('#editSsl').prop('checked', false);
                }
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


    });

    $(document).on('click', '#editWebsiteSubmitBtn', function(e) {
        e.preventDefault();
        var formData = $('#editWebsiteForm').serialize();
        $.ajax({
            url: '{{ url('admin/edit-website') }}',
            method: 'post',
            data: formData,
            success: function(data) {
                $('#editWebsiteModal').modal('hide');
                toastr.success('Success!', 'Website updated successfully', {
                    "positionClass": "toast-bottom-right"
                });
                $('#websitesDataTable').DataTable().ajax.reload();
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })

    });

    $(document).ready(function() {
        reloadDatatable();
    });

    function reloadDatatable() {
        setTimeout(function() {
            $('#websitesDataTable').DataTable().ajax.reload();
            reloadDatatable();

        }, 65000);

    }

    $(document).on('change', '#all_checkboxes', function() {
        if (this.checked) {
            $('.checkbox').prop('checked', true);;
            $('#assign_to_users').removeClass('d-none');
        }
        else{
            $('.checkbox').prop('checked', false);;
            $('#assign_to_users').addClass('d-none');
        }
    });

    $(document).on('change', '.checkbox', function() {
        if (this.checked) {
            $('#assign_to_users').removeClass('d-none');
        }
        else{
            $('#assign_to_users').addClass('d-none');
        }
    });

    $(document).on('click', '#assign_to_users', function() {
        $('#assign_user_Modal').modal('show');
    });

    $(document).on('click', '.btn_save_assign_user', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want Assign Selected Websites to a Sub User?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                let sub_user_id = $('#sub_users').val();
                let permission = $('#permission').val();
                var selected_items = [];
                $("input.checkbox:checked").each(function() {
                    selected_items.push($(this).val());
                });
                $.ajax({
                    url: "{{ route('assign-websites-to-user') }}",
                    method: "POST",
                    data: {selected_items:selected_items, sub_user_id:sub_user_id, permission:permission},
                    success: function(data){
                        if (data.success) {
                            toastr.success('Success!', 'User Assign successfully', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('#assign_user_Modal').modal('hide');
                        }
                    }
                });
            }
        })
    });

    $(document).on('click', '.btn_view_assigned_users',function(){
        let id = $(this).val();
        $.ajax({
            url: '{{ route('show_assigned_users') }}',
            data: {id: id},
            success: function(data) {
                if (data.success){
                    $('.assigned_user_tbody').html("");
                    $('.assigned_user_tbody').append(data.html);
                    $('#show_assign_user_Modal').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.feature', function(e) {

        var id = $(this).val();
        var status = $(this).data('status');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to feature this website",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ url('admin/feature') }}',
                    method: 'get',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        if (data.limit == 0) {
                            toastr.success('Success!',
                                'Website featured successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                        } else if (data.limit == 1) {
                            toastr.info('Info!', 'Featured limit reached', {
                                "positionClass": "toast-bottom-right"
                            });
                        } else if (data.limit == 2) {
                            toastr.success('Success!',
                                'Website unfeatured successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                        }

                        $('#websitesDataTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong', {
                            "positionClass": "toast-bottom-right"
                        });
                    },
                });
            }
        })

    });

                {{-- Highcharts.chart('container', {

    title: {
        text: 'Up and Down Time Check of Servers'
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: 'Number of Employees'
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Range: 2010 to 2017'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Installation',
        data: [1, 2]
    },  {
        name: 'Other',
        data: [1, 1.8]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

}); --}}
            </script>
        @endsection
