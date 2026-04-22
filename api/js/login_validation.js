document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    const emailSuccessIcon = document.getElementById('emailSuccessIcon');
    const emailErrorIcon = document.getElementById('emailErrorIcon');
    const passwordSuccessIcon = document.getElementById('passwordSuccessIcon');
    const passwordErrorIcon = document.getElementById('passwordErrorIcon');

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
        } else {
            displayValidationFeedback(passwordInput, passwordError, passwordSuccessIcon, passwordErrorIcon, '', true);
            return true;
        }
    }

    emailInput.addEventListener('input', validateEmail);
    passwordInput.addEventListener('input', validatePassword);

    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);

    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();

        if (!isEmailValid || !isPasswordValid) {
            event.preventDefault();
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});