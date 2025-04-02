document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // Email Validation (basic)
    emailInput.addEventListener('input', function() {
        if (this.value.includes('@') && this.value.includes('.')) {
            this.classList.add('valid');
        } else {
            this.classList.remove('valid');
        }
    });

    // AJAX Form Validation (example)
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        fetch('validate.php', { // Replace with your server-side validation script
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Login successful!');
                // Redirect or perform other actions
            } else {
                alert('Login failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});