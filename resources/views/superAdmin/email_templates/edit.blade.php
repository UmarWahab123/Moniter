@extends('layouts.app')

@section('content')
    <style>
        .ck.ck-content.ck-editor__editable {
            height: 180px;
        }
    </style>
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
            </div>
         @endsection
         @section('scripts')
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
                        url: "{{ route('templates.update') }}",
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
