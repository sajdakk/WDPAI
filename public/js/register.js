function togglePassword() {
    const passwordInput = document.querySelector('#password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}