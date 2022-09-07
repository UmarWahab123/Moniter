@extends('layouts.app')

@section('content')
<style>
    .span_css {
        margin-top: 5px;
        font-size: 14px;
    }
</style>
    <div class="page-container">
        @include('admin.assets.sidebar')
        <div class="main-content">
            @include('admin.assets.title_area')
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="h4">Uer Details</span>
                                        <table class="table mt-2" border="0">
                                            <tr>
                                                <td><span class="span_css">Name</span></td>
                                                <td><span class="span_css">{{ $user->name }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="span_css">Email:</span></td>
                                                <td><span class="span_css">{{ $user->email }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="span_css">Status:</span></td>
                                                <td>
                                                    @if ($user->status == 1)
                                                        <span class="badge badge-success span_css">Active</span>
                                                    @else
                                                        <span class="badge badge-danger span_css">Disabled</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="span_css">Role:</span></td>
                                                <td><span class="span_css">{{ $user->role->name }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="span_css">Account Status Verification:</span></td>
                                                <td>
                                                    @if ($user->email_verified_at != null)
                                                        <span class="badge badge-success span_css">Verified</span>
                                                    @else
                                                        <span class="badge badge-danger span_css">Not Verified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="span_css">Last Seen At: </span></td>
                                                <td><span class="span_css">{{ $user->last_seen_at }}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-6">

                                    <span class="h4">Uer Permissions</span>
                                        <form class="Form_permissions mt-2">
                                            @csrf
                                            <div class="form-group">
                                                <input type="checkbox" @if ($permissions && in_array('dashboard',$permissions)) checked @endif @if ($permissions && in_array('dashboard',$permissions))  value="1" @else value = "0" @endif name="dashboard"> Dashboard
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" @if ($permissions && in_array('websites',$permissions)) checked @endif @if ($permissions && in_array('websites',$permissions)) value="1" @else value = "0" @endif name="websites"> Websites
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" @if ($permissions && in_array('servers',$permissions)) checked @endif @if ($permissions && in_array('servers',$permissions)) value="1" @else value = "0" @endif name="servers"> Servers
                                            </div>
                                            <div class="form-group">
                                                @if ($user->role_id == 1)
                                                <input type="checkbox" @if ($permissions && in_array('plans',$permissions)) checked @endif @if ($permissions && in_array('plans',$permissions)) value="1" @else value = "0" @endif name="plans"> Plans
                                                @elseif ($user->role_id == 2)
                                                <input type="checkbox" @if ($permissions && in_array('subscription',$permissions)) checked @endif @if ($permissions && in_array('subscription',$permissions)) value="1" @else value = "0" @endif name="subscription"> Subscription
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" @if ($permissions && in_array('users',$permissions)) checked @endif @if ($permissions && in_array('users',$permissions))  value="1" @else value = "0" @endif name="users"> Users
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" @if ($permissions && in_array('settings',$permissions)) checked @endif @if ($permissions && in_array('settings',$permissions))  value="1" @else value = "0" @endif name="settings"> Settings
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-primary" value="Save">
                                            </div>
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.assets.footer')
            @include('admin.assets.javascript')
            <script type="text/javascript">
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $('input[type="checkbox"]').change(function (e) {
                    if ($(this).is(':checked')) {
                        $(this).val("1");
                    }
                    else{
                        $(this).val("0");
                    }
                });
                $(document).on('submit', '.Form_permissions', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('users.save-permissions') }}",
                        data: $(this).serialize(),
                        method: 'POST',
                        success: function(data){
                            if(data.success){
                                toastr.success('Success!', 'Permissions saved successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                location.reload();
                            }
                        }

                    });
                });
            </script>
        </div>
    </div>
@endsection
