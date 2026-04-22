document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmNewPasswordInput = document.getElementById('confirmNewPassword');

    const newPasswordError = document.getElementById('newPasswordError');
    const confirmNewPasswordError = document.getElementById('confirmNewPasswordError');

    const newPasswordSuccessIcon = document.getElementById('newPasswordSuccessIcon');
    const newPasswordErrorIcon = document.getElementById('newPasswordErrorIcon');
    const confirmNewPasswordSuccessIcon = document.getElementById('confirmNewPasswordSuccessIcon');
    const confirmNewPasswordErrorIcon = document.getElementById('confirmNewPasswordErrorIcon');

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

    function validateNewPassword() {
        const value = newPasswordInput.value.trim();
        if (value === '') {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'New Password is required.', false);
            return false;
        } else if (value.length < 8) {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'Password must be at least 8 characters long.', false);
            return false;
        } else if (!/[A-Z]/.test(value)) {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'Password must contain an uppercase letter.', false);
            return false;
        } else if (!/[a-z]/.test(value)) {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'Password must contain a lowercase letter.', false);
            return false;
        } else if (!/[0-9]/.test(value)) {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'Password must contain a number.', false);
            return false;
        } else if (!/[^A-Za-z0-9]/.test(value)) {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, 'Password must contain a special character.', false);
            return false;
        } else {
            displayValidationFeedback(newPasswordInput, newPasswordError, newPasswordSuccessIcon, newPasswordErrorIcon, '', true);
            return true;
        }
    }

    function validateConfirmNewPassword() {
        const newPassword = newPasswordInput.value.trim();
        const confirmNewPassword = confirmNewPasswordInput.value.trim();
        if (confirmNewPassword === '') {
            displayValidationFeedback(confirmNewPasswordInput, confirmNewPasswordError, confirmNewPasswordSuccessIcon, confirmNewPasswordErrorIcon, 'Confirm New Password is required.', false);
            return false;
        } else if (newPassword !== confirmNewPassword) {
            displayValidationFeedback(confirmNewPasswordInput, confirmNewPasswordError, confirmNewPasswordSuccessIcon, confirmNewPasswordErrorIcon, 'Passwords do not match.', false);
            return false;
        } else {
            displayValidationFeedback(confirmNewPasswordInput, confirmNewPasswordError, confirmNewPasswordSuccessIcon, confirmNewPasswordErrorIcon, '', true);
            return true;
        }
    }

    newPasswordInput.addEventListener('input', validateNewPassword);
    confirmNewPasswordInput.addEventListener('input', validateConfirmNewPassword);

    newPasswordInput.addEventListener('blur', validateNewPassword);
    confirmNewPasswordInput.addEventListener('blur', validateConfirmNewPassword);

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const isNewPasswordValid = validateNewPassword();
        const isConfirmNewPasswordValid = validateConfirmNewPassword();

        if (isNewPasswordValid && isConfirmNewPasswordValid) {
            form.submit();
        } else {
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
