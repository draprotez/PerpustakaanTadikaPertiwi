<?php
session_start();
require_once 'config/database.php';
include 'header.php';

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
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tadika Pertiwi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .carousel-wrapper { 
            display: flex; 
            transition: transform 0.5s ease; 
            gap: 20px; 
        }
        .book-card { 
            flex: 0 0 220px;
            scroll-snap-align: start;
        }
        @media (max-width: 768px) {
            .book-card { flex: 0 0 180px; }
        }
        .dot { 
            transition: all 0.3s; 
        }
        .dot.active { 
            background: #008CBA; 
            width: 25px; 
            border-radius: 5px; 
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- NAVBAR -->
    <nav class="bg-[#1C77D2] text-white sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img class="rounded-full w-10 h-10 md:w-12 md:h-12" src="assets/images/logo/logo-smk.png" alt="Logo">
                    <div class="text-xs md:text-sm">
                        <p class="leading-tight">E-LIBRARY</p>
                        <p class="font-bold leading-tight">SMK TADIKA PERTIWI</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex gap-6 lg:gap-12 font-bold text-sm lg:text-base">
                    <a href="#" class="hover:text-yellow-400 transition">Beranda</a>
                    <a href="#about" class="hover:text-yellow-400 transition">Tentang</a>
                    <a href="#contact" class="hover:text-yellow-400 transition">Kontak</a>
                    <a href="#organisasi" class="hover:text-yellow-400 transition">Organisasi</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="logout.php" class="hover:bg-white hover:text-black px-3 py-1 rounded-lg transition">Keluar</a>
                    <?php else: ?>
                        <a href="login.php" class="hover:bg-white hover:text-black px-3 py-1 rounded-lg transition">Masuk</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <div class="flex flex-col gap-3 font-semibold">
                    <a href="#" class="hover:text-yellow-400 transition">Beranda</a>
                    <a href="#about" class="hover:text-yellow-400 transition">Tentang</a>
                    <a href="#contact" class="hover:text-yellow-400 transition">Kontak</a>
                    <a href="#organisasi" class="hover:text-yellow-400 transition">Organisasi</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="logout.php" class="bg-white text-blue-700 px-3 py-2 rounded-lg text-center">Keluar</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-white text-blue-700 px-3 py-2 rounded-lg text-center">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative w-full h-[400px] md:h-[500px] lg:h-[600px] overflow-hidden">
        <img src="assets/images/logo/main-bg.png" alt="Background" class="w-full h-full object-cover">
        
        <!-- Overlay Text -->
        <div class="absolute inset-0 flex items-center px-4 md:px-8 lg:px-16">
            <div class="text-white max-w-xl">
                <p class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2">Halo <?php echo htmlspecialchars($nama_user); ?>👋</p>
                <p class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2">Siap baca buku</p>
                <p class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">hari ini?</p>
                <a href="views/lihatBukuViews.php">
                    <button class="flex items-center font-semibold text-base md:text-xl bg-white text-blue-700 rounded-lg py-2 px-4 md:py-3 md:px-6 gap-2 hover:bg-yellow-400 transition shadow-lg">
                        Lihat buku
                        <img src="assets/images/icon/maki_arrow.png" class="w-5 md:w-6" alt="arrow">
                    </button>
                </a>
            </div>
        </div>
    </div>

    <!-- BOOK CAROUSEL SECTION -->
    <div class="container mx-auto px-4 md:px-8 py-8 md:py-12" id="katalog-buku">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-center mb-6 md:mb-8">Koleksi Buku Pilihan</h2>

        <?php if (!empty($books)): ?>
            <div class="relative">
                <!-- Carousel Container -->
                <div class="overflow-hidden px-2 md:px-12">
                    <div class="carousel-wrapper" id="carouselWrapper">
                        <?php foreach ($books as $book): ?>
                        <a href="views/lihatBukuViews.php?id=<?php echo $book['id']; ?>" 
                           class="book-card bg-white rounded-lg shadow-md hover:shadow-xl transition p-4 flex flex-col">
                            <div class="w-full h-48 md:h-64 bg-gray-100 rounded-lg mb-3 overflow-hidden flex items-center justify-center">
                                <?php if ($book['gambar'] && file_exists('assets/images/buku/' . $book['gambar'])): ?>
                                    <img src="assets/images/buku/<?php echo htmlspecialchars($book['gambar']); ?>" 
                                         alt="Cover" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-4xl text-gray-400">📚</span>
                                <?php endif; ?>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-gray-800 mb-1 line-clamp-2 h-10 md:h-12">
                                <?php echo htmlspecialchars($book['judul_buku']); ?>
                            </h3>
                            <p class="text-xs md:text-sm text-gray-600 mb-1">Penulis: <?php echo htmlspecialchars($book['penulis']); ?></p>
                            <p class="text-xs text-gray-400 mt-auto"><?php echo htmlspecialchars($book['kode_buku']); ?></p>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button onclick="moveCarousel(-1)" 
                        class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 bg-blue-600/80 hover:bg-blue-600 text-white w-10 h-10 rounded-full shadow-lg transition">
                    ❮
                </button>
                <button onclick="moveCarousel(1)" 
                        class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 bg-blue-600/80 hover:bg-blue-600 text-white w-10 h-10 rounded-full shadow-lg transition">
                    ❯
                </button>
            </div>

            <!-- Dots Indicator -->
            <div class="flex justify-center gap-2 mt-6" id="carouselDots"></div>

        <?php else: ?>
            <div class="text-center py-12 px-4 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-gray-500 italic">Belum ada koleksi buku pilihan yang ditampilkan.</p>
                <?php if ($isLoggedIn): ?>
                    <small class="text-gray-400">(Silakan tambahkan buku melalui menu Kelola Carousel di Dashboard)</small>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ABOUT SECTION -->
    <div id="about" class="container mx-auto px-4 md:px-8 py-8">
        <div class="bg-[#1C77D2] max-w-4xl mx-auto p-6 md:p-10 text-white rounded-lg shadow-lg">
            <img src="assets/images/logo/logo-smk.png" class="w-16 md:w-20 mx-auto mb-4" alt="Logo">
            
            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-center mb-4">
                Perpustakaan SMK TADIKA PERTIWI
            </h2>

            <p class="text-sm md:text-base text-center leading-relaxed">
                Berada di lingkungan SMK Tadika Pertiwi yang beralamat di
                Jl. Haji Jaera Np.1, Cinere, Depok. Perpustakaan ini terletak di
                lantai 1 di samping ruang guru. Di perpustakaan ini juga
                dilengkapi dengan sarana dan prasarana yang memadai guna
                menunjang kegiatan pembelajaran di sekolah.
            </p>
        </div>
    </div>

    <!-- NEWS SECTION -->
    <div class="container mx-auto px-4 md:px-8 py-8">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-center mb-6">Berita E-Library</h2>
        <img src="assets/images/logo/Poster.svg" alt="Berita" class="w-full max-w-lg md:max-w-xl mx-auto rounded-lg shadow-lg">
    </div>

    <!-- LOCATION & CONTACT SECTION -->
    <div class="container mx-auto px-4 md:px-8 py-8">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-center mb-6">Lokasi Perpustakaan</h2>
        
        <div class="flex flex-col md:flex-row justify-center items-stretch gap-0 max-w-3xl mx-auto">
            <!-- Map -->
            <div class="w-full md:w-1/2 h-64 md:h-80 relative overflow-hidden shadow-lg">
                <iframe 
                    class="w-full h-full"
                    src="https://www.google.com/maps?q=-6.340040503434626,106.78242625327886&hl=es;z=18&output=embed"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
                <div class="absolute top-2 left-2 flex items-center gap-2 bg-white/90 backdrop-blur-sm px-3 py-2 rounded shadow text-xs md:text-sm">
                    <img src="assets/images/icon/location-pin.png" class="w-4 h-4" alt="pin">
                    <span class="font-semibold">Lokasi Perpustakaan</span>
                </div>
            </div>

            <!-- Contact Info -->
            <div id="contact" class="w-full md:w-1/2 p-6 bg-[#d9e4f0] h-64 md:h-80 overflow-y-auto">
                <h3 class="text-lg md:text-xl font-bold mb-3">Perpustakaan SMK Tadika Pertiwi</h3>
                <p class="text-sm mb-2">Jl. Haji Jaeran No.1, Cinere, Depok</p>
                <p class="text-sm mb-2">Telepon: +62895383578689</p>
                <p class="text-sm mb-2">E-mail: tadika.pertiwi@gmail.com</p>
                <p class="text-sm mb-4">G-mail: info@smktadikapertiwi</p>
                
                <p class="text-sm font-semibold mb-2">Ikuti Kami di Media Sosial</p>
                <div class="flex items-center gap-4 text-xl md:text-2xl">
                    <a href="https://www.facebook.com/share/14LoiwFZkmv/" class="hover:text-blue-600 transition">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/smktadikapertiwi_?igsh=cTV4NHlkc3BtODlm" class="hover:text-pink-600 transition">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/@smktadikapertiwi3159" class="hover:text-red-600 transition">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ORGANIZATION STRUCTURE -->
    <div id="organisasi" class="container mx-auto px-4 md:px-8 py-8">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-center mb-6">Struktur Organisasi</h2>
        <img src="assets/images/logo/Struktur.png" alt="Struktur Organisasi" 
             class="w-full max-w-2xl mx-auto rounded-lg shadow-lg">
    </div>

    <!-- FOOTER -->
    <footer class="bg-[#1F7BD8] text-white py-8 px-4 md:px-8 mt-12">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <!-- Left -->
                <div>
                    <img src="assets/images/logo/logo-smk.png" class="w-16 mb-4" alt="logo">
                    <ul class="space-y-2">
                        <li><a href="index.php" class="hover:text-yellow-400 transition">Beranda</a></li>
                        <li><a href="#katalog-buku" class="hover:text-yellow-400 transition">Katalog Buku</a></li>
                        <li><a href="#about" class="hover:text-yellow-400 transition">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Middle -->
                <div class="md:text-center">
                    <h3 class="font-semibold mb-3">Shortcut Link:</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.smktadikapertiwi.sch.id/" class="hover:text-yellow-400 transition">Website Profile Sekolah</a></li>
                        <li>Website Perpustakaan</li>
                    </ul>
                </div>

                <!-- Right -->
                <div class="md:text-right">
                    <h3 class="font-semibold mb-3">Kontak:</h3>
                    <ul class="space-y-2">
                        <li>0895383578689</li>
                        <li>tadika.pertiwi@gmail.com</li>
                        <li>info@smktadikapertiwi</li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-xs mt-8 pt-6 border-t border-white/20">
                © 2025 Perpustakaan SMK Tadika Pertiwi. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Carousel Logic
        let currentIndex = 0;
        const carouselWrapper = document.getElementById('carouselWrapper');
        
        if (carouselWrapper) {
            const cards = document.querySelectorAll('.book-card');
            const totalCards = cards.length;
            
            function getCardsPerView() {
                if (window.innerWidth < 640) return 1;
                if (window.innerWidth < 768) return 2;
                if (window.innerWidth < 1024) return 3;
                return 4;
            }

            let cardsPerView = getCardsPerView();
            let maxIndex = Math.max(0, totalCards - cardsPerView);

            const dotsContainer = document.getElementById('carouselDots');
            
            function createDots() {
                dotsContainer.innerHTML = '';
                const totalPages = Math.ceil(totalCards / cardsPerView);
                
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-gray-300 cursor-pointer dot' + (i === 0 ? ' active' : '');
                    dot.onclick = () => goToSlide(i * cardsPerView);
                    dotsContainer.appendChild(dot);
                }
            }

            createDots();

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
                currentIndex = Math.min(index, maxIndex);
                updateCarousel();
            }

            // Auto-play
            let autoplayInterval = setInterval(() => moveCarousel(1), 5000);
            carouselWrapper.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
            carouselWrapper.addEventListener('mouseleave', () => {
                clearInterval(autoplayInterval);
                autoplayInterval = setInterval(() => moveCarousel(1), 5000);
            });
            
            // Responsive handler
            window.addEventListener('resize', () => {
                cardsPerView = getCardsPerView();
                maxIndex = Math.max(0, totalCards - cardsPerView);
                createDots();
                updateCarousel();
            });
        }
    </script>
</body>
</html>