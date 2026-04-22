document.addEventListener('contextmenu', event => event.preventDefault());

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    const nameInput = document.getElementById('contactName');
    const emailInput = document.getElementById('contactEmail');
    const subjectInput = document.getElementById('contactSubject');
    const messageInput = document.getElementById('contactMessage');

    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const subjectError = document.getElementById('subjectError');
    const messageError = document.getElementById('messageError');

    const nameSuccessIcon = document.getElementById('nameSuccessIcon');
    const nameErrorIcon = document.getElementById('nameErrorIcon');
    const emailSuccessIcon = document.getElementById('emailSuccessIcon');
    const emailErrorIcon = document.getElementById('emailErrorIcon');
    const subjectSuccessIcon = document.getElementById('subjectSuccessIcon');
    const subjectErrorIcon = document.getElementById('subjectErrorIcon');

    function validateName() {
        const name = nameInput.value.trim();
        if (name === '') {
            displayValidationFeedback(nameInput, nameError, nameSuccessIcon, nameErrorIcon, 'Name is required.', false);
            return false;
        } else if (!/^[a-zA-Z-' ]*$/.test(name)) {
            displayValidationFeedback(nameInput, nameError, nameSuccessIcon, nameErrorIcon, 'Only letters, spaces, hyphens, and apostrophes allowed.', false);
            return false;
        } else {
            displayValidationFeedback(nameInput, nameError, nameSuccessIcon, nameErrorIcon, '', true);
            return true;
        }
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        if (email === '') {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, 'Email is required.', false);
            return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, 'Invalid email format.', false);
            return false;
        } else {
            displayValidationFeedback(emailInput, emailError, emailSuccessIcon, emailErrorIcon, '', true);
            return true;
        }
    }

    function validateSubject() {
        const subject = subjectInput.value.trim();
        if (subject === '') {
            displayValidationFeedback(subjectInput, subjectError, subjectSuccessIcon, subjectErrorIcon, 'Subject is required.', false);
            return false;
        } else {
            displayValidationFeedback(subjectInput, subjectError, subjectSuccessIcon, subjectErrorIcon, '', true);
            return true;
        }
    }

    function validateMessage() {
        const message = messageInput.value.trim();
        if (message === '') {
            displayValidationFeedback(messageInput, messageError, null, null, 'Message is required.', false);
            return false;
        } else {
            displayValidationFeedback(messageInput, messageError, null, null, '', true);
            return true;
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

    nameInput.addEventListener('input', validateName);
    emailInput.addEventListener('input', validateEmail);
    subjectInput.addEventListener('input', validateSubject);
    messageInput.addEventListener('input', validateMessage);

    nameInput.addEventListener('blur', validateName);
    emailInput.addEventListener('blur', validateEmail);
    subjectInput.addEventListener('blur', validateSubject);
    messageInput.addEventListener('blur', validateMessage);

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isSubjectValid = validateSubject();
        const isMessageValid = validateMessage();

        if (isNameValid && isEmailValid && isSubjectValid && isMessageValid) {
            form.submit();
        } else {
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
