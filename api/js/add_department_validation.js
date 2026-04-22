// api/js/add_department_validation.js

document.addEventListener('DOMContentLoaded', function() {
    const departmentForm = document.getElementById('departmentForm');
    if (!departmentForm) return;

    const departmentNameInput = document.getElementById('departmentName');
    const collegeSelect = document.getElementById('collegeSelect');

    const departmentNameError = document.getElementById('departmentNameError');
    const collegeSelectError = document.getElementById('collegeSelectError');

    // Function to validate a single field
    function validateField(inputElement, errorElement, errorMessage) {
        // Check for empty string or default select option
        if (inputElement.value.trim() === '' || (inputElement.tagName === 'SELECT' && inputElement.value === '')) {
            errorElement.textContent = errorMessage;
            inputElement.classList.add('is-invalid');
            inputElement.classList.remove('is-valid'); // Ensure valid class is removed
            return false;
        } else {
            errorElement.textContent = '';
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid'); // Add is-valid class for valid fields
            return true;
        }
    }

    // Real-time validation on input/change
    departmentNameInput.addEventListener('input', function() {
        validateField(departmentNameInput, departmentNameError, 'Department Name is required.');
    });

    collegeSelect.addEventListener('change', function() {
        validateField(collegeSelect, collegeSelectError, 'Please select a College.');
    });

    // Form submission
    departmentForm.addEventListener('submit', function(e) {
        // Run all validations
        const isDepartmentNameValid = validateField(departmentNameInput, departmentNameError, 'Department Name is required.');
        const isCollegeSelectedValid = validateField(collegeSelect, collegeSelectError, 'Please select a College.');

        // If any validation fails, prevent form submission
        if (!isDepartmentNameValid || !isCollegeSelectedValid) {
            e.preventDefault(); // Prevent default form submission
            // Optionally, you can add a general message here if needed.
        }
        // If all validations pass, the form will submit naturally.
    });

    // Removed: Initial validation call for all fields on page load.
    // Validation will now only trigger on user interaction or form submission.
});
