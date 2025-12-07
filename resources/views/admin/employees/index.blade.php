@extends('admin.master.master')

@section('title', 'Employees List')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentSearch = '';
    let currentDepartment = '';
    let perPage = $('#perPageSelect').val();
    let lastResponse = {}; 

    function debounce(func, delay) {
        let timer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
    }

    function fetchData(page = 1, search = '', department_id = '', per_page = 10) {
        currentPage = page;
        currentSearch = search;
        currentDepartment = department_id;
        perPage = per_page;
        
        $('#tableBody').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i> Loading data...</td></tr>');

        $.ajax({
            url: '{{ route('employees.data') }}',
            method: 'GET',
            data: { page: page, search: search, department_id: department_id, per_page: per_page },
            success: function(response) {
                lastResponse = response; 
                let html = '';
                if (response.data.length > 0) {
                    response.data.forEach(emp => {
                        const departmentName = emp.department ? emp.department.name : 'N/A';
                        html += `
                            <tr>
                                <td>${emp.id}</td>
                                <td>${emp.first_name} ${emp.last_name}</td>
                                <td>${emp.email}</td>
                                <td>${departmentName}</td>
                                <td>
                                    <a href="/employees/${emp.id}" class="btn btn-sm btn-secondary me-1" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/employees/${emp.id}/edit" class="btn btn-sm btn-info me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${emp.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center">No employees found.</td></tr>';
                }
                
                $('#tableBody').html(html);
                renderPagination(response);
            },
            error: function(xhr) {
                $('#tableBody').html('<tr><td colspan="5" class="text-center text-danger">Error loading data.</td></tr>');
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    }

    function renderPagination(response) {
        let links = '';
        const totalPages = response.last_page;
        const currentPage = response.current_page;
        
        if (totalPages > 1) {
            links += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
            </li>`;

            for (let i = 1; i <= totalPages; i++) {
                links += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            links += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
            </li>`;
        }
        
        $('#paginationLinks').html(links);
        
        const from = (response.total > 0) ? response.from : 0;
        const to = (response.total > 0) ? response.to : 0;
        $('#paginationInfo').text(`Showing ${from} to ${to} of ${response.total} entries`);
    }

    // Initial load
    fetchData();

    // --- Filter Handlers ---
    $('#searchInput').on('keyup', debounce(function() {
        fetchData(1, $(this).val(), currentDepartment, perPage);
    }, 500)); 

    $('#departmentFilter').on('change', function() {
        fetchData(1, currentSearch, $(this).val(), perPage);
    });

    $('#perPageSelect').on('change', function() {
        fetchData(1, currentSearch, currentDepartment, $(this).val());
    });
    
    // Pagination Click Handler
    $(document).on('click', '#paginationLinks a.page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        
        if (page > 0 && page <= lastResponse.last_page && page !== lastResponse.current_page) {
             fetchData(page, currentSearch, currentDepartment, perPage);
        }
    });

    // SweetAlert Delete Handler
    $(document).on('click', '.delete-btn', function() {
        const empId = $(this).data('id');
        const deleteUrl = '{{ url('employees') }}/' + empId; // Use URL helper for cleaner routing here

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = deleteUrl;
                form.method = 'POST'; 
                form.style.display = 'none';
                
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

});
</script>
@endsection