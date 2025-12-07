@extends('admin.master.master')

@section('title')
Departments Management
@endsection

{{-- Add any specific CSS needed for this page here --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
            
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Departments Management</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold" style="color: var(--bd-green);">Department List</h6>
            <button class="btn btn-bd-primary btn-sm" data-bs-toggle="modal" data-bs-target="#storeDepartmentModal">
                <i class="fas fa-plus me-1"></i> Add New Department
            </button>
        </div>
        <div class="card-body">
            
            <div class="d-flex justify-content-between mb-3">
                <div class="input-group w-50">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search departments by name...">
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
                <table class="table table-bordered table-striped" id="departmentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-1">ID</th>
                            <th>Department Name</th>
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

    @include('admin.departments.modals.store')
    @include('admin.departments.modals.update')

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentSearch = '';
    let perPage = $('#perPageSelect').val();
    let lastResponse = {}; 
    let typingTimer; // Timer for debouncing

    // Debounce function to limit the rate of execution
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

    // ---------------------- Flash Messages & Validation Errors ----------------------
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
    
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: 'Please check the form fields for errors.',
            confirmButtonText: 'OK'
        });
        
        @if ($errors->has('update_name'))
            $('#updateDepartmentModal').modal('show');
        @endif
        @if ($errors->has('name') && !isset($errors->messages()['update_name'])) 
            $('#storeDepartmentModal').modal('show');
        @endif
    @endif


    // ---------------------- 1. AJAX Data Fetching ----------------------
    function fetchData(page = 1, search = '', per_page = 10) {
        currentPage = page;
        currentSearch = search;
        perPage = per_page;
        
        $('#tableBody').html('<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i> Loading data...</td></tr>');

        $.ajax({
            url: '{{ route('departments.data') }}',
            method: 'GET',
            data: { page: page, search: search, per_page: per_page },
            success: function(response) {
                lastResponse = response; 
                let html = '';
                if (response.data.length > 0) {
                    response.data.forEach(dept => {
                        const createdAt = new Date(dept.created_at).toLocaleDateString('en-US', {
                            year: 'numeric', month: 'short', day: 'numeric'
                        });

                        html += `
                            <tr>
                                <td>${dept.id}</td>
                                <td>${dept.name}</td>
                                <td>${createdAt}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn me-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateDepartmentModal"
                                            data-id="${dept.id}"
                                            data-name="${dept.name}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${dept.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center">No departments found.</td></tr>';
                }
                
                $('#tableBody').html(html);
                renderPagination(response);
            },
            error: function(xhr) {
                $('#tableBody').html('<tr><td colspan="4" class="text-center text-danger">Error loading data.</td></tr>');
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    }

    // ---------------------- 2. Pagination Rendering ----------------------
    function renderPagination(response) {
        let links = '';
        const totalPages = response.last_page;
        const currentPage = response.current_page;
        
        if (totalPages > 1) {
            // Previous Link
            links += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
            </li>`;

          
            for (let i = 1; i <= totalPages; i++) {
                links += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            // Next Link
            links += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
            </li>`;
        }
        
        $('#paginationLinks').html(links);
        
        // Pagination Info
        const from = (response.total > 0) ? (currentPage - 1) * perPage + 1 : 0;
        const to = Math.min(currentPage * perPage, response.total);
        $('#paginationInfo').text(`Showing ${from} to ${to} of ${response.total} entries`);
    }

    // ---------------------- 3. Event Listeners ----------------------

    // Initial load
    fetchData();

    // --- Search Keyup Handler (Debounced) ---
    $('#searchInput').on('keyup', debounce(function() {
        const newSearch = $(this).val();
        fetchData(1, newSearch, perPage);
    }, 500)); // Wait 500ms after the user stops typing

    // Pagination Click Handler (Delegated event)
    $(document).on('click', '#paginationLinks a.page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        
        if (page > 0 && page <= lastResponse.last_page && page !== lastResponse.current_page) {
             fetchData(page, currentSearch, perPage);
        }
    });

    // Per Page Change Handler
    $('#perPageSelect').on('change', function() {
        const newPerPage = $(this).val();
        fetchData(1, currentSearch, newPerPage);
    });

    // SweetAlert Delete Handler (Delegated event listener)
    $(document).on('click', '.delete-btn', function() {
        const deptId = $(this).data('id');
        const deleteUrl = '{{ route('departments.destroy', ['department' => ':id']) }}'.replace(':id', deptId);

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
                // Submit a hidden form to handle the DELETE request
                const form = document.createElement('form');
                form.action = deleteUrl;
                form.method = 'POST'; 
                form.style.display = 'none';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE'; 
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Edit Button Click Handler (Populate Update Modal)
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        $('#update_department_id').val(id);
        $('#update_name').val(name);
        
        // Update the form action URL using the resource update route
        const updateUrl = '{{ route('departments.update', ['department' => ':id']) }}'.replace(':id', id);
        $('#updateDepartmentForm').attr('action', updateUrl);
        
        // Clear previous validation errors if any were shown
        $('#updateDepartmentForm').find('.is-invalid').removeClass('is-invalid');
        $('#updateDepartmentForm').find('.invalid-feedback').remove();
    });

    // Reset modals on hide (essential to clear forms and error states)
    $('.modal').on('hidden.bs.modal', function () {
        // Reset the form in the modal
        $(this).find('form')[0].reset(); 
        
        // Clear any previous invalid/error styling
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    });

});
</script>
@endsection