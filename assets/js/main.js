const toggleButton = document.querySelector('.toggle-button');

toggleButton.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
});

// Load theme from localStorage
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
}

gsap.registerPlugin(ScrollTrigger);

// Header Scroll Effect
const header = document.querySelector('.main-header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        header.classList.remove('scroll-up');
        return;
    }
    
    if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
        // Scroll Down
        header.classList.remove('scroll-up');
        header.classList.add('scroll-down');
    } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
        // Scroll Up
        header.classList.remove('scroll-down');
        header.classList.add('scroll-up');
    }
    lastScroll = currentScroll;
});

// Hero Section Animation
gsap.from('.hero-content', {
    duration: 1.5,
    y: 100,
    opacity: 0,
    ease: 'power4.out'
});

// Featured Products Animation
gsap.from('.product-slider', {
    scrollTrigger: {
        trigger: '.featured-products',
        start: 'top center',
        toggleActions: 'play none none reverse'
    },
    duration: 1,
    y: 50,
    opacity: 0,
    ease: 'power3.out'
});

// Categories Animation
gsap.from('.category-grid', {
    scrollTrigger: {
        trigger: '.categories',
        start: 'top center',
        toggleActions: 'play none none reverse'
    },
    duration: 1,
    y: 50,
    opacity: 0,
    ease: 'power3.out'
});

// Back to Top Button
const backToTop = document.getElementById('back-to-top');

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTop.style.display = 'flex';
    } else {
        backToTop.style.display = 'none';
    }
});

backToTop.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Loading Overlay
const loadingOverlay = document.getElementById('loading-overlay');

window.addEventListener('load', () => {
    loadingOverlay.style.display = 'none';
});

// Real-time Search
const searchInput = document.querySelector('.search-input');
const searchResults = document.createElement('div');
searchResults.className = 'search-results';
searchInput.parentNode.appendChild(searchResults);

let searchTimeout;

searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`search.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    searchResults.innerHTML = data.map(product => `
                        <a href="product.php?id=${product.id}" class="search-result-item">
                            <img src="${product.image}" alt="${product.name}">
                            <div class="search-result-info">
                                <h4>${product.name}</h4>
                                <p>$${product.price}</p>
                            </div>
                        </a>
                    `).join('');
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<p class="no-results">No products found</p>';
                    searchResults.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.style.display = 'none';
            });
    }, 300);
});

// Close search results when clicking outside
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Cart Functionality
function updateCartCount() {
    // The cart count is now handled by PHP in the header
    // This function is kept for any dynamic updates if needed
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        // Refresh the page to get the updated count from PHP
        window.location.reload();
    }
}

// Mobile Menu Toggle
const mobileMenuButton = document.querySelector('.mobile-menu-button');
const navLinks = document.querySelector('.nav-links');

if (mobileMenuButton && navLinks) {
    mobileMenuButton.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!navLinks.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            navLinks.classList.remove('active');
        }
    });
}

// Add to Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            // Disable button while processing
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            
            // Create form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Add animation to cart icon
                    const cartIcon = document.querySelector('.cart-link');
                    cartIcon.classList.add('bounce');
                    setTimeout(() => cartIcon.classList.remove('bounce'), 1000);
                } else {
                    if (data.redirect) {
                        // Redirect to login page
                        window.location.href = data.redirect;
                    } else {
                        // Show error message
                        showNotification(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = 'Add to Cart';
            });
        });
    });
});

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Add animation class
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
