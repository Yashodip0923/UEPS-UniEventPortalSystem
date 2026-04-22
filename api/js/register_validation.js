document.addEventListener('contextmenu', event => event.preventDefault());

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    const firstNameInput = document.getElementById('firstName');
    const middleNameInput = document.getElementById('middleName');
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const dobInput = document.getElementById('dob');
    const contactNumberInput = document.getElementById('contactNumber');

    const customCollegeDisplay = document.getElementById('customCollegeDisplay');
    const hiddenCollegeIdInput = document.getElementById('hiddenCollegeId');
    const customCollegeOptionsContainer = hiddenCollegeIdInput.nextElementSibling; // Get the actual options container
    const collegeSearchInput = document.getElementById('collegeSearch');
    const collegeOptionsList = document.getElementById('collegeOptionsList');

    const customDepartmentDisplay = document.getElementById('customDepartmentDisplay');
    const hiddenDepartmentIdInput = document.getElementById('hiddenDepartmentId');
    const customDepartmentOptionsContainer = hiddenDepartmentIdInput.nextElementSibling; // Get the actual options container
    const departmentSearchInput = document.getElementById('departmentSearch');
    const departmentOptionsList = document.getElementById('departmentOptionsList');
    const departmentFieldWrapper = document.getElementById('departmentFieldWrapper');


    const firstNameError = document.getElementById('firstNameError');
    const middleNameError = document.getElementById('middleNameError');
    const lastNameError = document.getElementById('lastNameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const dobError = document.getElementById('dobError');
    const contactNumberError = document.getElementById('contactNumberError');
    const collegeError = document.getElementById('collegeError');
    const departmentError = document.getElementById('departmentError');

    const firstNameSuccessIcon = document.getElementById('firstNameSuccessIcon');
    const firstNameErrorIcon = document.getElementById('firstNameErrorIcon');
    const middleNameSuccessIcon = document.getElementById('middleNameSuccessIcon');
    const middleNameErrorIcon = document.getElementById('middleNameErrorIcon');
    const lastNameSuccessIcon = document.getElementById('lastNameSuccessIcon');
    const lastNameErrorIcon = document.getElementById('lastNameErrorIcon');
    const emailSuccessIcon = document.getElementById('emailSuccessIcon');
    const emailErrorIcon = document.getElementById('emailErrorIcon');
    const passwordSuccessIcon = document.getElementById('passwordSuccessIcon');
    const passwordErrorIcon = document.getElementById('passwordErrorIcon');
    const confirmPasswordSuccessIcon = document.getElementById('confirmPasswordSuccessIcon');
    const confirmPasswordErrorIcon = document.getElementById('confirmPasswordErrorIcon');
    const dobSuccessIcon = document.getElementById('dobSuccessIcon');
    const dobErrorIcon = document.getElementById('dobErrorIcon');
    const contactNumberSuccessIcon = document.getElementById('contactNumberSuccessIcon');
    const contactNumberErrorIcon = document.getElementById('contactNumberErrorIcon');
    const collegeSuccessIcon = document.getElementById('collegeSuccessIcon');
    const collegeErrorIcon = document.getElementById('collegeErrorIcon');
    const departmentSuccessIcon = document.getElementById('departmentSuccessIcon');
    const departmentErrorIcon = document.getElementById('departmentErrorIcon');

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        if (alertContainer) {
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show text-center" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }
    }

    function parseUrlParams() {
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status');
        const message = params.get('message');

        if (status && message) {
            showAlert(status, decodeURIComponent(message));
        }

        firstNameInput.value = params.get('first_name') || '';
        middleNameInput.value = params.get('middle_name') || '';
        lastNameInput.value = params.get('last_name') || '';
        emailInput.value = params.get('email') || '';
        dobInput.value = params.get('dob') || '';
        contactNumberInput.value = params.get('contact_number') || '';

        const preSelectedCollegeId = params.get('college_id') || '';
        if (preSelectedCollegeId) {
            hiddenCollegeIdInput.value = preSelectedCollegeId;
        }

        const preSelectedDepartmentId = params.get('department_id') || '';
        if (preSelectedDepartmentId) {
            hiddenDepartmentIdInput.value = preSelectedDepartmentId;
        }
    }

    function displayValidationFeedback(inputElement, errorElement, successIconElement, errorIconElement, errorMessage, isValid) {
        if (isValid) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
            errorElement.textContent = '';
            if (successIconElement && errorIconElement) {
                successIconElement.classList.add('icon-show');
                errorIconElement.classList.remove('icon-show');
            }
        } else {
            inputElement.classList.remove('is-valid');
            inputElement.classList.add('is-invalid');
            errorElement.textContent = errorMessage;
            if (successIconElement && errorIconElement) {
                successIconElement.classList.remove('icon-show');
                errorIconElement.classList.add('icon-show');
            }
        }
    }

    function validateFirstName() {
        const value = firstNameInput.value.trim();
        if (value === '') {
            displayValidationFeedback(firstNameInput, firstNameError, firstNameSuccessIcon, firstNameErrorIcon, 'First Name is required.', false);
            return false;
        } else if (!/^[a-zA-Z-' ]*$/.test(value)) {
            displayValidationFeedback(firstNameInput, firstNameError, firstNameSuccessIcon, firstNameErrorIcon, 'Only letters, spaces, hyphens, and apostrophes allowed.', false);
            return false;
        } else {
            displayValidationFeedback(firstNameInput, firstNameError, firstNameSuccessIcon, firstNameErrorIcon, '', true);
            return true;
        }
    }

    function validateMiddleName() {
        const value = middleNameInput.value.trim();
        if (value !== '' && !/^[a-zA-Z-' ]*$/.test(value)) {
            displayValidationFeedback(middleNameInput, middleNameError, middleNameSuccessIcon, middleNameErrorIcon, 'Only letters, spaces, hyphens, and apostrophes allowed.', false);
            return false;
        } else {
            displayValidationFeedback(middleNameInput, middleNameError, middleNameSuccessIcon, middleNameErrorIcon, '', true);
            return true;
        }
    }

    function validateLastName() {
        const value = lastNameInput.value.trim();
        if (value === '') {
            displayValidationFeedback(lastNameInput, lastNameError, lastNameSuccessIcon, lastNameErrorIcon, 'Last Name is required.', false);
            return false;
        } else if (!/^[a-zA-Z-' ]*$/.test(value)) {
            displayValidationFeedback(lastNameInput, lastNameError, lastNameSuccessIcon, lastNameErrorIcon, 'Only letters, spaces, hyphens, and apostrophes allowed.', false);
            return false;
        } else {
            displayValidationFeedback(lastNameInput, lastNameError, lastNameSuccessIcon, lastNameErrorIcon, '', true);
            return true;
        }
    }

    function validateEmail() {
        const value = emailInput.value.trim();
        if (value === '') {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, 'Email is required.', false);
            return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, 'Invalid email format.', false);
            return false;
        } else {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, '', true);
            return true;
        }
    }

    function validatePassword() {
        const value = passwordInput.value.trim();
        if (value === '') {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password is required.', false);
            return false;
        } else if (value.length < 8) {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password must be at least 8 characters long.', false);
            return false;
        } else if (!/[A-Z]/.test(value)) {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password must contain an uppercase letter.', false);
            return false;
        } else if (!/[a-z]/.test(value)) {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password must contain a lowercase letter.', false);
            return false;
        } else if (!/[0-9]/.test(value)) {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password must contain a number.', false);
            return false;
        } else if (!/[^A-Za-z0-9]/.test(value)) {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, 'Password must contain a special character.', false);
            return false;
        } else {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, '', true);
            return true;
        }
    }

    function validateConfirmPassword() {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        if (confirmPassword === '') {
            displayValidationFeedback(confirmPasswordInput, confirmPasswordError, confirmPasswordSuccessIcon, confirmPasswordErrorIcon, 'Confirm Password is required.', false);
            return false;
        } else if (password !== confirmPassword) {
            displayValidationFeedback(confirmPasswordInput, confirmPasswordError, confirmPasswordSuccessIcon, confirmPasswordErrorIcon, 'Passwords do not match.', false);
            return false;
        } else {
            displayValidationFeedback(confirmPasswordInput, confirmPasswordError, confirmPasswordSuccessIcon, confirmPasswordErrorIcon, '', true);
            return true;
        }
    }

    function validateDob() {
        const value = dobInput.value;
        if (value === '') {
            displayValidationFeedback(dobInput, dobError, dobSuccessIcon, dobErrorIcon, 'Date of Birth is required.', false);
            return false;
        }
        const today = new Date();
        const birthDate = new Date(value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) {
            displayValidationFeedback(dobInput, dobError, dobSuccessIcon, dobErrorIcon, 'You must be at least 18 years old.', false);
            return false;
        } else {
            displayValidationFeedback(dobInput, dobError, dobSuccessIcon, dobErrorIcon, '', true);
            return true;
        }
    }

    function validateContactNumber() {
        const value = contactNumberInput.value.trim();
        if (value === '') {
            displayValidationFeedback(contactNumberInput, contactNumberError, contactNumberSuccessIcon, contactNumberErrorIcon, 'Contact Number is required.', false);
            return false;
        } else if (!/^\d{10}$/.test(value)) {
            displayValidationFeedback(contactNumberInput, contactNumberError, contactNumberSuccessIcon, contactNumberErrorIcon, 'Contact Number must be 10 digits.', false);
            return false;
        } else {
            displayValidationFeedback(contactNumberInput, contactNumberError, contactNumberSuccessIcon, contactNumberErrorIcon, '', true);
            return true;
        }
    }

    function validateCollege() {
        const value = hiddenCollegeIdInput.value;
        if (value === '') {
            displayValidationFeedback(customCollegeDisplay, collegeError, collegeSuccessIcon, collegeErrorIcon, 'Please select your college.', false);
            return false;
        } else {
            displayValidationFeedback(customCollegeDisplay, collegeError, collegeSuccessIcon, collegeErrorIcon, '', true);
            return true;
        }
    }

    function validateDepartment() {
        const value = hiddenDepartmentIdInput.value;
        if (value === '') {
            displayValidationFeedback(customDepartmentDisplay, departmentError, departmentSuccessIcon, departmentErrorIcon, 'Please select your department.', false);
            return false;
        } else {
            displayValidationFeedback(customDepartmentDisplay, departmentError, departmentSuccessIcon, departmentErrorIcon, '', true);
            return true;
        }
    }

    function renderCollegeOptions(collegesToRender) {
        collegeOptionsList.innerHTML = '';
        const defaultOption = document.createElement('div');
        defaultOption.classList.add('custom-option');
        defaultOption.textContent = 'Select your college';
        defaultOption.dataset.value = '';
        collegeOptionsList.appendChild(defaultOption);

        collegesToRender.forEach(college => {
            const optionDiv = document.createElement('div');
            optionDiv.classList.add('custom-option');
            optionDiv.textContent = college.college_name;
            optionDiv.dataset.value = college.college_id;
            if (String(college.college_id) === String(hiddenCollegeIdInput.value)) {
                optionDiv.classList.add('selected');
                customCollegeDisplay.textContent = college.college_name;
            }
            collegeOptionsList.appendChild(optionDiv);

            optionDiv.addEventListener('click', function() {
                hiddenCollegeIdInput.value = this.dataset.value;
                customCollegeDisplay.textContent = this.textContent;
                closeCustomSelect(customCollegeOptionsContainer, customCollegeDisplay);
                validateCollege();
                filterDepartments(); // Re-filter departments when college changes
                hiddenDepartmentIdInput.value = ''; // Reset department selection
                customDepartmentDisplay.textContent = 'Select your department'; // Reset display text
                // Remove validation feedback for department dropdown immediately after college selection
                customDepartmentDisplay.classList.remove('is-invalid', 'is-valid');
                departmentError.textContent = '';
                departmentSuccessIcon.classList.remove('icon-show');
                departmentErrorIcon.classList.remove('icon-show');
                collegeOptionsList.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                departmentFieldWrapper.style.display = 'block'; // Show department dropdown
            });
        });
    }

    function filterCollegeOptions() {
        const searchText = collegeSearchInput.value.toLowerCase();
        const filteredColleges = allColleges.filter(college =>
            college.college_name.toLowerCase().includes(searchText)
        );
        renderCollegeOptions(filteredColleges);
    }

    function renderDepartmentOptions(departmentsToRender) {
        departmentOptionsList.innerHTML = '';
        const defaultOption = document.createElement('div');
        defaultOption.classList.add('custom-option');
        defaultOption.textContent = 'Select your department';
        defaultOption.dataset.value = '';
        departmentOptionsList.appendChild(defaultOption);

        if (departmentsToRender.length === 0 && hiddenCollegeIdInput.value !== '') {
            const noDeptOption = document.createElement('div');
            noDeptOption.classList.add('custom-option');
            noDeptOption.textContent = 'No departments found for this college';
            noDeptOption.dataset.value = '';
            departmentOptionsList.appendChild(noDeptOption);
        }

        departmentsToRender.forEach(dept => {
            const optionDiv = document.createElement('div');
            optionDiv.classList.add('custom-option');
            optionDiv.textContent = dept.department_name;
            optionDiv.dataset.value = dept.department_id;
            if (String(dept.department_id) === String(hiddenDepartmentIdInput.value)) {
                optionDiv.classList.add('selected');
                customDepartmentDisplay.textContent = dept.department_name;
            }
            departmentOptionsList.appendChild(optionDiv);

            optionDiv.addEventListener('click', function() {
                hiddenDepartmentIdInput.value = this.dataset.value;
                customDepartmentDisplay.textContent = this.textContent;
                closeCustomSelect(customDepartmentOptionsContainer, customDepartmentDisplay);
                validateDepartment();
                departmentOptionsList.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }

    function filterDepartmentOptions() {
        const searchText = departmentSearchInput.value.toLowerCase();
        const selectedCollegeId = hiddenCollegeIdInput.value;
        const filteredDepartments = allDepartments.filter(dept =>
            String(dept.college_id) === String(selectedCollegeId) &&
            dept.department_name.toLowerCase().includes(searchText)
        );
        renderDepartmentOptions(filteredDepartments);
    }

    function openCustomSelect(container, searchInput) {
        container.style.display = 'block';
        searchInput.value = '';
        if (container === customCollegeOptionsContainer) {
            renderCollegeOptions(allColleges);
        } else if (container === customDepartmentOptionsContainer) {
            const selectedCollegeId = hiddenCollegeIdInput.value;
            const departmentsForSelectedCollege = allDepartments.filter(dept => String(dept.college_id) === String(selectedCollegeId));
            renderDepartmentOptions(departmentsForSelectedCollege);
        }
        searchInput.focus();
    }

    function closeCustomSelect(container, displayElement) {
        container.style.display = 'none';
        // Only validate on blur if an option hasn't been selected yet
        if (displayElement === customCollegeDisplay && hiddenCollegeIdInput.value === '') {
            validateCollege();
        } else if (displayElement === customDepartmentDisplay && hiddenDepartmentIdInput.value === '') {
            validateDepartment();
        }
    }

    function filterDepartments() {
        const selectedCollegeId = hiddenCollegeIdInput.value;
        const departmentsForSelectedCollege = allDepartments.filter(dept => String(dept.college_id) === String(selectedCollegeId));

        if (selectedCollegeId === '') {
            customDepartmentDisplay.textContent = 'Select your college first';
            hiddenDepartmentIdInput.value = '';
            renderDepartmentOptions([]); // Clear department options
            customDepartmentDisplay.classList.remove('is-invalid', 'is-valid'); // Ensure cleared
            departmentError.textContent = '';
            departmentSuccessIcon.classList.remove('icon-show');
            departmentErrorIcon.classList.remove('icon-show');
            departmentFieldWrapper.style.display = 'none'; // Keep department dropdown hidden
        } else {
            customDepartmentDisplay.textContent = 'Select your department';
            renderDepartmentOptions(departmentsForSelectedCollege);
            departmentFieldWrapper.style.display = 'block'; // Show department dropdown

            // Explicitly clear validation state when it becomes visible
            customDepartmentDisplay.classList.remove('is-invalid', 'is-valid');
            departmentError.textContent = '';
            departmentSuccessIcon.classList.remove('icon-show');
            departmentErrorIcon.classList.remove('icon-show');

            // If a department was pre-selected and it's valid for the new college, keep it. Otherwise, clear.
            const preSelectedDepartmentId = new URLSearchParams(window.location.search).get('department_id') || '';
            const isValidPreSelectedDept = departmentsForSelectedCollege.some(dept => String(dept.department_id) === String(preSelectedDepartmentId));

            if (preSelectedDepartmentId && isValidPreSelectedDept) {
                hiddenDepartmentIdInput.value = preSelectedDepartmentId;
                const preSelectedDept = departmentsForSelectedCollege.find(dept => String(dept.department_id) === String(preSelectedDepartmentId));
                customDepartmentDisplay.textContent = preSelectedDept.department_name;
                // Only apply valid feedback if a valid department is pre-selected
                displayValidationFeedback(customDepartmentDisplay, departmentError, departmentSuccessIcon, departmentErrorIcon, '', true);
            } else {
                hiddenDepartmentIdInput.value = '';
                customDepartmentDisplay.textContent = 'Select your department';
                // Validation feedback already removed above, no need to repeat here
            }
        }
    }

    parseUrlParams();

    // Initial setup for College Dropdown - do NOT validate immediately
    if (hiddenCollegeIdInput.value) {
        const preSelectedCollege = allColleges.find(college => String(college.college_id) === String(hiddenCollegeIdInput.value));
        if (preSelectedCollege) {
            customCollegeDisplay.textContent = preSelectedCollege.college_name;
            // No initial validation feedback here
            customCollegeDisplay.classList.remove('is-invalid', 'is-valid');
            collegeError.textContent = '';
            collegeSuccessIcon.classList.remove('icon-show');
            collegeErrorIcon.classList.remove('icon-show');
        } else {
            customCollegeDisplay.textContent = 'Select your college';
            hiddenCollegeIdInput.value = '';
            // No initial validation feedback here
            customCollegeDisplay.classList.remove('is-invalid', 'is-valid');
            collegeError.textContent = '';
            collegeSuccessIcon.classList.remove('icon-show');
            collegeErrorIcon.classList.remove('icon-show');
        }
    } else {
        customCollegeDisplay.textContent = 'Select your college';
        // No initial validation feedback here
        customCollegeDisplay.classList.remove('is-invalid', 'is-valid');
        collegeError.textContent = '';
        collegeSuccessIcon.classList.remove('icon-show');
        collegeErrorIcon.classList.remove('icon-show');
    }
    renderCollegeOptions(allColleges);

    // Initial setup for Department Dropdown - it should be hidden by default
    filterDepartments(); // This will also handle pre-selection for departments and hide if no college selected

    customCollegeDisplay.addEventListener('click', function(event) {
        event.stopPropagation();
        if (customCollegeOptionsContainer.style.display === 'none' || customCollegeOptionsContainer.style.display === '') {
            openCustomSelect(customCollegeOptionsContainer, collegeSearchInput);
        } else {
            closeCustomSelect(customCollegeOptionsContainer, customCollegeDisplay);
        }
    });

    collegeSearchInput.addEventListener('input', filterCollegeOptions);

    customDepartmentDisplay.addEventListener('click', function(event) {
        event.stopPropagation();
        if (hiddenCollegeIdInput.value === '') {
            showAlert('danger', 'Please select a college first.');
            return;
        }
        if (customDepartmentOptionsContainer.style.display === 'none' || customDepartmentOptionsContainer.style.display === '') {
            openCustomSelect(customDepartmentOptionsContainer, departmentSearchInput);
        } else {
            closeCustomSelect(customDepartmentOptionsContainer, customDepartmentDisplay);
        }
    });

    departmentSearchInput.addEventListener('input', filterDepartmentOptions);


    document.addEventListener('click', function(event) {
        if (!customCollegeOptionsContainer.contains(event.target) && !customCollegeDisplay.contains(event.target)) {
            closeCustomSelect(customCollegeOptionsContainer, customCollegeDisplay);
        }
        if (!customDepartmentOptionsContainer.contains(event.target) && !customDepartmentDisplay.contains(event.target)) {
            closeCustomSelect(customDepartmentOptionsContainer, customDepartmentDisplay);
        }
    });

    firstNameInput.addEventListener('input', validateFirstName);
    middleNameInput.addEventListener('input', validateMiddleName);
    lastNameInput.addEventListener('input', validateLastName);
    emailInput.addEventListener('input', validateEmail);
    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validateConfirmPassword);
    dobInput.addEventListener('input', validateDob);
    contactNumberInput.addEventListener('input', validateContactNumber);

    // No direct change event for custom selects, validation is tied to click on options and blur of display
    customCollegeDisplay.addEventListener('blur', validateCollege);
    customDepartmentDisplay.addEventListener('blur', validateDepartment);


    firstNameInput.addEventListener('blur', validateFirstName);
    middleNameInput.addEventListener('blur', validateMiddleName);
    lastNameInput.addEventListener('blur', validateLastName);
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    dobInput.addEventListener('blur', validateDob);
    contactNumberInput.addEventListener('blur', validateContactNumber);

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const isFirstNameValid = validateFirstName();
        const isMiddleNameValid = validateMiddleName();
        const isLastNameValid = validateLastName();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();
        const isDobValid = validateDob();
        const isContactNumberValid = validateContactNumber();
        const isCollegeValid = validateCollege(); // Validate on submit
        const isDepartmentValid = validateDepartment(); // Validate on submit

        if (isFirstNameValid && isMiddleNameValid && isLastNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid && isDobValid && isContactNumberValid && isCollegeValid && isDepartmentValid) {
            form.submit();
        } else {
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
