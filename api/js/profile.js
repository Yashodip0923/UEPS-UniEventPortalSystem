document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

function displayAlert(status, message) {
    const alertContainer = document.getElementById('alertContainer');
    if (alertContainer) {
        const alertClass = status === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show text-center" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertContainer.innerHTML = alertHtml;
    }
}

if (successMessage) {
    displayAlert('success', successMessage);
}
if (errorMessage) {
    displayAlert('error', errorMessage);
}

document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.getElementById('profileForm');
    const firstNameInput = document.getElementById('firstName');
    const middleNameInput = document.getElementById('middleName');
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('email');
    const dobInput = document.getElementById('dob');
    const contactNumberInput = document.getElementById('contactNumber');
    const profilePictureInput = document.getElementById('profilePicture');
    const profilePreview = document.getElementById('profilePreview');

    function toggleOptions(optionsContainer, displayElement) {
        optionsContainer.style.display = optionsContainer.style.display === 'block' ? 'none' : 'block';
        if (optionsContainer.style.display === 'block') {
            const rect = displayElement.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            if (spaceBelow < optionsContainer.offsetHeight && rect.top > optionsContainer.offsetHeight) {
                optionsContainer.style.top = 'auto';
                optionsContainer.style.bottom = '100%';
            } else {
                optionsContainer.style.top = '100%';
                optionsContainer.style.bottom = 'auto';
            }
        }
    }

    profilePictureInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function enableEditing(fieldId) {
        const inputElement = document.getElementById(fieldId);
        if (inputElement) {
            if (inputElement.tagName === 'INPUT') {
                inputElement.removeAttribute('readonly');
                inputElement.setAttribute('placeholder', inputElement.value);
                inputElement.style.setProperty('background-color', '', 'important');
                inputElement.style.setProperty('cursor', '', 'important');
            }
            else if (inputElement.classList.contains('custom-select-display')) {
                inputElement.classList.remove('readonly-display');
                inputElement.style.setProperty('pointer-events', 'auto', 'important');
                inputElement.style.setProperty('background-color', '', 'important');
                inputElement.style.setProperty('cursor', '', 'important');
            }
            inputElement.focus();
        }
    }

    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const targetId = this.dataset.target;
            enableEditing(targetId);
        });
    });

    function validateField(inputElement, errorElement) {
        let isValid = true;
        let errorMessage = '';
        const value = inputElement.value || inputElement.textContent.trim(); 

        inputElement.classList.remove('is-invalid', 'is-valid');
        errorElement.textContent = '';

        if (inputElement.hasAttribute('required') && actualValue === '') {
            isValid = false;
            errorMessage = 'This field is required.';
        } else {
            switch (inputElement.id) {
                case 'firstName':
                case 'middleName':
                case 'lastName':
                    if (inputElement.id !== 'middleName' && value.trim() === '') {
                        isValid = false;
                        errorMessage = 'Name is required.';
                    } else if (value.trim() !== '' && !/^[a-zA-Z-' ]*$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Only letters, spaces, hyphens, and apostrophes allowed.';
                    }
                    break;
                case 'email':
                    if (value.trim() === '') {
                        isValid = false;
                        errorMessage = 'Email is required.';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Invalid email format.';
                    }
                    break;
                case 'dob':
                    if (value.trim() === '') {
                        isValid = false;
                        errorMessage = 'Date of Birth is required.';
                    } else {
                        const today = new Date();
                        const birthDate = new Date(value);
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        if (age < 18) {
                            isValid = false;
                            errorMessage = 'You must be at least 18 years old.';
                        }
                    }
                    break;
                case 'contactNumber':
                    if (value.trim() === '') {
                        isValid = false;
                        errorMessage = 'Contact Number is required.';
                    } else if (!/^\d{10}$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Contact Number must be 10 digits.';
                    }
                    break;
            }
        }

        if (!isValid) {
            inputElement.classList.add('is-invalid');
            errorElement.textContent = errorMessage;
        } else {
            inputElement.classList.add('is-valid');
        }
        return isValid;
    }

    firstNameInput.addEventListener('input', () => validateField(firstNameInput, document.getElementById('firstNameError')));
    middleNameInput.addEventListener('input', () => validateField(middleNameInput, document.getElementById('middleNameError')));
    lastNameInput.addEventListener('input', () => validateField(lastNameInput, document.getElementById('lastNameError')));
    emailInput.addEventListener('input', () => validateField(emailInput, document.getElementById('emailError')));
    dobInput.addEventListener('input', () => validateField(dobInput, document.getElementById('dobError')));
    contactNumberInput.addEventListener('input', () => validateField(contactNumberInput, document.getElementById('contactNumberError')));
    
    profileForm.addEventListener('submit', function (event) {
        let formIsValid = true;

        formIsValid = validateField(firstNameInput, document.getElementById('firstNameError')) && formIsValid;
        formIsValid = validateField(middleNameInput, document.getElementById('middleNameError')) && formIsValid;
        formIsValid = validateField(lastNameInput, document.getElementById('lastNameError')) && formIsValid;
        formIsValid = validateField(emailInput, document.getElementById('emailError')) && formIsValid;
        formIsValid = validateField(dobInput, document.getElementById('dobError')) && formIsValid;
        formIsValid = validateField(contactNumberInput, document.getElementById('contactNumberError')) && formIsValid;

        if (!formIsValid) {
            event.preventDefault();
            displayAlert('error', 'Please correct the errors in the form.');
        }
    });

    validateField(firstNameInput, document.getElementById('firstNameError'));
    validateField(middleNameInput, document.getElementById('middleNameError'));
    validateField(lastNameInput, document.getElementById('lastNameError'));
    validateField(emailInput, document.getElementById('emailError'));
    validateField(dobInput, document.getElementById('dobError'));
    validateField(contactNumberInput, document.getElementById('contactNumberError'));
});
