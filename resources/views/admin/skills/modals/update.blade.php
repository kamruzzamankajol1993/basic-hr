<div class="modal fade" id="updateSkillModal" tabindex="-1" aria-labelledby="updateSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--bd-green); color: white;">
                <h5 class="modal-title" id="updateSkillModalLabel"><i class="fas fa-edit me-2"></i> Edit Skill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="updateSkillForm" method="POST">
                @csrf
                @method('PUT') 
                
                <input type="hidden" name="skill_id" id="update_skill_id"> 
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="update_name_skill" class="form-label fw-bold">Skill Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="update_name_skill" 
                               name="name" 
                               required 
                               placeholder="Enter new name">
                        
                    
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
                        <i class="fas fa-sync-alt me-1"></i> Update Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>