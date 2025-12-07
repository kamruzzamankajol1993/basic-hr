<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentSearch = '';
    let currentDepartment = '';
    let perPage = $('#perPageSelect').val();
    let lastResponse = {}; 

    // Define base route URLs in JavaScript outside the loop for dynamic use
    const SHOW_ROUTE_BASE = '{{ route('employees.show', ['employee' => 'ID_PLACEHOLDER']) }}';
    const EDIT_ROUTE_BASE = '{{ route('employees.edit', ['employee' => 'ID_PLACEHOLDER']) }}';
    const DELETE_URL_BASE = '{{ url('employees') }}'; // Using url() helper as the base for deletion

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
                        
                        // Dynamically generate the correct routes using the base paths
                        const showUrl = SHOW_ROUTE_BASE.replace('ID_PLACEHOLDER', emp.id);
                        const editUrl = EDIT_ROUTE_BASE.replace('ID_PLACEHOLDER', emp.id);

                        html += `
                            <tr>
                                <td>${emp.id}</td>
                                <td>${emp.first_name} ${emp.last_name}</td>
                                <td>${emp.email}</td>
                                <td>${departmentName}</td>
                                <td>
                                    <a href="${showUrl}" class="btn btn-sm btn-secondary me-1" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="${editUrl}" class="btn btn-sm btn-info me-1" title="Edit">
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
        const deleteUrl = DELETE_URL_BASE + '/' + empId; 

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