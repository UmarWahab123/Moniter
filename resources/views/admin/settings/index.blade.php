@extends('layouts.app')
@section('content')
<div class="main-content-inner">
    <div class="col-lg-12 col-ml-12 mt-5">

        <div class="row">
            <!-- basic form start -->

            <div class="col-12 mt-5">

                <div class="card col-6">
                    <div class="card-body">
                        <h4 class="header-title">Default Settings
                        </h4>
                        <table class="table d-none">
                            <tr>
                                <th>Email</th>
                                <td>arslan@akhtarsitsolutions.com</td>
                            </tr>
                        </table>
                        <form id="defaultSettingForm">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input required value="{{ @$setting->settings }}" type="email"
                                    class="form-control" name="email" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Developer Email</label>
                                <input required value="{{ @$setting->developer_email }}" type="email"
                                    class="form-control" name="developer_email" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Enter Developer Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Owner Email</label>
                                <input required value="{{ @$setting->owner_email }}" type="email"
                                    class="form-control" name="owner_email" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Enter Owner Email">
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

    <!-- footer area end-->
</div>
@endsection
@section('scripts')
<script>
    $(document).on('submit', '#defaultSettingForm', function(e) {
        @if (Auth::user()->email_verified_at == null)
            toastr.info('Info!', 'Please Verify your account first', {
                "positionClass": "toast-bottom-right"
            });
            return;
        @endif
        e.preventDefault();
        var formData = $('#defaultSettingForm').serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            method: "post",
            url: "{{ url('admin/add-settings') }}",
            data: formData,
            success: function(data) {
                if (data.success) {
                    toastr.success('Success!', 'Settings saved successfully', {
                        "positionClass": "toast-bottom-right"
                    });
                } else {
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                }
            }
        })
    })
</script>
@endsection
