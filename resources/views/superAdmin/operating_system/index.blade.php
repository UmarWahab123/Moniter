@extends('layouts.app')
@section('content')
<!-- page title area end -->
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary btn-sm float-right mb-2" id="addOperating"> <i
                            class="fa fa-plus"></i> Add Operating System</button>
                    <div class="table-responsive">

                        <table id="operating-table" class="table table-stripped text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
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

<div class="modal fade" id="addOperatingModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Operating System</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addOperatingForm">
                    @csrf
                    <div class="row">
                    <input type="text" name="id" class="form-control d-none" id="edit_id">
                        <div class="col-md-12">
                            <label class="m-0">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row" style="height:15px;"></div>
                    <button type="submit" id="operatingSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                </form>
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

    
    var table = $('#operating-table').DataTable({

    
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
        ajax: "{{ url('superAdmin/operating-system') }}",
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
                data: 'action',
                name: 'Action'
            },
        ],
    });
    
    $(document).on('click', '.edit_operating_system', function(e) {
        var id = $(this).val();
        $.ajax({
            url: "{{ url('superAdmin/operating-system/edit') }}",
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('#addOperatingModal').modal('show');
                $('#name').val(data.data['name']);
                $('#edit_id').val(id);
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

    $('#addOperating').on('click', function() {
        $('#addOperatingModal').modal('show');
    });

    $('#addOperatingForm').on('submit', function(e) {
        e.preventDefault();
        var form = $('#addOperatingForm').serialize();

        $.ajax({
            url: '{{ url('superAdmin/add-operating-system') }}',
            method: 'post',
            data: form,
            beforeSend: function() {
                $('#operatingSubmitBtn').prop('disabled', true);
                $('#operatingSubmitBtn').html('Please wait...');
            },
            success: function(data) {
                
                if (data.success == true) {
                    $('#addOperatingModal').modal('hide');
                    $('#addOperatingForm')[0].reset();
                    toastr.success('Success!', data.message , {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#operating-table').DataTable().ajax.reload();
                    $('#operatingSubmitBtn').prop('disabled', false);
                    $('#operatingSubmitBtn').html('Submit');
                } else if (data.success == false) {
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#operatingSubmitBtn').prop('disabled', false);
                    $('#operatingSubmitBtn').html('Submit');
                }
            },
        })
    });
    $(document).on('click', '.btn_delete', function(e) {
        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Delete the selected Operating System !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:"{{ url('superAdmin/operating-system/delete') }}",
                    method: 'post',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        toastr.success('Success!', ' Operating System Deleted successfully !', {
                            "positionClass": "toast-bottom-right"
                        });
                        $('#operating-table').DataTable().ajax.reload();
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
            $('#operating-table').DataTable().ajax.reload();
            reloadDatatable();
        }, 65000);
    }

    
</script>
@endsection