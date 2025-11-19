<?php
session_start();
require_once 'config/database.php';

$isLoggedIn = false;
$nama_user = '';

if (isset($_SESSION['member_name'])) {
    $isLoggedIn = true;
    $nama_user = $_SESSION['member_name'];
}
else if (isset($_SESSION['user_name'])) {
    $isLoggedIn = true;
    $nama_user = $_SESSION['user_name'];
}

// AMBIL DATA CAROUSEL
// Hanya ambil data dari tabel 'homepage_books' yang aktif
$query = "SELECT b.id, b.judul_buku, b.penulis, b.penerbit, b.tahun_terbit, b.gambar, b.kode_buku 
          FROM buku b
          INNER JOIN homepage_books hb ON b.id = hb.buku_id
          WHERE hb.is_active = 1
          ORDER BY hb.urutan ASC";

$result = mysqli_query($conn, $query);
$books = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tadika Pertiwi</title>
    <style>
        /* CSS SAMA SEPERTI SEBELUMNYA */
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; text-align: center; }
        .welcome-text { text-align: center; margin: 15px 0; color: #666; }
        .nav-buttons { text-align: center; margin: 30px 0; }
        .nav-buttons a { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; border: 1px solid #ddd; }
        .btn-primary { background-color: #008CBA; color: white; border: none; }
        .btn-secondary { background-color: #f0f0f0; color: #333; }
        .btn-danger { background-color: #dc3545; color: white; border: none; }
        
        .book-section { margin-top: 40px; }
        .section-title { font-size: 1.8em; color: #333; margin-bottom: 20px; text-align: center; }
        
        .carousel-container { position: relative; overflow: hidden; padding: 20px 0; }
        .carousel-wrapper { display: flex; transition: transform 0.5s ease; gap: 20px; align-items: stretch; }
        .book-card { flex: 0 0 220px; min-width: 220px; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
        .book-card:hover { box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .book-cover { width: 100%; height: 280px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 2.5em; margin-bottom: 10px; overflow: hidden; }
        .book-cover img { width: 100%; height: 100%; object-fit: cover; }
        .book-title { font-weight: bold; color: #333; margin-bottom: 5px; height: 40px; overflow: hidden; font-size: 0.95em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .book-author { color: #666; font-size: 0.85em; margin-bottom: 3px; }
        .book-code { color: #999; font-size: 0.8em; margin-top: auto; }
        .carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,140,186,0.8); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 18px; z-index: 10; }
        .carousel-btn:hover { background: rgba(0,140,186,1); }
        .carousel-btn.prev { left: 10px; }
        .carousel-btn.next { right: 10px; }
        .carousel-dots { display: flex; justify-content: center; gap: 8px; margin-top: 15px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; background: #ddd; cursor: pointer; transition: all 0.3s; }
        .dot.active { background: #008CBA; width: 25px; border-radius: 5px; }

        /* ▼▼▼ CSS BARU UNTUK PESAN KOSONG ▼▼▼ */
        .empty-carousel {
            text-align: center;
            padding: 40px 20px;
            background-color: #fff;
            border: 1px dashed #ccc;
            border-radius: 8px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($isLoggedIn): ?>
            <h1>Selamat Datang Kembali!</h1>
            <p class="welcome-text">Halo, <strong><?php echo htmlspecialchars($nama_user); ?></strong>!</p>
            <p class="welcome-text">Anda sudah masuk ke sistem Perpustakaan Tadika Pertiwi.</p>
            <div class="nav-buttons">
                <a href="views/dashboardAdmin.php" class="btn-primary">Dashboard</a>
                <a href="logout.php" class="btn-danger">Keluar</a>
            </div>
        <?php else: ?>
            <h1>Perpustakaan Tadika Pertiwi</h1>
            <p class="welcome-text">Selamat datang di sistem perpustakaan kami</p>
            <div class="nav-buttons">
                <a href="#" class="btn-secondary">Beranda</a>
                <a href="#" class="btn-secondary">Tentang</a>
                <a href="#" class="btn-secondary">Organisasi</a>
                <a href="#" class="btn-secondary">Kontak</a>
                <a href="login.php" class="btn-primary">Masuk</a>
            </div>
        <?php endif; ?>

        <div>
            Siap baca buku hari ini?
            <a href="views/lihatBukuViews.php"><button>Lihat Buku</button></a>
        </div>

        <div class="book-section">
            <h2 class="section-title">Koleksi Buku Pilihan</h2>

            <?php if (!empty($books)): ?>
                <div class="carousel-container">
                    <button class="carousel-btn prev" onclick="moveCarousel(-1)">❮</button>
                    <div class="carousel-wrapper" id="carouselWrapper">
                        <?php foreach ($books as $book): ?>
                        
                        <a href="views/lihatBukuViews.php?id=<?php echo $book['id']; ?>" class="book-card" style="text-decoration: none; color: inherit;">
                            <div class="book-cover">
                                <?php if ($book['gambar'] && file_exists('assets/images/buku/' . $book['gambar'])): ?>
                                    <img src="assets/images/buku/<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover">
                                <?php else: ?>
                                    📚
                                <?php endif; ?>
                            </div>
                            <div class="book-title" title="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                                <?php echo htmlspecialchars($book['judul_buku']); ?>
                            </div>
                            <div class="book-author">Penulis: <?php echo htmlspecialchars($book['penulis']); ?></div>
                            <div class="book-code"><?php echo htmlspecialchars($book['kode_buku']); ?></div>
                        </a>

                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn next" onclick="moveCarousel(1)">❯</button>
                </div>
                <div class="carousel-dots" id="carouselDots"></div>

            <?php else: ?>
                <div class="empty-carousel">
                    <p>Belum ada koleksi buku pilihan yang ditampilkan.</p>
                    <?php if ($isLoggedIn): // Opsional: Pesan untuk admin ?>
                        <small>(Silakan tambahkan buku melalui menu Kelola Carousel di Dashboard)</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        </div>

    <div>
        <h1>Perpustakaan SMK Tadika Pertiwi</h1>
        <p>Berada di lingkungan SMK Tadika Pertiwi yang beralamat di <br>
           Jl. Haji Jaera Np.1, Cinere,Depok. Perpustakaan ini terletak di <br>
           lantai 1 di samping ruang guru. Di perpustakaan ini juga di <br>
           lengkapi dengan sarana dan prasarana yang memadai. Guna <br>
           untuk menunjang kegiatan pembelajaran di sekolah</p>
           <img src="assets/images/logo/logo-smk.png" alt="">
    </div>

    <div>
        <h2>VIDEO PROFILE PERPUSTAKAAN</h2>
        <h1><b>SMK TADIKA PERTIWI</b></h1>
        <video src="assets/images/logo/1.mp4"></video>
    </div>

    <script>
        let currentIndex = 0;
        const carouselWrapper = document.getElementById('carouselWrapper');
        
        // Cek jika wrapper ada (karena jika kosong, wrapper tidak dirender)
        if (carouselWrapper) {
            const cards = document.querySelectorAll('.book-card');
            const totalCards = cards.length;
            
            function getCardsPerView() {
                if (window.innerWidth < 600) return 1;
                if (window.innerWidth < 900) return 2;
                if (window.innerWidth < 1200) return 3;
                return 4;
            }

            let cardsPerView = getCardsPerView();
            let maxIndex = Math.max(0, totalCards - cardsPerView);

            const dotsContainer = document.getElementById('carouselDots');
            dotsContainer.innerHTML = '';

            const totalPages = Math.ceil(totalCards / cardsPerView);
            
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => goToSlide(i * cardsPerView); 
                dotsContainer.appendChild(dot);
            }

            function updateCarousel() {
                const cardWidth = cards[0].offsetWidth;
                const gap = 20;
                const offset = -(currentIndex * (cardWidth + gap));
                carouselWrapper.style.transform = `translateX(${offset}px)`;

                const currentPage = Math.floor(currentIndex / cardsPerView);
                const dots = document.querySelectorAll('.dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentPage);
                });
            }

            function moveCarousel(direction) {
                cardsPerView = getCardsPerView();
                maxIndex = Math.max(0, totalCards - cardsPerView);
                currentIndex += direction;
                if (currentIndex < 0) currentIndex = maxIndex;
                else if (currentIndex > maxIndex) currentIndex = 0;
                updateCarousel();
            }

            function goToSlide(index) {
                cardsPerView = getCardsPerView();
                maxIndex = Math.max(0, totalCards - cardsPerView);
                currentIndex = index;
                if (currentIndex > maxIndex) currentIndex = maxIndex;
                updateCarousel();
            }

            let autoplayInterval = setInterval(() => moveCarousel(1), 5000);
            carouselWrapper.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
            carouselWrapper.addEventListener('mouseleave', () => {
                clearInterval(autoplayInterval);
                autoplayInterval = setInterval(() => moveCarousel(1), 5000);
            });
            
            window.addEventListener('resize', () => {
                cardsPerView = getCardsPerView();
                maxIndex = Math.max(0, totalCards - cardsPerView);
                dotsContainer.innerHTML = '';
                const totalPages = Math.ceil(totalCards / cardsPerView);
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'dot' + (i === Math.floor(currentIndex / cardsPerView) ? ' active' : '');
                    dot.onclick = () => goToSlide(i * cardsPerView);
                    dotsContainer.appendChild(dot);
                }
                updateCarousel();
            });
        }
    </script>
</body>
</html>