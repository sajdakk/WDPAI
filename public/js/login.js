function togglePassword() {
    const passwordInput = document.querySelector('#password');
    const visibilityIcon = document.querySelector('#visibilityIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        visibilityIcon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        visibilityIcon.textContent = 'visibility';
    }
}