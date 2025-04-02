document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signup-form');
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    emailInput.addEventListener('input', function() {
        if (this.value.includes('@') && this.value.includes('.')) {
            this.classList.add('valid');
        } else {
            this.classList.remove('valid');
        }
    });

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('signup_validate.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Signup successful!');
            } else {
                alert('Signup failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });

    const loginButton = document.querySelector('.login-button');
    loginButton.addEventListener('click', function() {
        window.location.href = 'login.html'; //replace with your login page location
    });
});