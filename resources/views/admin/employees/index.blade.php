@extends('admin.master.master')

@section('title', 'Employees List')

@section('css')
   
@endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
            
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Employees Management</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold" style="color: var(--bd-green);">Employee List</h6>
            <a href="{{ route('employees.create') }}" class="btn btn-bd-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Employee
            </a>
        </div>
        <div class="card-body">
            
            <div class="d-flex justify-content-between mb-3">
                <div class="input-group w-30 me-3">
                    <span class="input-group-text">Filter by Dept</span>
                    <select id="departmentFilter" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="input-group w-50">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name or email...">
                    <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                </div>

                <select id="perPageSelect" class="form-select w-auto ms-3">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-1">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th class="col-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="5" class="text-center">Loading data...</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="paginationInfo" class="small text-muted"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationLinks">
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
@include('admin.employees._partial.indexScript')
@endsection