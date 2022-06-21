@extends('layouts.app')
@section('content')
    <div class="page-container">
        <!-- sidebar menu area start -->
        @if (auth()->user()->userRole->role_id == 1)
            @include('admin.assets.sidebar')
        @else
            @include('user.assets.sidebar')
        @endif
        <!-- sidebar menu area end -->

        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            @if (auth()->user()->userRole->role_id == 1)
                @include('admin.assets.title_area')
            @else
                @include('user.assets.title_area')
            @endif

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary btn-sm float-right mb-2" id="addPackage"> <i
                                        class="fa fa-plus"></i> Add Package</button>
                                <div class="table-responsive">

                                    <table id="package_table" class="table table-stripped text-center">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Group By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @if (auth()->user()->userRole->role_id == 1)
                    @include('admin.assets.footer')
                @else
                    @include('user.assets.footer')
                @endif

                <!-- footer area end-->
            </div>

            <div class="modal fade" id="addPackageModal">
                <div class="modal-dialog" style="max-width:800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Package</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="addPackageForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-0">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="m-0">Price <span class="text-danger">*</span></label>
                                        <input type="text" name="price" class="form-control" id="ip_address"
                                            placeholder="Enter Price">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="m-0">Billing Periods <span class="text-danger">*</span></label>
                                        <select name="duration_in_days" id="duration_in_days" class="form-control">
                                            <option value="" selected="">Please Select</option>
                                            <option value="30">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" id="PackageSubmitBtn"
                                    class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            

            @if (auth()->user()->userRole->role_id == 1)
                @include('admin.assets.javascript')
            @else
                @include('user.assets.javascript')
            @endif

            <!-- Start datatable js -->
            <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                var table = $('#package_table').DataTable({
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
                        url: "{{ url('get-servers') }}",
                        {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'price',
                            name: 'price'
                        },
                       
                        {
                            data: 'group_by',
                            name: 'group_by'
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

                $(document).on('change', '.form-control', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next().remove();
                });

                $('#addPackage').on('click', function() {
                    $('#addPackageModal').modal('show');
                });

                $('#addPackageForm').on('submit', function(e) {
                    e.preventDefault();
                    var form = $('#addPackageForm').serialize();

                    $.ajax({
                        url: '{{ url('admin/add-package') }}',
                        method: 'post',
                        data: form,
                        beforeSend: function() {
                            $('#PackageSubmitBtn').prop('disabled', true);
                            $('#PackageSubmitBtn').html('Please wait...');
                        },
                        success: function(data) {
                            if (data.success == true) {
                                $('#addPackageModal').modal('hide');
                                $('#addPackageForm')[0].reset();
                                toastr.success('Success!', 'Server added successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#servers_table').DataTable().ajax.reload();
                                $('#PackageSubmitBtn').prop('disabled', false);
                                $('#PackageSubmitBtn').html('Submit');
                            } else if (data.success == false) {
                                toastr.error('Error!', 'Something went wrong', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#PackageSubmitBtn').prop('disabled', false);
                                $('#PackageSubmitBtn').html('Submit');
                            }
                        },
                        error: function(request, status, error) {
                            $('#PackageSubmitBtn').prop('disabled', false);
                            $('#PackageSubmitBtn').html('Submit');
                            $('.form-control').removeClass('is-invalid');
                            $('.form-control').next().remove();
                            json = $.parseJSON(request.responseText);
                            $.each(json.errors, function(key, value) {
                                $('input[name="' + key + '"]').after(
                                    '<span class="invalid-feedback" role="alert"><strong>' + value +
                                    '</strong>');
                                $('input[name="' + key + '"]').addClass('is-invalid');
                                $('select[name="' + key + '"]').after(
                                    '<span class="invalid-feedback" role="alert"><strong>' + value +
                                    '</strong>');
                                $('select[name="' + key + '"]').addClass('is-invalid');
                            });
                        },
                    })
                });

             
            

            

                $(document).ready(function() {
                    reloadDatatable();
                });

                function reloadDatatable() {
                    setTimeout(function() {
                        $('#servers_table').DataTable().ajax.reload();
                        reloadDatatable();
                    }, 65000);
                }

             
            </script>
        @endsection
