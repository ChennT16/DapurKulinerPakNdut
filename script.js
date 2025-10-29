// Mobile Navigation
const burger = document.getElementById('burger');
const navLinks = document.getElementById('navLinks');

// Pastikan elemen-elemen ada sebelum menambahkan event listener
if (burger && navLinks) {
    burger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

// Close menu when clicking on a link
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        if (navLinks) {
            navLinks.classList.remove('active');
        }
    });
});

// Header scroll effect
window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    if (header) { // Pastikan elemen header ada
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
});

// ---

// Menu Slider
let currentSlide = 0;
const slider = document.getElementById('menuSlider');
const menuItems = document.querySelectorAll('.menu-item');
const itemWidth = 330; // 300px + 30px gap

// Pastikan slider dan menuItems ada sebelum menjalankan logika slider
if (slider && menuItems.length > 0) {
    
    function updateSliderPosition() {
        // Syntax error: Menggunakan backticks (`) untuk template literal dan properti transform
        slider.style.transform = `translateX(-${currentSlide * itemWidth}px)`;
    }
    
    function slideMenu(direction) {
        // Cek lebar parent container untuk menentukan maxSlide yang valid
        const parentWidth = slider.parentElement ? slider.parentElement.offsetWidth : 0;
        if (parentWidth === 0) return; // Keluar jika parentWidth tidak valid
        
        // Hitung berapa banyak item yang muat di layar
        const itemsInView = Math.floor(parentWidth / itemWidth);
        
        // maxSlide adalah jumlah item yang bisa digeser, ini harus lebih besar dari 0 agar bisa bergeser
        const maxSlide = Math.max(0, menuItems.length - itemsInView); 
        
        currentSlide += direction;
        
        if (currentSlide < 0) currentSlide = 0;
        if (currentSlide > maxSlide) currentSlide = maxSlide;
        
        updateSliderPosition();
    }
    
    // Auto slide
    setInterval(() => {
        const parentWidth = slider.parentElement ? slider.parentElement.offsetWidth : 0;
        if (parentWidth === 0) return; 
        
        const itemsInView = Math.floor(parentWidth / itemWidth);
        const maxSlide = Math.max(0, menuItems.length - itemsInView); 
        
        if (currentSlide >= maxSlide) {
            currentSlide = 0;
        } else {
            currentSlide++;
        }
        
        updateSliderPosition();
    }, 5000);
    
    // Panggil slideMenu untuk mengaktifkan tombol panah (jika ada)
    // Anda perlu memastikan tombol-tombol memiliki event listener yang memanggil `slideMenu(1)` atau `slideMenu(-1)`
    // Contoh: window.slideMenu = slideMenu; // Membuatnya global jika tombol ada di luar script
}


// ---

// Order function
function orderItem(itemName) {
    // Syntax error: Menggunakan backticks (`) untuk template literal
    alert(`Terima kasih! Anda memesan: ${itemName}\n\nSilakan hubungi kami melalui WhatsApp di +62 812-3456-7890 untuk melanjutkan pesanan.`);
}

// ---

// Form submission
const contactForm = document.getElementById('contactForm');
if (contactForm) { // Pastikan form ada
    contactForm.addEventListener('submit', function submitForm(e) {
        e.preventDefault();
        const nama = document.getElementById('nama') ? document.getElementById('nama').value : 'Tamu';
        const email = document.getElementById('email') ? document.getElementById('email').value : 'tidak tercantum';
        const pesan = document.getElementById('pesan') ? document.getElementById('pesan').value : '';
        
        // Syntax error: Menggunakan backticks (`) untuk template literal
        alert(`Terima kasih ${nama}!\n\nPesan Anda telah diterima. Kami akan menghubungi Anda melalui ${email} segera.`);
        
        contactForm.reset();
    });
}


// ---

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ---

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            // Opsional: Hentikan observasi setelah animasi agar tidak terus berjalan
            // observer.unobserve(entry.target); 
        }
    });
}, observerOptions);

document.querySelectorAll('.menu-item, .info-box, .feature-box').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
});