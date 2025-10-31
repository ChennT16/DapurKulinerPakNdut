// =======================
// MOBILE NAVIGATION
// =======================
const burger = document.getElementById('burger');
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

// =======================
// HEADER SCROLL EFFECT
// =======================
window.addEventListener('scroll', function () {
    const header = document.getElementById('header');
    if (!header) return;
    if (window.scrollY > 100) {
    header.classList.add('scrolled');
    } else {
    header.classList.remove('scrolled');
    }
});

// =======================
// MENU SLIDER
// =======================
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

// =======================
// CONTACT FORM
// =======================
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var namaEl = document.getElementById('nama');
    var emailEl = document.getElementById('email');
    var pesanEl = document.getElementById('pesan');

    var nama = namaEl ? namaEl.value.trim() : 'Tamu';
    var email = emailEl ? emailEl.value.trim() : 'tidak tercantum';
    var pesan = pesanEl ? pesanEl.value.trim() : '';

    alert('Terima kasih ' + nama + '!\n\nPesan Anda telah diterima. Kami akan menghubungi Anda melalui ' + email + ' segera.');

    contactForm.reset();
    });
}

// =======================
// SMOOTH SCROLL
// =======================
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

// =======================
// INTERSECTION OBSERVER (ANIMASI)
// =======================
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

// ============================================
// TAMBAHKAN CODE INI DI BAWAH FILE script.js
// ============================================

// =======================
// SISTEM PEMESANAN MENU
// =======================

// Data menu Dapur Pak Ndut
var menuItemsData = [
    // Pentol & Gorengan
    { id: 1, name: 'Pentol', price: 5000, category: 'Pentol', image: 'PENTOL.jpg' },
    { id: 2, name: 'Pentol Tahu', price: 5000, category: 'Pentol', image: 'PENTOL TAHU.jpg' },
    { id: 3, name: 'Pentol Bakar', price: 1000, category: 'Pentol', image: 'PENTOL BAKAR.jpg' },
    { id: 4, name: 'Tahu Bakar', price: 1000, category: 'Pentol', image: 'TAHU BAKAR.jpg' },
    { id: 11, name: 'Gorengan Pangsit', price: 1000, category: 'Pentol', image: 'GORENGAN PANGSIT.jpg' },
    
    // Nasi Bento
    { id: 5, name: 'Nasi Bento Ayam Katsu', price: 10000, category: 'Nasi Bento', image: 'NASI BENTO KATSU.jpg' },
    { id: 6, name: 'Nasi Bento Ayam Crispy', price: 10000, category: 'Nasi Bento', image: 'NASI BENTO AYAM CRISPY.jpg' },
    { id: 7, name: 'Nasi Bento Ayam Geprek', price: 10000, category: 'Nasi Bento', image: 'NASI BENTO GEPREK.jpg' },
    { id: 8, name: 'Nasi Beff & Sosis', price: 10000, category: 'Nasi Bento', image: 'NASI BENTO DAGING DAN SOSIS.jpg' },
    { id: 9, name: 'Nasi Bento Rica-Rica Balungan', price: 8000, category: 'Nasi Bento', image: 'NASI BENTO RICA RICA BALUNGAN.jpg' },
    { id: 10, name: 'Nasi Bento Ati Ampela', price: 8000, category: 'Nasi Bento', image: 'NASI BENTO ATI AMPELA.jpg' },
    
    // Minuman
    { id: 12, name: 'Es Kuwut', price: 3000, category: 'Minuman', image: 'ES KUWUT.jpg' },
    { id: 13, name: 'Es Rasa-Rasa', price: 3000, category: 'Minuman', image: 'ES RASA RASA.jpg' },
    { id: 14, name: 'Es Teh Lemon', price: 3000, category: 'Minuman', image: 'LEMON TEA.jpg' },
    { id: 15, name: 'Air Mineral', price: 3000, category: 'Minuman', image: 'AIR MINERAL.jpg' },
    { id: 16, name: 'Susu Kedelai', price: 6000, category: 'Minuman', image: 'SUSU KEDELAI.jpg' }
];

var cartItems = [];
var currentModalCategory = 'Semua';

// Fungsi format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Fungsi buka modal pemesanan
function orderAllMenu() {
    var modal = document.getElementById('orderModal');
    if (modal) {
        modal.style.display = 'flex';
        displayMenuInModal();
        updateCartDisplay();
    }
}

