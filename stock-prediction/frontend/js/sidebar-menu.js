// sidebar-menu.js - Menangani fungsi navigasi sidebar

/**
 * Mengatur menu sidebar termasuk penandaan menu aktif dan menangani aksi khusus
 */
document.addEventListener('DOMContentLoaded', function() {
    // Tandai menu aktif berdasarkan URL saat ini
    markActiveMenu();
    
    // Event listener untuk menu Stock List - scroll ke tabel saham
    const stockListLink = document.getElementById('stockListLink');
    if (stockListLink) {
        stockListLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Jika berada di halaman dashboard, scroll ke tabel saham
            if (window.location.pathname.includes('dashboard')) {
                const stocksTableSection = document.querySelector('.stock-table-container');
                if (stocksTableSection) {
                    stocksTableSection.scrollIntoView({ behavior: 'smooth' });
                }
            } else {
                // Jika bukan di dashboard, arahkan ke dashboard dan tambahkan parameter untuk scroll ke tabel
                window.location.href = 'dashboard.html#stocks-table';
            }
        });
    }
});

/**
 * Menandai menu yang aktif berdasarkan URL halaman saat ini
 */
function markActiveMenu() {
    const currentPage = window.location.pathname.split('/').pop();
    const sidebarLinks = document.querySelectorAll('#sidebarMenu .nav-link');
    
    // Hapus kelas active dari semua link
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Tambahkan kelas active ke link yang sesuai dengan halaman saat ini
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPage.includes(href)) {
            link.classList.add('active');
        } else if (currentPage === '' && href === 'dashboard.html') {
            // Default ke dashboard jika di halaman root
            link.classList.add('active');
        }
    });
    
    // Tangani kasus khusus untuk URL dengan hash/fragment
    if (window.location.hash === '#stocks-table') {
        const stockListLink = document.getElementById('stockListLink');
        if (stockListLink) {
            // Aktifkan link Stock List
            sidebarLinks.forEach(link => link.classList.remove('active'));
            stockListLink.classList.add('active');
            
            // Scroll ke tabel saham setelah halaman dimuat
            setTimeout(() => {
                const stocksTableSection = document.querySelector('.stock-table-container');
                if (stocksTableSection) {
                    stocksTableSection.scrollIntoView({ behavior: 'smooth' });
                }
            }, 500);
        }
    }
}

/**
 * Menangani tampilan sidebar pada perangkat mobile
 */
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('show-mobile');
    }
}

// Tambahkan event untuk tombol toggle pada navbar mobile
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', toggleSidebar);
    }
});
