@extends('layouts.app')
<style>input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
}

input[type=number] {
  -moz-appearance: textfield;
}</style>
@section('content')
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
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <input type="text" name="id" class="form-control d-none" id="edit_id">
                        <div class="col-md-6">
                            <label class="m-0">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter Name">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0">Type<span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control">
                                <option value="" selected="">Please Select</option>
                                <option value="Free">Free</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                       </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="m-0">Price <span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control" id="price"
                                placeholder="Enter Price">
                        </div>
                        <div class="col-md-6">
                            <label class="m-0">Status<span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control">
                                <option value="" selected="">Please Select</option>
                                <option value="1">Active</option>
                                <option value="0">Suspended</option>
                            </select>
                        </div>
                        </div>
                        <div class="row mt-2">
                        <div class="col-md-12">
                        <label>Description</label>
                        <textarea type="text" name="description" id="description" rows="5" class="form-control">
                        </textarea>
                        </div>
                       </div>
                    <div class="row" style="height:15px;"></div>
                    <button type="submit" id="PackageSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                </form>
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade" id="assignFeatureModal">
 <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Assign Feature</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <h6 class="mb-3">Already Assigned Features</h6>
            <div class="table-responsive assigned-features-table"></div>

            <h6 class="mb-3 mt-4">Assign More Features</h6>
            <form id="assignFeatureForm">
                @csrf
                <div class="row">
                <input type="text" name="package_id" class="form-control d-none" id="package_id">
                <div class="col-md-6">
                <label class="m-0">System Features<span class="text-danger">*</span></label>
                <select name="system_feature_id" id="feature_id" class="form-control">
                    <option value="" selected="">Please Select Feature</option>
                    <option value=""></option>
                </select>
                </div>
                <div class="col-md-6">
                <label class="m-0">Count<span class="text-danger">*</span></label>
                <input type="number" name="max_allowed_count"  placeholder="Enter Count" class="form-control " id="max_count">
                </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="assignFeature" class="btn btn-primary">Submit</button>
                </div>
            </form>
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

    
    var table = $('#package_table').DataTable({

    
        // "bAutoWidth": false,
        serverSide: true,
        processing: true,
        searching: true,
        ordering: true,
        "processing": true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },

        scrollCollapse: true,
        ajax: "{{ url('superAdmin/package') }}",
        columns: [

            {
                data: 'id',
                name: 'ID'
            },
            {
                data: 'name',
                name: 'Name'
            },
            {
                data: 'type',
                name: 'Type'
            },
            {
                data: 'price',
                name: 'Price'
            },
            
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'Action'
            },


        ],
    });
    // var table = $('#feature-table').DataTable({

                
    //         // "bAutoWidth": false,
    //         serverSide: true,
    //         processing: true,
    //         searching: true,
    //         ordering: true,
    //         "processing": true,
    //         'language': {
    //             'loadingRecords': '&nbsp;',
    //             'processing': 'Loading...'
    //         },

    //         scrollCollapse: true,
    //         ajax: "{{ url('superAdmin/system-features') }}",
    //         columns: [

    //             {
    //                 data: 'id',
    //                 name: 'ID'
    //             },
    //             {
    //                 data: 'name',
    //                 name: 'Name'
    //             },
    //             {
    //                 data: 'action',
    //                 name: 'Action'
    //             },
    //         ],
    // });
    $(document).on('click', '.assign-feature', function(e) {
        var id = $(this).val();
        $.ajax({
            url: "{{ url('superAdmin/package/assign-feature') }}",
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('#assignFeatureModal').modal('show');
                $('#feature_id').html(data.response);
                $('#package_id').val(id);
                $('.assigned-features-table').html(data.table_html);
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


    });

    $(document).on('click', '.edit-package', function(e) {
        var id = $(this).val();
        $.ajax({
            url: "{{ url('superAdmin/package/edit') }}",
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('#addPackageModal').modal('show');
                $('#name').val(data.data['name']);
                $('#price').val(data.data['price']);
                $('#type').val(data.data['type']);
                $('#status').val(data.data['status']);
                $('#description').val(data.data['description']);
                $('#edit_id').val(id);
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


    });

    $(document).on('click', '.change-status', function(e) {
        var id = $(this).val();
        var status = $(this).attr('data-status');
        $.ajax({
            url: "{{ url('superAdmin/package/update-status') }}",
            method: 'get',
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                toastr.success('Success!', 'Package Status Update successfully', {
                        "positionClass": "toast-bottom-right"
                    });
            

                    window.setTimeout( function() {
                        table.ajax.reload();
                    // window.location.reload();
                    }, 1000);
                
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


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
            url: '{{ url('superAdmin/add-package') }}',
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
                    toastr.success('Success!', data.message , {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#package_table').DataTable().ajax.reload();
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
    $('#assignFeatureForm').on('submit', function(e) {
        e.preventDefault();
        var form = $('#assignFeatureForm').serialize();

        $.ajax({
            url: '{{ url('superAdmin/store-assign-feature') }}',
            method: 'post',
            data: form,
            beforeSend: function() {
                $('#assignFeature').prop('disabled', true);
                $('#assignFeature').html('Please wait...');
            },
            success: function(data) {
                
                if (data.success == true) {
                    $('#assignFeatureModal').modal('hide');
                    $('#assignFeatureForm')[0].reset();
                    toastr.success('Success!', 'System Feature assign successfully !' , {
                        "positionClass": "toast-bottom-right"
                    });
                    // $('#feature-table').DataTable().ajax.reload();
                    $('#assignFeature').prop('disabled', false);
                    $('#assignFeature').html('Submit');
                } else if (data.success == false) {
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#assignFeature').prop('disabled', false);
                    $('#assignFeature').html('Submit');
                }
            },
        })
    });
    $(document).on('click', '.assigned_feature_delete', function(e) {
        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Delete the selected Feature !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:"{{ url('superAdmin/package-feature/delete') }}",
                    method: 'post',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        toastr.success('Success!', 'Feature Deleted successfully', {
                            "positionClass": "toast-bottom-right"
                        });
                        $( ".assign-feature" ).trigger( "click" );
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
    $(document).ready(function() {
        reloadDatatable();
    });

    function reloadDatatable() {
        setTimeout(function() {
            $('#package_table').DataTable().ajax.reload();
            reloadDatatable();
        }, 65000);
    }

    
</script>
@endsection
