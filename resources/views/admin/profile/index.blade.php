@extends('admin.master.master')

@section('title', 'User Profile')

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
            
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">My Profile</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold" style="color: var(--bd-green);">User Information</h6>
           
        </div>
        <div class="card-body">
            
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <i class="fas fa-user-circle fa-8x text-secondary"></i>
                    <h5 class="mt-3 fw-bold">{{ $user->name }}</h5>
                </div>
                
                <div class="col-md-9">
                    <table class="table table-sm table-striped detail-table">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Full Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>Administrator</td>
                            </tr>
                            <tr>
                                <th>Account Created</th>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $user->updated_at->format('M d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')

@endsection