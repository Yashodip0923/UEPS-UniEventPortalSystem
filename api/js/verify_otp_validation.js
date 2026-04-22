document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const otpInput = document.getElementById('otp');
    const otpError = document.getElementById('otpError');
    const otpSuccessIcon = document.getElementById('otpSuccessIcon');
    const otpErrorIcon = document.getElementById('otpErrorIcon');

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

    function validateOtp() {
        const value = otpInput.value.trim();
        if (value === '') {
            displayValidationFeedback(otpInput, otpError, otpSuccessIcon, otpErrorIcon, 'OTP is required.', false);
            return false;
        } else if (!/^\d{6}$/.test(value)) {
            displayValidationFeedback(otpInput, otpError, otpSuccessIcon, otpErrorIcon, 'OTP must be a 6-digit number.', false);
            return false;
        } else {
            displayValidationFeedback(otpInput, otpError, otpSuccessIcon, otpErrorIcon, '', true);
            return true;
        }
    }

    otpInput.addEventListener('input', validateOtp);
    otpInput.addEventListener('blur', validateOtp);

    form.addEventListener('submit', function (event) {
        const isOtpValid = validateOtp();

        if (!isOtpValid) {
            event.preventDefault();
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
