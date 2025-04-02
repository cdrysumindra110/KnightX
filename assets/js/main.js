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
let cart = JSON.parse(localStorage.getItem('cart')) || [];
const cartCount = document.querySelector('.cart-count');

function updateCartCount() {
    cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
}

function addToCart(productId, quantity = 1) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ id: productId, quantity });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    // Show success message
    showNotification('Product added to cart!');
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    // Show success message
    showNotification('Product removed from cart!');
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    gsap.from(notification, {
        duration: 0.3,
        y: -100,
        opacity: 0,
        ease: 'power3.out'
    });
    
    // Animate out and remove
    setTimeout(() => {
        gsap.to(notification, {
            duration: 0.3,
            y: -100,
            opacity: 0,
            ease: 'power3.in',
            onComplete: () => notification.remove()
        });
    }, 3000);
        });
    }, 3000);
}

// Initialize cart count on page load
updateCartCount();

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
*/