// Fungsi tutup modal
function closeOrderModal() {
    var modal = document.getElementById('orderModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Tutup modal ketika klik di luar
window.addEventListener('click', function(event) {
    var modal = document.getElementById('orderModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Fungsi tampilkan menu di modal
function displayMenuInModal() {
    var menuGrid = document.getElementById('modalMenuGrid');
    if (!menuGrid) return;
    
    menuGrid.innerHTML = '';

    var filteredMenu = currentModalCategory === 'Semua' 
        ? menuItemsData 
        : menuItemsData.filter(function(item) {
            return item.category === currentModalCategory;
        });

    filteredMenu.forEach(function(item) {
        var menuCard = document.createElement('div');
        menuCard.className = 'modal-menu-item';
        menuCard.innerHTML = 
            '<div class="modal-menu-image" style="background-image: url(\'' + item.image + '\'); background-size: cover; background-position: center;"></div>' +
            '<div class="modal-menu-content">' +
                '<h4>' + item.name + '</h4>' +
                '<div class="modal-menu-category">' + item.category + '</div>' +
                '<div class="modal-menu-price">' + formatRupiah(item.price) + '</div>' +
                '<button class="modal-add-btn" onclick="addToCartById(' + item.id + ')">+ Tambah</button>' +
            '</div>';
        menuGrid.appendChild(menuCard);
    });
}

// Filter kategori modal
function filterModalKategori(kategori) {
    currentModalCategory = kategori;
    
    var buttons = document.querySelectorAll('.modal-kategori-btn');
    buttons.forEach(function(btn) {
        btn.classList.remove('active');
    });
    
    // Cari tombol yang diklik dan tambahkan class active
    var clickedBtn = event.target;
    if (clickedBtn) {
        clickedBtn.classList.add('active');
    }

    displayMenuInModal();
}

// Tambah ke keranjang berdasarkan ID
function addToCartById(itemId) {
    var item = null;
    for (var i = 0; i < menuItemsData.length; i++) {
        if (menuItemsData[i].id === itemId) {
            item = menuItemsData[i];
            break;
        }
    }
    
    if (!item) return;

    var cartItem = null;
    for (var j = 0; j < cartItems.length; j++) {
        if (cartItems[j].id === itemId) {
            cartItem = cartItems[j];
            break;
        }
    }

    if (cartItem) {
        cartItem.quantity++;
    } else {
        cartItems.push({
            id: item.id,
            name: item.name,
            price: item.price,
            category: item.category,
            image: item.image,
            quantity: 1
        });
    }

    updateCartDisplay();
}

// Update jumlah item di keranjang
function updateCartQuantity(itemId, change) {
    var cartItem = null;
    for (var i = 0; i < cartItems.length; i++) {
        if (cartItems[i].id === itemId) {
            cartItem = cartItems[i];
            break;
        }
    }
    
    if (cartItem) {
        cartItem.quantity += change;
        if (cartItem.quantity <= 0) {
            removeFromCartById(itemId);
        } else {
            updateCartDisplay();
        }
    }
}

// Hapus item dari keranjang
function removeFromCartById(itemId) {
    cartItems = cartItems.filter(function(item) {
        return item.id !== itemId;
    });
    updateCartDisplay();
}

// Update tampilan keranjang
function updateCartDisplay() {
    var cartContent = document.getElementById('modalCartContent');
    if (!cartContent) return;

    if (cartItems.length === 0) {
        cartContent.innerHTML = '<div class="modal-cart-empty">Keranjang masih kosong</div>';
        return;
    }

    var cartHTML = '<div class="modal-cart-items">';
    
    cartItems.forEach(function(item) {
        cartHTML += 
            '<div class="modal-cart-item">' +
                '<div class="modal-cart-item-header">' +
                    '<h5>' + item.name + '</h5>' +
                    '<div class="modal-cart-item-price">' + formatRupiah(item.price) + ' x ' + item.quantity + '</div>' +
                '</div>' +
                '<div class="modal-cart-item-controls">' +
                    '<div class="modal-quantity-controls">' +
                        '<button class="modal-qty-btn" onclick="updateCartQuantity(' + item.id + ', -1)">-</button>' +
                        '<span class="modal-quantity">' + item.quantity + '</span>' +
                        '<button class="modal-qty-btn" onclick="updateCartQuantity(' + item.id + ', 1)">+</button>' +
                    '</div>' +
                    '<button class="modal-remove-btn" onclick="removeFromCartById(' + item.id + ')">Hapus</button>' +
                '</div>' +
            '</div>';
    });

    cartHTML += '</div>';

    // Hitung total
    var total = 0;
    var jumlahItem = 0;
    cartItems.forEach(function(item) {
        total += item.price * item.quantity;
        jumlahItem += item.quantity;
    });

    cartHTML += 
        '<div class="modal-cart-total">' +
            '<div class="modal-total-row">' +
                '<span>Jumlah Item:</span>' +
                '<span>' + jumlahItem + ' item</span>' +
            '</div>' +
            '<div class="modal-total-row">' +
                '<span>Subtotal:</span>' +
                '<span>' + formatRupiah(total) + '</span>' +
            '</div>' +
            '<div class="modal-total-row final">' +
                '<span>Total Bayar:</span>' +
                '<span>' + formatRupiah(total) + '</span>' +
            '</div>' +
        '</div>' +
        '<button class="modal-checkout-btn" onclick="checkoutOrder()">Konfirmasi Pesanan</button>';

    cartContent.innerHTML = cartHTML;
}

// Checkout pesanan
function checkoutOrder() {
    if (cartItems.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    var total = 0;
    cartItems.forEach(function(item) {
        total += item.price * item.quantity;
    });

    alert('✅ Pesanan Berhasil!\n\nTotal: ' + formatRupiah(total) + '\n\nTerima kasih telah memesan di Dapur Pak Ndut!');
    
    // UNCOMMENT BARIS DI BAWAH untuk simpan ke database PHP:
    // simpanPesananToDatabase();

    cartItems = [];
    updateCartDisplay();
    closeOrderModal();
}

// Fungsi simpan pesanan ke database (opsional)
function simpanPesananToDatabase() {
    var total = 0;
    cartItems.forEach(function(item) {
        total += item.price * item.quantity;
    });

    var formData = new FormData();
    formData.append('pesanan', JSON.stringify(cartItems));
    formData.append('total', total);

    fetch('simpan_pesanan.php', {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        console.log('Pesanan berhasil disimpan:', data);
    })
    .catch(function(error) {
        console.error('Error:', error);
    });
}

// Expose functions ke window
window.orderAllMenu = orderAllMenu;
window.closeOrderModal = closeOrderModal;
window.filterModalKategori = filterModalKategori;
window.addToCartById = addToCartById;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCartById = removeFromCartById;
window.checkoutOrder = checkoutOrder;