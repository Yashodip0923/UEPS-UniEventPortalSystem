// api/js/add_user_validation.js

document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    if (!userForm) return;

    const firstNameInput = document.getElementById('firstName');
    const middleNameInput = document.getElementById('middleName'); // Middle name is optional
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const dobInput = document.getElementById('dob');
    const contactNumberInput = document.getElementById('contactNumber');
    const collegeIdSelect = document.getElementById('collegeId');
    const deptIdSelect = document.getElementById('deptId'); // Department is optional
    const roleIdSelect = document.getElementById('roleId');
    // const photoUrlInput = document.getElementById('photoUrl'); // Removed, now file input

    const firstNameError = document.getElementById('firstNameError');
    const middleNameError = document.getElementById('middleNameError');
    const lastNameError = document.getElementById('lastNameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const dobError = document.getElementById('dobError');
    const contactNumberError = document.getElementById('contactNumberError');
    const collegeIdError = document.getElementById('collegeIdError');
    const deptIdError = document.getElementById('deptIdError');
    const roleIdError = document.getElementById('roleIdError');
    // const photoUrlError = document.getElementById('photoUrlError'); // Removed, now file input

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

    // Email validation function
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Password validation function (example: at least 6 characters)
    function isValidPassword(password) {
        return password.length >= 6;
    }

    // Contact number validation (example: 10 digits)
    function isValidContactNumber(number) {
        return /^\d{10}$/.test(number);
    }

    // Real-time validation on input/change
    firstNameInput.addEventListener('input', function() {
        validateField(firstNameInput, firstNameError, 'First Name is required.');
    });

    lastNameInput.addEventListener('input', function() {
        validateField(lastNameInput, lastNameError, 'Last Name is required.');
    });

    emailInput.addEventListener('input', function() {
        if (emailInput.value.trim() === '') {
            emailError.textContent = 'Email is required.';
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
        } else if (!isValidEmail(emailInput.value.trim())) {
            emailError.textContent = 'Invalid email format.';
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
        }
    });

    // Password validation only if password fields exist (for add user)
    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', function() {
            if (passwordInput.value.trim() === '') {
                passwordError.textContent = 'Password is required.';
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
            } else if (!isValidPassword(passwordInput.value.trim())) {
                passwordError.textContent = 'Password must be at least 6 characters.';
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
            } else {
                passwordError.textContent = '';
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
            }
            // Also re-validate confirm password if password changes
            if (confirmPasswordInput.value.trim() !== '') {
                validateField(confirmPasswordInput, confirmPasswordError, 'Passwords do not match.');
            }
        });

        confirmPasswordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value.trim() === '') {
                confirmPasswordError.textContent = 'Confirm Password is required.';
                confirmPasswordInput.classList.add('is-invalid');
                confirmPasswordInput.classList.remove('is-valid');
            } else if (confirmPasswordInput.value.trim() !== passwordInput.value.trim()) {
                confirmPasswordError.textContent = 'Passwords do not match.';
                confirmPasswordInput.classList.add('is-invalid');
                confirmPasswordInput.classList.remove('is-valid');
            } else {
                confirmPasswordError.textContent = '';
                confirmPasswordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.add('is-valid');
            }
        });
    }


    dobInput.addEventListener('change', function() {
        validateField(dobInput, dobError, 'Date of Birth is required.');
    });

    contactNumberInput.addEventListener('input', function() {
        if (contactNumberInput.value.trim() === '') {
            contactNumberError.textContent = 'Contact Number is required.';
            contactNumberInput.classList.add('is-invalid');
            contactNumberInput.classList.remove('is-valid');
        } else if (!isValidContactNumber(contactNumberInput.value.trim())) {
            contactNumberError.textContent = 'Contact Number must be 10 digits.';
            contactNumberInput.classList.add('is-invalid');
            contactNumberInput.classList.remove('is-valid');
        } else {
            contactNumberError.textContent = '';
            contactNumberInput.classList.remove('is-invalid');
            contactNumberInput.classList.add('is-valid');
        }
    });

    collegeIdSelect.addEventListener('change', function() {
        validateField(collegeIdSelect, collegeIdError, 'Please select a College.');
    });

    deptIdSelect.addEventListener('change', function() {
        // Department is optional, only validate if a value is selected and it's not empty
        // Or if it's required for certain roles, add that logic here.
        // For now, it's optional, so no error if empty.
        if (deptIdSelect.value.trim() !== '') {
            deptIdSelect.classList.remove('is-invalid');
            deptIdSelect.classList.add('is-valid');
            deptIdError.textContent = '';
        } else {
            deptIdSelect.classList.remove('is-valid');
            deptIdSelect.classList.remove('is-invalid');
            deptIdError.textContent = '';
        }
    });

    roleIdSelect.addEventListener('change', function() {
        validateField(roleIdSelect, roleIdError, 'Please select a Role.');
    });

    // Form submission
    userForm.addEventListener('submit', function(e) {
        let formIsValid = true;

        // Validate all required fields
        formIsValid = validateField(firstNameInput, firstNameError, 'First Name is required.') && formIsValid;
        formIsValid = validateField(lastNameInput, lastNameError, 'Last Name is required.') && formIsValid;
        
        // Email validation
        if (emailInput.value.trim() === '') {
            emailError.textContent = 'Email is required.';
            emailInput.classList.add('is-invalid');
            formIsValid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
            emailError.textContent = 'Invalid email format.';
            emailInput.classList.add('is-invalid');
            formIsValid = false;
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
        }

        // Password validation (only for add user form)
        if (passwordInput && confirmPasswordInput) {
            if (passwordInput.value.trim() === '') {
                passwordError.textContent = 'Password is required.';
                passwordInput.classList.add('is-invalid');
                formIsValid = false;
            } else if (!isValidPassword(passwordInput.value.trim())) {
                passwordError.textContent = 'Password must be at least 6 characters.';
                passwordInput.classList.add('is-invalid');
                formIsValid = false;
            } else {
                passwordError.textContent = '';
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
            }

            if (confirmPasswordInput.value.trim() === '') {
                confirmPasswordError.textContent = 'Confirm Password is required.';
                confirmPasswordInput.classList.add('is-invalid');
                formIsValid = false;
            } else if (confirmPasswordInput.value.trim() !== passwordInput.value.trim()) {
                confirmPasswordError.textContent = 'Passwords do not match.';
                confirmPasswordInput.classList.add('is-invalid');
                formIsValid = false;
            } else {
                confirmPasswordError.textContent = '';
                confirmPasswordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.add('is-valid');
            }
        }

        formIsValid = validateField(dobInput, dobError, 'Date of Birth is required.') && formIsValid;
        
        // Contact Number validation
        if (contactNumberInput.value.trim() === '') {
            contactNumberError.textContent = 'Contact Number is required.';
            contactNumberInput.classList.add('is-invalid');
            formIsValid = false;
        } else if (!isValidContactNumber(contactNumberInput.value.trim())) {
            contactNumberError.textContent = 'Contact Number must be 10 digits.';
            contactNumberInput.classList.add('is-invalid');
            formIsValid = false;
        } else {
            contactNumberError.textContent = '';
            contactNumberInput.classList.remove('is-invalid');
            contactNumberInput.classList.add('is-valid');
        }

        formIsValid = validateField(roleIdSelect, roleIdError, 'Please select a Role.') && formIsValid;
        // College and Department are optional based on current HTML, so not strictly validated here
        // If they become required, add validation here.

        if (!formIsValid) {
            e.preventDefault(); // Prevent form submission if any validation fails
            // No general message needed, as individual field errors are shown.
        }
        // If formIsValid is true, the form will submit normally (PHP will handle redirection).
    });

    // Initial validation for pre-filled fields (e.g., in edit mode)
    // Removed initial validation that caused errors on page load.
    // Validation will now only trigger on user interaction or form submission.
});
