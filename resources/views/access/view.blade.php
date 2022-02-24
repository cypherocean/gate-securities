@extends('layouts.app')

@section('meta')
@endsection

@section('title')
    Access View
@endsection

@section('styles')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Access</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('access') }}" class="text-muted">Access</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="customize-input float-right">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="role" name="role" placeholder="Plese select role">Role</label>
                            <select class="form-control" name="role" id="role" disabled>
                                <option value="">Select role</option>
                                @if(isset($roles) && $roles->isNotEmpty())
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if($data->id == $role->id) selected @endif>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="kt-form__help error role"></span>
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="permissions" name="permissions" placeholder="Plese select permissions">Permissions</label>
                            <br>
                            <span class="kt-form__help error permissions"></span>
                        </div>
                        @if(isset($permissions) && $permissions->isNotEmpty())
                            @foreach($permissions as $permission)
                                <div class="col-sm-3 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="permissions[]" class="custom-control-input" id="{{ $permission->id }}" value="{{ $permission->id }}" @if(in_array($permission->id, $data->permissions)) checked @endif disabled>
                                        <label class="custom-control-label" for="{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn waves-effect waves-light btn-rounded btn-outline-primary">Submit</button>
                        <a href="{{ route('access') }}" class="btn waves-effect waves-light btn-rounded btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
