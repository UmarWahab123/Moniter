@extends('layouts.app')
@section('content')
<!-- page title area end -->
<div class="main-content-inner">
    <div class="card mt-2">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-8 title-col">
                    <h4 class="maintitle">EMAIL TEMPLATES</h4>
                </div>
                <div class="col-md-4 text-right">
                    <a href="javascript:;" class="btn btn-primary button-st btn-wd btn_add_email">ADD
                        EMAIL TEMPLATE</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="table-responsive" width="100">
                    <table class="table table-stripped text-center Table_email_templates w-100">
                        <thead class="text-center">
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Last Updated By</th>
                                <th>Last Updated At</th>
                            </tr>
                        </thead>
                    </table>
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
        var table = $('.Table_email_templates').DataTable({
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
                url: "{{ route('templates.getTemplates') }}",
            },
            columns: [{
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                }
            ],
        });

        $(document).ready(function() {
            reloadDatatable();
        });

        function reloadDatatable() {
            setTimeout(function() {
                $('.Table_email_templates').DataTable().ajax.reload();
                reloadDatatable();

            }, 65000);

        }
        $(document).on('click', '.btn-delete', function(e) {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to Delete this Template",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $(this).data('url'),
                        method: 'post',
                        success: function(data) {
                            if (data.success) {
                                toastr.success('Success!', 'Template Deleted successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('.Table_email_templates').DataTable().ajax.reload();
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
        $(document).on('click', '.btn_add_email', function() {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            location.href = "{{ route('templates.create') }}";
        });
        $(document).on('click', '.btn-edit', function() {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            location.href = $(this).data('url');
        });
    </script>
@endsection
