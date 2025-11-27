// MOBILE NAVIGATION//
const burger = document.getElementById('PakNdut');
const navLinks = document.getElementById('navLinks');

if (burger && navLinks) {
    burger.addEventListener('click', function () {
    navLinks.classList.toggle('active');
    });

  // Tutup menu ketika salah satu link diklik
    const links = navLinks.querySelectorAll('a');
    if (links && links.length) {
    links.forEach(function (link) {
        link.addEventListener('click', function () {
        navLinks.classList.remove('active');
        });
    });
    }
}

//HEADER SCROLL EFFECT// 
window.addEventListener('scroll', function () {
    const header = document.getElementById('header');
    if (!header) return;
    if (window.scrollY > 100) {
    header.classList.add('scrolled');
    } else {
    header.classList.remove('scrolled');
    }
});

//MENU SLIDER//
let currentSlide = 0;
const slider = document.getElementById('menuSlider');
const menuItems = document.querySelectorAll('.menu-item');
const itemWidth = 330; // 300px + 30px gap

if (slider && menuItems.length > 0) {
    function updateSliderPosition() {
    slider.style.transform = 'translateX(-' + (currentSlide * itemWidth) + 'px)';
    }

    function slideMenu(direction) {
    var parentWidth = slider.parentElement ? slider.parentElement.offsetWidth : 0;
    if (parentWidth === 0) return;

    var itemsInView = Math.floor(parentWidth / itemWidth);
    var maxSlide = Math.max(0, menuItems.length - itemsInView);

    currentSlide = currentSlide + direction;
    if (currentSlide < 0) currentSlide = 0;
    if (currentSlide > maxSlide) currentSlide = maxSlide;

    updateSliderPosition();
    }

  // Expose function ke window supaya bisa dipanggil dari HTML onclick
    window.slideMenu = slideMenu;

  // Auto slide tiap 5 detik
    setInterval(function () {
    var parentWidth = slider.parentElement ? slider.parentElement.offsetWidth : 0;
    if (parentWidth === 0) return;

    var itemsInView = Math.floor(parentWidth / itemWidth);
    var maxSlide = Math.max(0, menuItems.length - itemsInView);

    if (currentSlide >= maxSlide) {
        currentSlide = 0;
    } else {
        currentSlide = currentSlide + 1;
    }

    updateSliderPosition();
    }, 5000);
}

//AUTO HIDE NOTIFICATION//
window.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada parameter status di URL
    if (window.location.search.includes('status=')) {
        // Setelah 3 detik, hapus parameter dari URL
        setTimeout(function() {
            // Ambil URL tanpa query string tapi tetap dengan hash
            var cleanUrl = window.location.pathname + window.location.hash;
            window.history.replaceState({}, document.title, cleanUrl);
        }, 3000);
    }
});

//SMOOTH SCROLL//
document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
    var href = this.getAttribute('href');
    if (!href || href === '#') return;

    var target = document.querySelector(href);
    if (target) {
        e.preventDefault();
        target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
        });
    }
    });
});

//INTERSECTION OBSERVER (ANIMASI)//
var observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
    if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
    }
    });
}, observerOptions);

document.querySelectorAll('.menu-item, .info-box, .feature-box').forEach(function (el) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
});