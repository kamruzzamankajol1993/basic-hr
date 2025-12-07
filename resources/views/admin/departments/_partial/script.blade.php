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