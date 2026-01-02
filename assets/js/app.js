// Main JavaScript for StayEasy Platform

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Password visibility toggle for login page
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Password visibility toggle for signup page
    const toggleSignupPassword = document.getElementById('toggleSignupPassword');
    const signupPasswordInput = document.getElementById('signupPassword');
    
    if (toggleSignupPassword && signupPasswordInput) {
        toggleSignupPassword.addEventListener('click', function() {
            const type = signupPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            signupPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Account type selection for signup page
    const accountTypeOptions = document.querySelectorAll('.account-type-option');
    const accountTypeInput = document.getElementById('accountType');
    
    if (accountTypeOptions.length > 0 && accountTypeInput) {
        // Set first option as active by default
        accountTypeOptions[0].classList.add('active');
        document.getElementById('traveler-benefits').classList.remove('hidden');
        
        accountTypeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                accountTypeOptions.forEach(opt => {
                    opt.classList.remove('active');
                    const benefitsId = opt.getAttribute('data-account-type') + '-benefits';
                    const benefitsEl = document.getElementById(benefitsId);
                    if (benefitsEl) benefitsEl.classList.add('hidden');
                });
                
                // Add active class to clicked option
                this.classList.add('active');
                
                // Update hidden input value
                const selectedType = this.getAttribute('data-account-type');
                accountTypeInput.value = selectedType;
                
                // Show benefits for selected option
                const benefitsId = selectedType + '-benefits';
                const benefitsEl = document.getElementById(benefitsId);
                if (benefitsEl) benefitsEl.classList.remove('hidden');
            });
        });
    }
    
    // Form validation and submission for login page
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Simple validation
            if (!email || !password) {
                showAlert('Please fill in all required fields.', 'error');
                return;
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Please enter a valid email address.', 'error');
                return;
            }
            
            // Demo login logic
            const demoCredentials = {
                'traveler@demo.com': {password: 'password123', role: 'traveler', name: 'John Traveler'},
                'host@demo.com': {password: 'password123', role: 'host', name: 'Sarah Host'},
                'admin@demo.com': {password: 'password123', role: 'admin', name: 'Admin User'}
            };
            
            if (demoCredentials[email] && demoCredentials[email].password === password) {
                // Successful login
                const user = demoCredentials[email];
                showAlert(`Welcome back, ${user.name}! (Role: ${user.role})`, 'success');
                
                // In a real app, you would redirect to dashboard
                console.log(`User logged in: ${email}, Role: ${user.role}`);
                
                // Simulate redirect after 2 seconds
                setTimeout(() => {
                    // In a real application, this would be a server-side redirect
                    // For demo purposes, we'll just show a message
                    if (user.role === 'traveler') {
                        showAlert('Redirecting to traveler dashboard...', 'info');
                    } else if (user.role === 'host') {
                        showAlert('Redirecting to host dashboard...', 'info');
                    } else if (user.role === 'admin') {
                        showAlert('Redirecting to admin dashboard...', 'info');
                    }
                }, 1500);
            } else {
                // Failed login
                showAlert('Invalid email or password. Please try again.', 'error');
            }
        });
    }
    
    // Form validation and submission for signup page
    const signupForm = document.getElementById('signupForm');
    
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('signupEmail').value;
            const password = document.getElementById('signupPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const accountType = document.getElementById('accountType').value;
            
            // Simple validation
            if (!firstName || !lastName || !email || !password || !confirmPassword) {
                showAlert('Please fill in all required fields.', 'error');
                return;
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Please enter a valid email address.', 'error');
                return;
            }
            
            // Password validation
            if (password.length < 6) {
                showAlert('Password must be at least 6 characters long.', 'error');
                return;
            }
            
            // Password confirmation
            if (password !== confirmPassword) {
                showAlert('Passwords do not match.', 'error');
                return;
            }
            
            // Terms agreement check
            const termsAgreed = document.getElementById('terms').checked;
            if (!termsAgreed) {
                showAlert('You must agree to the Terms of Service and Privacy Policy.', 'error');
                return;
            }
            
            // Successful signup
            showAlert(`Account created successfully! Welcome to StayEasy, ${firstName}! (Account type: ${accountType})`, 'success');
            
            // In a real app, you would submit to server and redirect
            console.log(`New user signup: ${firstName} ${lastName}, Email: ${email}, Account type: ${accountType}`);
            
            // Simulate redirect after 3 seconds
            setTimeout(() => {
                // In a real application, this would redirect to the login page or dashboard
                showAlert('Redirecting to login page...', 'info');
                // window.location.href = 'login.html';
            }, 2000);
        });
    }
    
    // Favorite button functionality for rental cards
    const favoriteButtons = document.querySelectorAll('.fa-heart');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (this.classList.contains('far')) {
                this.classList.remove('far');
                this.classList.add('fas');
                this.style.color = '#ef4444'; // red-500
                showAlert('Added to favorites!', 'success');
            } else {
                this.classList.remove('fas');
                this.classList.add('far');
                this.style.color = '';
                showAlert('Removed from favorites.', 'info');
            }
        });
    });
    
    // Search form functionality on homepage
    const searchForm = document.querySelector('.bg-white.rounded-lg.shadow-xl');
    
    if (searchForm) {
        const searchButton = searchForm.querySelector('button');
        
        if (searchButton) {
            searchButton.addEventListener('click', function() {
                const destination = searchForm.querySelector('input[type="text"]').value;
                const dates = searchForm.querySelector('input[placeholder="Select dates"]').value;
                const guests = searchForm.querySelector('select').value;
                
                if (!destination) {
                    showAlert('Please enter a destination to search.', 'info');
                    return;
                }
                
                showAlert(`Searching for rentals in ${destination} for ${guests}...`, 'info');
                
                // In a real app, this would submit a search request
                console.log(`Search: ${destination}, Dates: ${dates}, Guests: ${guests}`);
            });
        }
    }
    
    // Alert function for user feedback
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `custom-alert fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium max-w-md fade-in`;
        
        // Set color based on type
        if (type === 'success') {
            alert.style.backgroundColor = '#10b981'; // green-500
        } else if (type === 'error') {
            alert.style.backgroundColor = '#ef4444'; // red-500
        } else if (type === 'info') {
            alert.style.backgroundColor = '#3b82f6'; // blue-500
        }
        
        // Add message and close button
        alert.innerHTML = `
            <div class="flex justify-between items-center">
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(alert);
        
        // Add close functionality
        const closeButton = alert.querySelector('button');
        closeButton.addEventListener('click', function() {
            alert.remove();
        });
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Demo date picker functionality (would be replaced with a real date picker library)
    const dateInputs = document.querySelectorAll('input[placeholder="Select dates"]');
    
    dateInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.type = 'date';
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.type = 'text';
            }
        });
    });
    
    // Form input animations
    const formInputs = document.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-300');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-300');
        });
    });
});