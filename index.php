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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* CSS SAMA SEPERTI SEBELUMNYA */
       
        .welcome-text { text-align: center; margin: 15px 0; color: #666; }
      
        .book-section { margin-top: 40px; }
        .section-title { font-size: 1.8em; color: #333; margin-bottom: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .carousel-container { position: relative; overflow: hidden; padding: 20px 0;   }
        .carousel-wrapper { width: 20px; height: 350px; display: flex; transition: transform 0.5s ease; gap: 20px; align-items: stretch; }
        .book-card { flex: 0 0 220px;  background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
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
         html {
        scroll-behavior: smooth;
    }
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
<body class="scroll-smooth">
    <div class="kontainer ">
        <?php if ($isLoggedIn): ?>
        
          <div class=" container nav-main flex bg-[#1C77D2] justify-between items-center py-3 text-white">
            <div class="logo flex items-center gap-3">
                <img class="rounded-full w-12 " src="assets/images/logo/logo-smk.png" alt="">
                <p>E-LIBRARY <br> <strong>SMK TADIKA PERTIWI</strong></p>
            </div>
           <div class="nav-buttons gap-12 flex font-bold">
                <a href="#" class="btn-secondary hover:text-yellow-400">Beranda</a>
                <a href="#about" class="btn-secondary  hover:text-yellow-400">Tentang</a>
                <a href="#contact" class="btn-secondary  hover:text-yellow-400">Kontak</a>
                <a href="#organisasi" class="btn-secondary  hover:text-yellow-400">Organisasi</a>
                <a href="logout.php" class="btn-danger  hover:text-black hover:rounded-lg hover:bg-white px-2">Keluar</a>
            </div>
          </div>
        <?php else: ?>
            
             <div class=" container nav-main flex bg-[#1C77D2] justify-between items-center py-3 text-white">
            <div class="logo flex items-center gap-3">
                <img class="rounded-full w-12 " src="assets/images/logo/logo-smk.png" alt="">
                <p>E-LIBRARY <br> <strong>SMK TADIKA PERTIWI</strong></p>
            </div>
           <div class="nav-buttons gap-12 flex font-bold">
                <a href="#" class="btn-secondary hover:text-yellow-400">Beranda</a>
                <a href="#about" class="btn-secondary  hover:text-yellow-400">Tentang</a>
                <a href="#contact" class="btn-secondary  hover:text-yellow-400">Kontak</a>
                <a href="#organisasi" class="btn-secondary  hover:text-yellow-400">Organisasi</a>
                <a href="login.php" class="btn-danger  hover:text-black hover:rounded-lg hover:bg-white px-2">Masuk</a>
            </div>
          </div>
        <?php endif; ?>
        <div class="bg-main">
            <img src="assets/images/logo/main-bg.png" alt="">
        </div>

        <div class="absolute top-40 ml-[200px] text-4xl font-bold space-y-5 text-white">
    <p>Halo <?php echo htmlspecialchars($nama_user); ?>👋</p>
    <p>Siap baca buku</p>
    <p>hari ini?</p>
    <a href="views/lihatBukuViews.php">
       <button class="flex items-center font-semibold text-xl mt-5 bg-white  text-blue-700 rounded-lg py-2 px-4 gap-2 hover:bg-yellow-400">
    Lihat buku
    <img src="assets/images/icon/maki_arrow.png" class="w-6" alt="">
</button>

    </a>
</div>


        <div class="book-section" id="katalog buku">
            <a href="" class="flex justify-center font-semibold text-xl">Koleksi Buku Pilihan</a>

            <?php if (!empty($books)): ?>
                <div class="carousel-container pl-[200px]">
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

   <div id="about" class="bg-[#1C77D2] w-full max-w-[800px] mx-auto p-10 text-white mt-5 mb-5 shadow-lg shadow-blue-200 rounded-lg">
    <img src="assets/images/logo/logo-smk.png" class="w-20 mx-auto mb-4 " alt="">
    
    <h1 class="text-2xl font-bold text-center mb-3">
        Perpustakaan SMK TADIKA PERTIWI
    </h1>

    <p class="text-center leading-7">
        Berada di lingkungan SMK Tadika Pertiwi yang beralamat di <br>
        Jl. Haji Jaera Np.1, Cinere, Depok. Perpustakaan ini terletak di <br>
        lantai 1 di samping ruang guru. Di perpustakaan ini juga <br>
        dilengkapi dengan sarana dan prasarana yang memadai guna <br>
        menunjang kegiatan pembelajaran di sekolah.
    </p>
</div>


    <div>
        <h2 class=" font-semibold text-xl my-5 mx-5 flex justify-center ">Berita E-Library</h2>
      
        <img src="assets/images/logo//Poster.svg" alt="Berita 1" class="w-[500px] mx-auto">
        
       
    </div>  
    <p class="text-xl font-semibold justify-center flex mt-5 mb-2">Lokasi Perpustakaan</p>

   <div class="w-full flex justify-center py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-0">

        <!-- MAP -->
        <div class="w-[300px] h-[300px] relative overflow-hidden shadow justify-center">
            <iframe 
                class="w-full h-full"
                src="https://www.google.com/maps?q=-6.340040503434626,106.78242625327886&hl=es;z=18&output=embed"
                allowfullscreen=""
                loading="lazy">
            </iframe>

            <div class="absolute top-2 left-2 flex items-center gap-2 bg-white/80 backdrop-blur-sm px-0 py-1 shadow text-sm">
                <img src="assets/images/icon/location-pin.png" class="w-4 h-4" alt="pin">
                <span class="font-semibold">Lokasi Perpustakaan</span>
            </div>
        </div>

        <!-- TEXT -->
      <div class="p-6 bg-[#d9e4f0] w-[320px] h-[300px]" id="contact">
    <h2 class="text-xl font-bold mb-3">Perpustakaan SMK Tadika Pertiwi</h2>

    <p class="mb-1">Jl. Haji Jaeran No.1, Cinere, Depok</p>

    <p class="mb-1">Telepon : +62895383578689</p>
    <p class="mb-1">E-mail : tadika.pertiwi@gmail.com</p>
    <p class="mb-3">G-mail : info@smktadikapertiiwi</p>

    <p class="mb-2 font-semibold">Ikuti Kami di Media Sosial</p>

    <div class="flex items-center gap-3 text-xl">
        <a href="https://www.facebook.com/share/14LoiwFZkmv/" class="fa-brands fa-facebook"></a>
        <a href="https://www.instagram.com/smktadikapertiwi_?igsh=cTV4NHlkc3BtODlm" class="fa-brands fa-instagram"></a>
        <a href="https://www.youtube.com/@smktadikapertiwi3159" class="fa-brands fa-youtube"></a>
    </div>
</div>


    </div>
</div>

<!-- ORGANISASI -->
<div id="organisasi" > 
    <p class="text-xl font-semibold justify-center flex mt-5 mb-5">Struktur Organisasi</p>
   <img src="assets/images/logo/Struktur.png" alt="Struktur Organisasi" class="w-[500px] mx-auto mb-10 shadow-lg shadow-blue-200">
</div>

        <footer class="bg-[#1F7BD8] text-white py-8 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">

        <!-- LEFT (Logo + Menu) -->
        <div class="flex flex-col items-start">
            <img src="assets/images/logo/logo-smk.png" class="w-16 mb-3" alt="logo">
            <ul class="space-y-1">
               <a href="index.php"> <li>Beranda</li></a>
                <a href="#katalog buku"><li>Katalog Buku</li></a>
                <a href="#about"><li>Tentang Kami</li>
            </ul></a>
        </div>

        <!-- MIDDLE (Shortcut Link) -->
        <div class="flex flex-col items-start mt-12 md:items-center">
            <h3 class="font-semibold mb-2">Shortcut Link :</h3>
            <ul class="space-y-1">
                <a href="https://www.smktadikapertiwi.sch.id/"><li>Website Profile Sekolah</li></a>
                <li>Website Perpustakaan</li>
            </ul>
        </div>

        <!-- RIGHT (Kontak) -->
        <div class="flex flex-col items-start mt-12 md:items-end">
            <h3 class="font-semibold mb-2">Kontak :</h3>
            <ul class="space-y-1">
                <li>0895383578689</li>
                <li>tadika.pertiwi@gmail.com</li>
                <li>info@smktadikapertiwi</li>
            </ul>
        </div>
    </div>

    <!-- COPYRIGHT -->
    <div class="text-center text-xs mt-6">
        © 2025 Perpustakaan SMK Tadika Pertiwi. All Rights Reserved.
    </div>
</footer>


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