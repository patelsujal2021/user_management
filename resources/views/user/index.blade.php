@extends('layouts.account')

@section('page_title') {{ env('APP_NAME') }} | Users @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-{{ Session::get('type') }}">{{ Session::get('message') }}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Users</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Add New User</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Date of Birth</th>
                                <th>Email</th>
                                <th>Page Limit</th>
                                <th>Join Date</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $key => $u)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $u->last_name }} {{ $u->first_name }}</td>
                                <td>{{ $u->dob }}</td>
                                <td>
                                    {{ $u->email }}
                                    @if(!is_null($u->email_verified_at))
                                    <i class="fa fa-check-circle text-navy"></i>
                                    @endif
                                </td>
                                <td>{{ $u->page_limit }}</td>
                                <td>{{ $u->created_at }}</td>
                                <td>
                                    <form action="{{ route('user.destroy',$u->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('user.edit',$u->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No Records</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
