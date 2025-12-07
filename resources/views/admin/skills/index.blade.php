@extends('admin.master.master')

@section('title')
Skills Management
@endsection

@section('css')
  
@endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
            
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Skills Management</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold" style="color: var(--bd-green);">Skill List</h6>
            <button class="btn btn-bd-primary btn-sm" data-bs-toggle="modal" data-bs-target="#storeSkillModal">
                <i class="fas fa-plus me-1"></i> Add New Skill
            </button>
        </div>
        <div class="card-body">
            
            <div class="d-flex justify-content-between mb-3">
                <div class="input-group w-50">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search skills by name...">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select id="perPageSelect" class="form-select w-auto">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="skillsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-1">ID</th>
                            <th>Skill Name</th>
                            <th class="col-2">Created At</th>
                            <th class="col-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="4" class="text-center">Loading data...</td></tr>
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

    @include('admin.skills.modals.store')
    @include('admin.skills.modals.update')

</div>
@endsection

@section('script')
@include('admin.skills._partial.script')    
@endsection