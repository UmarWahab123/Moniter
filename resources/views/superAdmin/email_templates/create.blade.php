@extends('layouts.app')

@section('content')
    <style>
        .ck.ck-content.ck-editor__editable {
            height: 180px;
        }
    </style>
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        @include('superAdmin.assets.sidebar')
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            @include('superAdmin.assets.title_area')

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8 title-col">
                                <h4 class="maintitle"> Add New Template</h4>
                            </div>
                        </div>
                        <div class="row d-sm-flex">
                            <div class="col-sm-8">
                                {!! Form::open(['class' => 'Form_email_template']) !!}
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Name <span
                                            class="text-danger">*</span></label>
                                    <select name="name" id="name" class="form-control" style="height: 45px;">
                                        <option value="" disabled="disabled" selected="selected">Choose Template Name
                                        </option>
                                        {{-- <option value="SSLCertificateExpiry">SSLCertificateExpiry</option>
                                        <option value="MonthlySummary">MonthlySummary</option> --}}
                                        <option value="SiteUptimeStatus">SiteUptimeStatus</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="font-weight-bold">Subject <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('subject', $value = null, ['class' => 'form-control ' . ($errors->has('subject') ? 'is-invalid' : ''), 'placeholder' => 'Subject', 'id' => 'subject']) !!}
                                    @if ($errors->has('subject'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="body" class="font-weight-bold">Body <span
                                            class="text-danger">*</span></label>
                                    {!! Form::textarea('body', $value = null, ['class' => 'form-control ckeditor' . ($errors->has('body') ? 'is-invalid' : ''), 'placeholder' => 'Body', 'id' => 'body']) !!}
                                    @if ($errors->has('body'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-bg']) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class="col-sm-4 mt-4 pt-2">
                                <div class="border p-3 bg-light">
                                    <div class="col-md-12">
                                        <a href="javascript;:" class="btn btn-primary" data-toggle="modal"
                                            data-target="#Modal_Keyword">Add Keyword</a>
                                    </div>
                                    <div class="col-md-12 keywords_div">
                                        <strong>Note: </strong>Please copy and paste the following variables in the editor
                                        as
                                        they are.
                                        @foreach ($template_keywords as $keyword)
                                            <p class="mb-1">{{ $keyword->keyword }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="Modal_Keyword">
                        <div class="modal-dialog" style="max-width:800px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Keyword</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form id="Form_keyword">
                                        @csrf
                                        <input type="hidden" name="id" id="editId">
                                        <div class="from-group">
                                            <label class="m-0 mt-2">Keyword <span class="text-danger">*</span></label>
                                            <input type="text" name="keyword" class="form-control" id="keyword"
                                                placeholder="Enter Keyword e.g. [[name]]">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @include('superAdmin.assets.footer')

                <!-- footer area end-->
            </div>
            @include('superAdmin.assets.javascript')
            <!-- Start datatable js -->
            <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
            <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                ClassicEditor
                    .create(document.querySelector('#body'))
                    .catch(error => {
                        console.error(error);
                    });
                $(document).on('submit', '.Form_email_template', function(e) {
                    e.preventDefault();
                    if ($('#name').val() == '' || $('#subject').val() == '' || $('#body').val() == '') {
                        toastr.info('Info!', 'All * fields are reequired', {
                            "positionClass": "toast-bottom-right"
                        });
                        return false;
                    }
                    $.ajax({
                        url: "{{ route('templates.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                toastr.success('Success!', 'Email template Added Successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                location.href = '{{ route('templates.index') }}';
                            }
                        }
                    });
                });
                $(document).on('submit', '#Form_keyword', function(e) {
                    e.preventDefault();
                    if ($('#keyword').val() == '') {
                        toastr.info('Info!', 'Keyword Field is required', {
                            "positionClass": "toast-bottom-right"
                        });
                        return false;
                    }
                    $.ajax({
                        url: "{{ route('templates.storeKeyword') }}",
                        method: "POST",
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                $('#Modal_Keyword').modal('hide');
                                $('.keywords_div').append('<p class="mb-1">' + data.keyword + '</p>')
                                toastr.success('Success!', 'Keyword Added Successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#keyword').val('');
                            } else {
                                toastr.error('Error!', 'This Keyword Already Existed', {
                                    "positionClass": "toast-bottom-right"
                                });
                            }
                        }
                    });
                });
            </script>
        @endsection
