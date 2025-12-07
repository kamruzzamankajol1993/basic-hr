<script>

$(document).ready(function() {
    const skillOptions = `@foreach($skills as $skill) <option value="{{ $skill->id }}">{{ $skill->name }}</option> @endforeach`;
    const employeeId = $('#email').data('employee-id'); 

   

    function updateSkillOptions() {
        const selectedSkillIds = $('#skills-container select').map(function() {
            return $(this).val();
        }).get().filter(id => id !== ""); 
        $('#skills-container select').each(function() {
            const currentSelect = $(this);
            const currentSkillId = currentSelect.val(); 

            currentSelect.find('option').prop('disabled', false)
            selectedSkillIds.forEach(selectedId => {
             
                if (selectedId && selectedId !== currentSkillId) {
                    currentSelect.find('option[value="' + selectedId + '"]').prop('disabled', true);
                }
            });
        });
    }

    function addSkillField() {
        const newSkillHtml = `
            <div class="input-group mb-2 skill-group">
                <select name="skills[]" class="form-select" required>
                    <option value="">Select Skill</option>
                    ${skillOptions}
                </select>
                <button type="button" class="btn btn-danger remove-skill"><i class="fas fa-times"></i></button>
            </div>
        `;
        $('#skills-container').append(newSkillHtml);
        updateSkillOptions(); 
    }
    
    // Add button handler
    $('#add-skill-btn').on('click', function() {
        addSkillField();
    });

    // Handle change on ANY skill select dropdown
    $(document).on('change', '#skills-container select', updateSkillOptions);

    // Remove button handler (delegated)
    $(document).on('click', '.remove-skill', function() {
        $(this).closest('.skill-group').remove();
       
        updateSkillOptions(); 
    });

    updateSkillOptions();
    
    // --- End of New Skill Logic ---

    let emailCheckTimer;
    const emailInput = $('#email');
    const emailHelp = $('#emailHelp');

    emailInput.on('keyup', function() {
        clearTimeout(emailCheckTimer);
        const email = $(this).val();

        if (email.length < 5 || !email.includes('@')) {
            emailHelp.text('Enter a valid email address.');
            emailHelp.removeClass('text-success text-danger');
            emailInput.removeClass('is-valid is-invalid');
            return;
        }

        emailHelp.html('<i class="fas fa-spinner fa-spin me-1"></i> Checking uniqueness...');
        emailHelp.removeClass('text-success text-danger');
        emailInput.removeClass('is-valid is-invalid');

        emailCheckTimer = setTimeout(function() {
            $.ajax({
                url: '{{ route('employees.check.email') }}',
                method: 'POST',
                data: { 
                    email: email, 
                    id: employeeId, 
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    if (response.is_unique) {
                        emailHelp.text('Email is available.');
                        emailHelp.removeClass('text-danger').addClass('text-success');
                        emailInput.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        emailHelp.text('Email is already taken by another employee.');
                        emailHelp.removeClass('text-success').addClass('text-danger');
                        emailInput.removeClass('is-valid').addClass('is-invalid');
                    }
                },
                error: function() {
                    emailHelp.text('Error checking email.');
                    emailHelp.addClass('text-danger');
                }
            });
        }, 800);
    });
    emailInput.trigger('keyup'); 

});
</script>