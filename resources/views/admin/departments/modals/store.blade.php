<div class="modal fade" id="storeDepartmentModal" tabindex="-1" aria-labelledby="storeDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--bd-green); color: white;">
                <h5 class="modal-title" id="storeDepartmentModalLabel"><i class="fas fa-plus me-2"></i> Add New Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="storeDepartmentForm" action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Department Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required autofocus 
                               placeholder="e.g., Sales, Marketing, IT">
                        
                      
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-bd-primary">
                        <i class="fas fa-save me-1"></i> Save Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>