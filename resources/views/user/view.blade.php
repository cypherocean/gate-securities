@extends('layouts.app')

@section('meta')
@endsection

@section('title')
    User View
@endsection

@section('styles')
    <link href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sweetalert2.bundle.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Users</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user') }}" class="text-muted">Users</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" name="form" id="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Plese enter name" value="{{ $data->name ??'' }}" disabled>
                                <span class="kt-form__help error name"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Plese enter email" value="{{ $data->email ??'' }}" disabled>
                                <span class="kt-form__help error email"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Plese enter phone" value="{{ $data->phone ??'' }}" disabled>
                                <span class="kt-form__help error phone"></span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="role" name="role" placeholder="Plese select role">Role</label>
                                <select class="form-control" name="role" id="role" disabled>
                                    <option value="">Select role</option>
                                    @if(isset($roles) && $roles->isNotEmpty())
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($data->roles->first()->id == $role->id) selected @endif >{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="kt-form__help error role"></span>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="profile">Profile Image</label>
                                <input type="file" class="dropify" id="profile" data-default-file="{{ $data->photo ??'' }}" name="profile" data-allowed-file-extensions="jpg png jpeg" data-max-file-size-preview="2M" disabled>
                                <span class="kt-form__help error profile"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/promise.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.bundle.js') }}"></script>

    <script>
        $(document).ready(function(){
            var drEvent = $('.dropify').dropify();

            var dropifyElements = {};
            $('.dropify').each(function () {
                dropifyElements[this.id] = false;
            });

            drEvent.on('dropify.beforeClear', function(event, element){
                id = event.target.id;
                if(!dropifyElements[id]){
                    var url = "{!! route('user.profile.remove') !!}";
                    <?php if(isset($data) && isset($data->id)){ ?>
                        var id_encoded = "{{ base64_encode($data->id) }}";

                        Swal.fire({
                            title: 'Are you sure want delete this image?',
                            text: "",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then(function (result){
                            if (result.value){
                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    data:{
                                        id: id_encoded,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    dataType: "JSON",
                                    success: function (data){
                                        if(data.code == 200){
                                            Swal.fire('Deleted!', 'Deleted Successfully.', 'success');
                                            dropifyElements[id] = true;
                                            element.clearElement();
                                        }else{
                                            Swal.fire('', 'Failed to delete', 'error');
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown){
                                        Swal.fire('', 'Failed to delete', 'error');
                                    }
                                });
                            }
                        });

                        return false;
                    <?php } else { ?>
                        Swal.fire({
                            title: 'Are you sure want delete this image?',
                            text: "",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then(function (result){
                            if (result.value){
                                Swal.fire('Deleted!', 'Deleted Successfully.', 'success');
                                dropifyElements[id] = true;
                                element.clearElement();
                            }else{
                                Swal.fire('Cancelled', 'Discard Last Operation.', 'error');
                            }
                        });
                        return false;
                    <?php } ?>
                } else {
                    dropifyElements[id] = false;
                    return true;
                }
            });
        });
    </script>

@endsection

