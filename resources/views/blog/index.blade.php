@extends('layouts.account')

@section('page_title') {{ env('APP_NAME') }} | Blogs @endsection

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
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Created By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($blogs as $key => $b)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $b->title }}</td>
                                <td>{{ $b->author->last_name }} {{ $b->author->first_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No Records</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @include('layouts.components.table_pagination', ['paginator' => $blogs])
            </div>
        </div>
    </div>
@endsection
