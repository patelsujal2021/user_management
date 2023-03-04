@extends('layouts.account')

@section('page_title') {{ env('APP_NAME') }} | Users @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Edit User</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" action="{{ route('user.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="first_name" placeholder="first name" value="@if(old('first_name')){{old('first_name')}}@endif"/>
                                @if($errors->has('first_name'))
                                    <span class="form-text m-b-none text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="last_name" placeholder="last name" value="@if(old('last_name')){{old('last_name')}}@endif"/>
                                @if($errors->has('last_name'))
                                    <span class="form-text m-b-none text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Date Of Birth</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="dob" placeholder="date of birth" value="@if(old('dob')){{old('dob')}}::parse($user->dob)->format('Y-m-d')}}@endif"/>
                                @if($errors->has('dob'))
                                    <span class="form-text m-b-none text-danger">{{ $errors->first('dob') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" placeholder="email" value="@if(old('email')){{old('email')}}@endif"/>
                                @if($errors->has('email'))
                                    <span class="form-text m-b-none text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Page Limit</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="page_limit" placeholder="page limit" min="1" value="@if(old('page_limit')){{old('page_limit')}}@endif"/>
                                @if($errors->has('page_limit'))
                                    <span class="form-text m-b-none text-danger">{{ $errors->first('page_limit') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-sm m-r-lg" type="submit">Save</button>
                                <a href="{{route('user.index')}}" class="btn btn-white btn-sm">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
