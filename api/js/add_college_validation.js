document.addEventListener('DOMContentLoaded', function () {
    const addCollegeForm = document.getElementById('addCollegeForm');
    const collegeNameInput = document.getElementById('collegeName');
    const collegeCodeInput = document.getElementById('collegeCode');
    const addressInput = document.getElementById('address');

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

    function validateField(inputElement, errorElement) {
        let isValid = true;
        let errorMessage = '';

        inputElement.classList.remove('is-invalid', 'is-valid');
        errorElement.textContent = '';

        if (inputElement.hasAttribute('required') && inputElement.value.trim() === '') {
            isValid = false;
            errorMessage = 'This field is required.';
        } else {
            switch (inputElement.id) {
                case 'collegeName':
                    if (inputElement.value.trim().length < 3) {
                        isValid = false;
                        errorMessage = 'College Name must be at least 3 characters long.';
                    }
                    break;
                case 'collegeCode':
                    if (inputElement.value.trim().length < 2 || !/^[A-Z0-9]+$/.test(inputElement.value.trim())) {
                        isValid = false;
                        errorMessage = 'College Code must be at least 2 alphanumeric characters (uppercase).';
                    }
                    break;
                case 'address':
                    if (inputElement.value.trim().length < 10) {
                        isValid = false;
                        errorMessage = 'Address must be at least 10 characters long.';
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

    collegeNameInput.addEventListener('input', () => validateField(collegeNameInput, document.getElementById('collegeNameError')));
    collegeCodeInput.addEventListener('input', () => validateField(collegeCodeInput, document.getElementById('collegeCodeError')));
    addressInput.addEventListener('input', () => validateField(addressInput, document.getElementById('addressError')));

    addCollegeForm.addEventListener('submit', function (event) {
        let formIsValid = true;

        formIsValid = validateField(collegeNameInput, document.getElementById('collegeNameError')) && formIsValid;
        formIsValid = validateField(collegeCodeInput, document.getElementById('collegeCodeError')) && formIsValid;
        formIsValid = validateField(addressInput, document.getElementById('addressError')) && formIsValid;

        if (!formIsValid) {
            event.preventDefault();
            displayAlert('error', 'Please correct the errors in the form.');
        }
    });
});
