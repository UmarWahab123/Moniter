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
        @include('user.assets.sidebar')
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            @include('user.assets.title_area')

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8 title-col">
                                <h4 class="maintitle"> Edit Template</h4>
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
                                        {{-- <option value="SSLCertificateExpiry"
                                            @if ($template->name == 'SSLCertificateExpiry') selected="selected" @endif>
                                            SSLCertificateExpiry</option>
                                        <option value="MonthlySummary"
                                            @if ($template->name == 'MonthlySummary') selected="selected" @endif>MonthlySummary
                                        </option> --}}
                                        <option value="SiteUptimeStatus"
                                            @if ($template->name == 'SiteUptimeStatus') selected="selected" @endif>SiteUptimeStatus
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="font-weight-bold">Subject <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('subject', $value = $template->subject, ['class' => 'form-control ' . ($errors->has('subject') ? 'is-invalid' : ''), 'placeholder' => 'Subject', 'id' => 'subject']) !!}
                                    @if ($errors->has('subject'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="body" class="font-weight-bold">Body <span
                                            class="text-danger">*</span></label>
                                    {!! Form::textarea('body', $value = $template->body, ['class' => 'form-control ckeditor' . ($errors->has('body') ? 'is-invalid' : ''), 'placeholder' => 'Body', 'id' => 'body']) !!}
                                    @if ($errors->has('body'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Update', ['class' => 'btn btn-primary btn-bg']) !!}
                                </div>
                                {!! Form::hidden('id', $value = $template->id) !!}
                                {!! Form::close() !!}
                            </div>
                            <div class="col-sm-4 mt-4 pt-2">
                                <div class="border p-3 bg-light">
                                    <strong>Note: </strong>Please copy and paste the following variables in the editor as
                                    they are.
                                    @foreach ($template_keywords as $keyword)
                                        <p class="mb-1">{{ $keyword->keyword }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @include('user.assets.footer')

                <!-- footer area end-->
            </div>
            @include('user.assets.javascript')
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
                        url: "{{ route('users.templates.update') }}",
                        method: "POST",
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                toastr.success('Success!', 'Email template Updated Successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                location.href = '{{ route('templates.index') }}';
                            }
                        }
                    });
                });
            </script>
        @endsection
