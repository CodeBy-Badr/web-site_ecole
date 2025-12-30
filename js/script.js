// ===========================
// AOS INITIALIZATION
// ===========================
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: false,
        mirror: true,
        offset: 100
    });
});

// ===========================
// NAVBAR FUNCTIONALITY
// ===========================
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const navLinks = document.querySelectorAll('.nav-link');

// Toggle menu
if (hamburger) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
}

// Close menu when link is clicked
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Update active nav link based on page
function updateActiveNav() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

updateActiveNav();

// Close navbar when clicking outside
document.addEventListener('click', (event) => {
    const isClickInsideNav = event.target.closest('.navbar-container');
    if (!isClickInsideNav && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});

// ===========================
// CONTACT FORM HANDLING
// ===========================
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            subject: document.getElementById('subject').value,
            section: document.getElementById('child-section').value,
            message: document.getElementById('message').value,
            consent: document.getElementById('consent').checked
        };
        
        // Validate form
        if (!formData.consent) {
            showFormMessage('Veuillez accepter la politique de confidentialité', 'error');
            return;
        }
        
        // Simulate form submission (In real scenario, you would send to a server)
        showFormMessage('✓ Message envoyé avec succès! Nous vous contacterons très bientôt.', 'success');
        
        // Reset form
        contactForm.reset();
        
        // Log form data (for demonstration)
        console.log('Form Data:', formData);
        
        // Clear message after 5 seconds
        setTimeout(() => {
            formMessage.style.display = 'none';
        }, 5000);
    });
}

function showFormMessage(message, type) {
    formMessage.textContent = message;
    formMessage.className = `form-message ${type}`;
    formMessage.style.display = 'block';
}

// ===========================
// FAQ ACCORDION
// ===========================
const faqHeaders = document.querySelectorAll('.faq-header');

faqHeaders.forEach(header => {
    header.addEventListener('click', function() {
        const faqItem = this.parentElement;
        const faqContent = faqItem.querySelector('.faq-content');
        
        // Close other items
        document.querySelectorAll('.faq-item').forEach(item => {
            if (item !== faqItem) {
                item.classList.remove('active');
                item.querySelector('.faq-content').classList.remove('active');
            }
        });
        
        // Toggle current item
        faqItem.classList.toggle('active');
        faqContent.classList.toggle('active');
    });
});

// ===========================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ===========================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        
        // Only handle if it's a valid anchor
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            
            const target = document.querySelector(href);
            const offsetTop = target.offsetTop - 80;
            
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// ===========================
// NAVBAR SCROLL EFFECT
// ===========================
const navbar = document.querySelector('.navbar');
let lastScrollTop = 0;

window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
    } else {
        navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
    }
    
    lastScrollTop = currentScroll;
});

// ===========================
// COUNTER ANIMATION FOR STATS
// ===========================
function animateCounters() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach(element => {
        const target = parseInt(element.innerText);
        const isPercentage = element.innerText.includes('%');
        const isPlus = element.innerText.includes('+');
        
        let current = 0;
        const increment = Math.ceil(target / 50);
        
        const counter = setInterval(() => {
            current += increment;
            
            if (current >= target) {
                current = target;
                clearInterval(counter);
            }
            
            let displayValue = current;
            if (isPercentage) {
                element.innerText = displayValue + '%';
            } else if (isPlus) {
                element.innerText = displayValue + '+';
            } else {
                element.innerText = displayValue;
            }
        }, 30);
    });
}

// Trigger counter animation when stats section is in view
if (document.getElementById('stats')) {
    const statsSection = document.getElementById('stats');
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                animateCounters();
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);
    
    observer.observe(statsSection);
}

// ===========================
// SCROLL TO TOP BUTTON
// ===========================
function createScrollToTopButton() {
    const scrollToTop = document.createElement('button');
    scrollToTop.id = 'scrollToTop';
    scrollToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollToTop.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        z-index: 999;
    `;
    
    document.body.appendChild(scrollToTop);
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTop.style.display = 'flex';
        } else {
            scrollToTop.style.display = 'none';
        }
    });
    
    scrollToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    scrollToTop.addEventListener('mouseenter', () => {
        scrollToTop.style.transform = 'scale(1.1)';
    });
    
    scrollToTop.addEventListener('mouseleave', () => {
        scrollToTop.style.transform = 'scale(1)';
    });
}

createScrollToTopButton();

// ===========================
// LAZY LOADING IMAGES (Optional)
// ===========================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// ===========================
// FORM VALIDATION
// ===========================
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\d\s\-\+\(\)]{8,}$/;
    return re.test(phone);
}

const emailInput = document.getElementById('email');
if (emailInput) {
    emailInput.addEventListener('blur', function() {
        if (this.value && !validateEmail(this.value)) {
            this.style.borderColor = '#FF6B6B';
        } else {
            this.style.borderColor = '#eee';
        }
    });
}

const phoneInput = document.getElementById('phone');
if (phoneInput) {
    phoneInput.addEventListener('blur', function() {
        if (this.value && !validatePhone(this.value)) {
            this.style.borderColor = '#FF6B6B';
        } else {
            this.style.borderColor = '#eee';
        }
    });
}

// ===========================
// PAGE LOAD ANIMATION
// ===========================
window.addEventListener('load', () => {
    document.body.style.opacity = '1';
    
    // Trigger AOS refresh to ensure animations run
    if (window.AOS) {
        AOS.refresh();
    }
});

// ===========================
// UTILITY FUNCTIONS
// ===========================

// Prevent form submission on Enter in text inputs
const formInputs = document.querySelectorAll('.contact-form input[type="text"], .contact-form input[type="email"], .contact-form input[type="tel"]');
formInputs.forEach(input => {
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
});

// Mobile menu visibility toggle on window resize
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});

// ===========================
// ACCESSIBILITY IMPROVEMENTS
// ===========================

// Add keyboard navigation support
document.addEventListener('keydown', (e) => {
    // Escape key closes mobile menu
    if (e.key === 'Escape' && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});

// Focus management for mobile menu
hamburger.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        hamburger.click();
    }
});

// Announce to screen readers when form is submitted
if (contactForm) {
    const originalSubmit = contactForm.onsubmit;
    contactForm.addEventListener('submit', function() {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', 'status');
        announcement.setAttribute('aria-live', 'polite');
        announcement.className = 'sr-only';
        announcement.textContent = 'Votre message a été envoyé avec succès';
        document.body.appendChild(announcement);
        
        setTimeout(() => announcement.remove(), 3000);
    });
}

// ===========================
// CONSOLE WELCOME MESSAGE
// ===========================
console.log('%cBienvenue sur le site de Groupe Scolaire Noumidia! 🎓', 'font-size: 20px; color: #FF6B6B; font-weight: bold;');
console.log('%cSite créé avec HTML5, CSS3 et JavaScript moderne', 'font-size: 14px; color: #4ECDC4;');

// Log any errors to help with debugging
window.addEventListener('error', (e) => {
    console.error('Erreur détectée:', e.error);
});
