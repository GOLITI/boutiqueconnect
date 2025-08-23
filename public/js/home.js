// Create animated particles
        function createParticles() {
            const particles = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 4 + 2;
                const left = Math.random() * 100;
                const delay = Math.random() * 2;
                const duration = Math.random() * 3 + 3;
                
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = left + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = delay + 's';
                particle.style.animationDuration = duration + 's';
                
                particles.appendChild(particle);
            }
        }

        // Scroll reveal animation
        function revealOnScroll() {
            const reveals = document.querySelectorAll('.scroll-reveal');
            
            reveals.forEach(reveal => {
                const windowHeight = window.innerHeight;
                const elementTop = reveal.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < windowHeight - elementVisible) {
                    reveal.classList.add('revealed');
                }
            });
        }

        // Counter animation for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = counter.textContent;
                const isPercentage = target.includes('%');
                const numericTarget = parseInt(target.replace(/[^\d]/g, ''));
                
                let current = 0;
                const increment = numericTarget / 50;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericTarget) {
                        current = numericTarget;
                        clearInterval(timer);
                    }
                    
                    if (target.includes('+')) {
                        counter.textContent = Math.floor(current) + '+';
                    } else if (target.includes('%')) {
                        counter.textContent = Math.floor(current) + '%';
                    } else if (target.includes('/')) {
                        counter.textContent = '24/7';
                    } else {
                        if (numericTarget >= 1000000) {
                            counter.textContent = (Math.floor(current / 100000) / 10) + 'M+';
                        } else {
                            counter.textContent = Math.floor(current);
                        }
                    }
                }, 30);
            });
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Add fade-in class to elements
            const elements = document.querySelectorAll('.home-card, .about-us-card, .contact-card');
            elements.forEach((el, index) => {
                el.style.animationDelay = (index * 0.1) + 's';
                el.classList.add('fade-in');
            });
            
            // Start counter animation when stats section is visible
            const statsSection = document.querySelector('.stats-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            });
            observer.observe(statsSection);
        });

        // Scroll event listener
        window.addEventListener('scroll', revealOnScroll);

        // Smooth scrolling for CTA button
        document.querySelector('.hero-cta').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.home-content').scrollIntoView({
                behavior: 'smooth'
            });
        });
