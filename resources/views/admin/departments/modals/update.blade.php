<div class="modal fade" id="updateDepartmentModal" tabindex="-1" aria-labelledby="updateDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--bd-green); color: white;">
                <h5 class="modal-title" id="updateDepartmentModalLabel"><i class="fas fa-edit me-2"></i> Edit Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="updateDepartmentForm" method="POST">
                @csrf
                @method('PUT') {{-- Required for resource update route --}}
                
                {{-- Hidden field to store department ID (though not strictly needed if ID is in the URL) --}}
                <input type="hidden" name="department_id" id="update_department_id"> 
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="update_name" class="form-label fw-bold">Department Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="update_name" 
                               name="name" 
                               required 
                               placeholder="Enter new name">
                        
                        {{-- Display validation error if present (on redirect back) --}}
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt me-1"></i> Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>