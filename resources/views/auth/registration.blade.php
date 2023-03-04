@extends('layouts.auth')

@section('page_title') {{ env('APP_NAME') }} | Registration @endsection

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                {{--<h1 class="logo-name">IN+</h1>--}}
            </div>
            <h3>Welcome to {{ env('APP_NAME') }}</h3>
            <p>Registration</p>
            @if(Session::has('message'))
                <div class="alert alert-{{ Session::get('type') }}">{{ Session::get('message') }}</div>
            @endif
            <form method="post" class="m-t" role="form" action="{{ route('auth.register') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="@if(old('first_name')){{old('first_name')}}@endif"/>
                    @if($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="@if(old('last_name')){{old('last_name')}}@endif" />
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="@if(old('email')){{old('email')}}@endif"/>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="date" name="dob" class="form-control" placeholder="Date of Birth" value="@if(old('dob')){{old('dob')}}@endif"/>
                    @if ($errors->has('dob'))
                        <span class="text-danger">{{ $errors->first('dob') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" />
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmation Password" />
                </div>

                <div class="form-group">
                    {!! htmlFormSnippet() !!}
                    @error('g-recaptcha-response')
                    <div>
                        <small class="text-danger">{{ $message }}</small>
                    </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>

                <p class="text-muted text-center"><small>Already have an account</small></p>
                <a class="btn btn-sm btn-white btn-block" href="{{ route('auth.login.page') }}">Back to Login</a>
            </form>
        </div>
    </div>
@endsection
