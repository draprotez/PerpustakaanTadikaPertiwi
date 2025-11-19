-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 09:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan_tadikapertiwi`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `issn` varchar(50) DEFAULT NULL,
  `penerbit` varchar(200) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `kelas` enum('X','XI','XII','UMUM') DEFAULT NULL COMMENT 'Target kelas buku (X, XI, XII, atau UMUM untuk non-spesifik)',
  `kurikulum` enum('Merdeka','K-13','KTSP','UMUM') DEFAULT NULL,
  `total_copy` int(11) DEFAULT 1,
  `salinan_tersedia` int(11) DEFAULT 1,
  `gambar` varchar(255) DEFAULT NULL COMMENT 'Nama file gambar cover',
  `kode_buku` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul_buku`, `penulis`, `isbn`, `issn`, `penerbit`, `tahun_terbit`, `kategori_id`, `kelas`, `kurikulum`, `total_copy`, `salinan_tersedia`, `gambar`, `kode_buku`, `created_at`) VALUES
(341, 'Pendidikan Agama Islam', 'H.A .sholeh Dimyati', '978-623-522-283-0', NULL, 'Erlangga', '2025', NULL, 'X', 'Merdeka', 53, 53, NULL, 'PEN-X-001', '2025-11-17 15:13:10'),
(342, 'Pendidikan Agama Islam', 'Zayyinah Shaliha', '978-602-399-091-7', NULL, 'Mediatama', '2020', NULL, 'X', 'K-13', 5, 5, NULL, 'PEN-X-002', '2025-11-17 15:13:10'),
(343, 'Pendidikan Agama Islam', 'H.A .sholeh Dimyati', '978-623-266-775-4', NULL, 'Erlangga', '2022', NULL, 'XI', 'Merdeka', 33, 33, NULL, 'PEN-XI-003', '2025-11-17 15:13:10'),
(344, 'Pendidikan Agama Islam', 'Ahmad Malik', '978-623-328-938-2', NULL, 'Bumi Aksara', '2024', NULL, 'XII', 'Merdeka', 36, 36, NULL, 'PEN-XII-004', '2025-11-17 15:13:10'),
(345, 'Bahasa Inggris  SPLASH', 'Anik.M.Indriastuti', '978-623-266-629-0', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 67, 67, NULL, 'BAH-X-005', '2025-11-17 15:13:10'),
(346, 'Bahasa Inggris  SPLASH', 'Anik.M.Indriastuti', '978-623-266-950-5', NULL, 'Erlangga', '2023', NULL, 'XI', 'Merdeka', 34, 34, NULL, 'BAH-XI-006', '2025-11-17 15:13:10'),
(347, 'Bahasa Inggris  Modul', 'M.Haman Abdurrohman', '978-6023-399-611-7', NULL, 'Mediatama', '2017', NULL, 'XII', 'K-13', 10, 10, NULL, 'BAH-XII-007', '2025-11-17 15:13:10'),
(348, 'Bahasa inggris SPLASH', 'Anik.M.Indriastuti', '978-623-266-771-6', NULL, 'Erlangga', '2022', NULL, 'XI', 'Merdeka', 10, 10, NULL, 'BAH-XI-008', '2025-11-17 15:13:10'),
(349, 'Bahasa inggris Esensi', 'Yuni prihartanti & Sari Ratnaninggish', '978-602-488-756-8', NULL, 'Mediatama', '2025', NULL, 'XII', 'Merdeka', 17, 17, NULL, 'BAH-XII-009', '2025-11-17 15:13:10'),
(350, 'Bahasa inggris', 'Dendy Desmal & Ikhwan Muslim', '978-623-328-935-1', NULL, 'Bumi Aksara', '2024', NULL, 'XII', 'Merdeka', 19, 19, NULL, 'BAH-XII-010', '2025-11-17 15:13:10'),
(351, 'TKA Bahasa Inggris', 'Susiningsih & Prasetya Adhi Wardhana', '978-979-285-789-4', NULL, 'Intan Perwira Edukasi', '2025', NULL, 'UMUM', 'Merdeka', 50, 50, NULL, 'TKA-UMUM-011', '2025-11-17 15:13:10'),
(352, 'TKA Matematika', 'Dini Fima Udari & Herdita Fajar Isnani', '978-979-285-798-0', NULL, 'Intan Perwira Edukasi', '2025', NULL, 'UMUM', 'Merdeka', 49, 49, NULL, 'TKA-UMUM-012', '2025-11-17 15:13:10'),
(353, 'TKA Bahasa indonesia', 'Arfah Rizky & Metha sandra Santhi', '978-979-285-788-7', NULL, 'Intan Perwira Edukasi', '2025', NULL, 'UMUM', 'Merdeka', 48, 48, NULL, 'TKA-UMUM-013', '2025-11-17 15:13:10'),
(354, 'IPAS Rumpun bisnis', 'Dwi harti,DKK', '978-623-266-748-8', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 8, 8, NULL, 'IPA-X-014', '2025-11-17 15:13:10'),
(355, 'IPAS Rumpun Teknologi', 'Berti Sagendra,dkk', '978-623-266-740-2', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 10, 10, NULL, 'IPA-X-015', '2025-11-17 15:13:10'),
(356, 'Projek Kreatif & Wirausaha bidang bisnis dan managemen', 'Dwi harti,DKK', '978-623-522-324-7', NULL, 'Erlangga', '2025', NULL, 'XI', 'Merdeka', 6, 6, NULL, 'PRO-XI-016', '2025-11-17 15:13:10'),
(357, 'Projek Kreatif& Wirausaha bidang Teknologi Informasi', 'Andi Novianto &Maryanti', '978-623-180-755-7', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 6, 6, NULL, 'PRO-XI-018', '2025-11-17 15:13:10'),
(358, 'Sejarah', 'Ratna hapsari,M,adil', '978-623-266-681-8', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 37, 37, NULL, 'SEJ-X-021', '2025-11-17 15:13:10'),
(359, 'Sejarah', 'Ratna hapsari,M,adil', '978-623-266-691-7', NULL, 'Erlangga', '2022', NULL, 'XI', 'Merdeka', 23, 23, NULL, 'SEJ-XI-022', '2025-11-17 15:13:10'),
(360, 'Pendidikan Pancasila & kewarganegaraan', 'Fuad aljihad', '978-623-522-144-1', NULL, 'Erlangga', '2025', NULL, 'X', 'Merdeka', 51, 51, NULL, 'PEN-X-023', '2025-11-17 15:13:10'),
(361, 'Pendidikan Pancasila & kewarganegaraan', 'Yusuf Kardiman,DKK', '978-623-180-049-7', NULL, 'Erlangga', '2023', NULL, 'XI', 'Merdeka', 32, 32, NULL, 'PEN-XI-024', '2025-11-17 15:13:10'),
(362, 'Pendidikan Pancasila & kewarganegaraan', 'Sholicin Dwi Prasetyo', '978-602-399-094-8', NULL, 'Mediatama', '2017', NULL, 'XI', 'K-13', 10, 10, NULL, 'PEN-XI-025', '2025-11-17 15:13:10'),
(363, 'Pendidikan Pancasila & kewarganegaraan', 'Dwi Winarto', '978-602-444-316-0', NULL, 'Bumi Aksara', '2018', NULL, 'XI', 'K-13', 2, 2, NULL, 'PEN-XI-026', '2025-11-17 15:13:10'),
(364, 'Pendidikan Pancasila & kewarganegaraan', 'Dwi Catur Nugroho', '978-602-399-829-6', NULL, 'Mediatama', '2017', NULL, 'XI', 'K-13', 10, 10, NULL, 'PEN-XI-027', '2025-11-17 15:13:10'),
(365, 'Pendidikan Pancasila & kewarganegaraan', 'Istiana Nen Arienti,Deni Septianto Sombogo', '978-602-488-792-6', NULL, 'Erlangga', '2025', NULL, 'XII', 'Merdeka', 16, 16, NULL, 'PEN-XII-028', '2025-11-17 15:13:10'),
(366, 'Pendidikan Pancasila & kewarganegaraan', 'Rochimudin', '78-623-328-937-5', NULL, 'Bumi Aksara', '2024', NULL, 'XII', 'Merdeka', 19, 19, NULL, 'PEN-XII-029', '2025-11-17 15:13:10'),
(367, 'Bahasa Indonesia', 'Pipit Dwi Komariah,Ss.,M.pd', '978-623-266-683-2', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 53, 53, NULL, 'BAH-X-030', '2025-11-17 15:13:10'),
(368, 'Bahasa Indonesia', 'Pipit Dwi Komariah,Ss.,M.pd', '978-623-522-309-4', NULL, 'Erlangga', '2025', NULL, 'XI', 'Merdeka', 36, 36, NULL, 'BAH-XI-031', '2025-11-17 15:13:10'),
(369, 'Bahasa Indonesia', 'Astris Prameswari,Endah Tri Priyatni', '978-623-328-934-4', NULL, 'Bumi Aksara', '2024', NULL, 'XII', 'Merdeka', 20, 20, NULL, 'BAH-XII-032', '2025-11-17 15:13:10'),
(370, 'Bahasa Indonesia', 'Sekar Galuh Endah P.L,Sarwa indah Ika.W', '978-602-488-769-8', NULL, 'Mediatama', '2025', NULL, 'XII', 'Merdeka', 17, 17, NULL, 'BAH-XII-033', '2025-11-17 15:13:10'),
(371, 'Dasar Desain Grafis', 'Rudi Setiawan', '78-602-399-275-1', NULL, 'Mediatama', '2018', NULL, 'X', 'K-13', 3, 3, NULL, 'DAS-X-034', '2025-11-17 15:13:10'),
(372, 'Dasar -Dasar Animasi', 'Akhmad Syaiful Anwar', '978-623-522-592-0', NULL, 'Erlangga', '2025', NULL, 'X', 'Merdeka', 7, 7, NULL, 'DAS-X-035', '2025-11-17 15:13:10'),
(373, 'Desain Grafis Percetakan', 'Noviana', '978-602-399-276-8', NULL, 'Mediatama', '2020', NULL, 'XI', 'K-13', 3, 3, NULL, 'DES-XI-036', '2025-11-17 15:13:10'),
(374, 'Animasi 2D dan 3D', 'Rena Anggita Putri', '978-602-399-282-9', NULL, 'Mediatama', '2020', NULL, 'XI', 'K-13', 3, 3, NULL, 'ANI-XI-037', '2025-11-17 15:13:10'),
(375, 'Animasi 2D dan 3D', 'Rida Mulyadi', '978-602-445-362-6', NULL, 'yudhitira', '2018', NULL, 'XI', 'K-13', 5, 5, NULL, 'ANI-XI-038', '2025-11-17 15:13:10'),
(376, 'Perangkat Lunak Desain', 'Sumantoro kasdhani', '978-623-180-704-3', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 7, 7, NULL, 'PER-XI-039', '2025-11-17 15:13:10'),
(377, 'Proses Produksi Desain', 'Ahmad Fauzi,Arif Fitra,Berti Sagendra', '978-623-522-609-5', NULL, 'Erlangga', '2025', NULL, 'XI', 'Merdeka', 3, 3, NULL, 'PRO-XI-040', '2025-11-17 15:13:10'),
(378, 'Karya Desain', 'Sumantoro kasdhani', '978-623-180-785-4', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 7, 7, NULL, 'KAR-XI-041', '2025-11-17 15:13:10'),
(379, 'Prinsip Dasar Desain dan Komunikasi', 'Gilang Gerrialga,DKK', '978-623-180-565-3', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 3, 3, NULL, 'PRI-XI-042', '2025-11-17 15:13:10'),
(380, 'Teknik Pengolahan Audio dan Vidio', 'Sumantoro kasdhani', '978-602-486-573-3', NULL, 'Erlangga', '2019', NULL, 'XII', 'K-13', 11, 11, NULL, 'TEK-XII-043', '2025-11-17 15:13:10'),
(381, 'Karya Desain', 'Sumantoro kasdhani', '978-623-522-029-1', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 3, 3, NULL, 'KAR-XII-044', '2025-11-17 15:13:10'),
(382, 'Prinsip Dasar Desain dan Komunikasi', 'Gilang Gerrialga,DKK', '978-623-522-030-7', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 3, 3, NULL, 'PRI-XII-045', '2025-11-17 15:13:10'),
(383, 'Perangkat Lunak Desain', 'Sumantoro kasdhani', '978-623-522-031-4', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 3, 3, NULL, 'PER-XII-046', '2025-11-17 15:13:10'),
(384, 'Desain Komunikasi Visual', 'M.Harun Rosyid,DKK', '978-602-488-915-9', NULL, 'Mediatama', '2025', NULL, 'XII', 'Merdeka', 17, 17, NULL, 'DES-XII-047', '2025-11-17 15:13:10'),
(385, 'Teknik Pengolahan Audio dan Vidio', 'Rudy Setiawan', '978-602-399-285-0', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 3, 3, NULL, 'TEK-XII-048', '2025-11-17 15:13:10'),
(386, 'Desain Media Interaktif', 'Noviana', '978-602-399-278-2', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 3, 3, NULL, 'DES-XII-049', '2025-11-17 15:13:10'),
(387, 'Kearsipan', 'Agua Mulyono', '978-602-444-280-4', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 2, 2, NULL, 'KEA-X-050', '2025-11-17 15:13:10'),
(388, 'Korespondensi', 'Agua Mulyono', '978-602-444-294-1', NULL, 'Bumi Aksara', '2016', NULL, 'X', 'K-13', 4, 4, NULL, 'KOR-X-051', '2025-11-17 15:13:10'),
(389, 'Teknologi Perkantoran', 'M.Lutfi Hakim', '978-602-399-205-8', NULL, 'Mediatama', '2018', NULL, 'X', 'K-13', 1, 1, NULL, 'TEK-X-052', '2025-11-17 15:13:10'),
(390, 'Korespondensi 1', 'Donny H.Fahsani', '978-602-445-407-4', NULL, 'yudhitira', '2018', NULL, 'X', 'K-13', 9, 9, NULL, 'KOR-X-053', '2025-11-17 15:13:10'),
(391, 'Korespondensi', 'Dra.Aan .Hariyanah', '978-979-778-249-8', NULL, 'HUP', '2016', NULL, 'X', 'K-13', 8, 8, NULL, 'KOR-X-054', '2025-11-17 15:13:10'),
(392, 'Dasar-Dasar Manajemen Perkantoran Dan Pelayanan Bisnis vol 2', 'Sri mulyani', '978-623-266-939-0', NULL, 'Erlangga', '2023', NULL, 'X', 'Merdeka', 8, 8, NULL, 'DAS-X-055', '2025-11-17 15:13:10'),
(393, 'Dasar-Dasar Manajemen Perkantoran Dan Pelayanan Bisnis vol 1', 'Sri mulyani', '978-623-266-938-3', NULL, 'Erlangga', '2023', NULL, 'X', 'Merdeka', 7, 7, NULL, 'DAS-X-056', '2025-11-17 15:13:10'),
(394, 'Kearsipan  C2', 'M.Lutfi Hakim', '978-602-399-212-6', NULL, 'Mediatama', '2013', NULL, 'X', 'K-13', 2, 2, NULL, 'KEA-X-057', '2025-11-17 15:13:10'),
(395, 'Kearsipan Program Keahlian Manajemen Perkantoran', 'Daryo Susmanto', '978-602-445-317-6', NULL, 'yudhitira', '2018', NULL, 'X', 'K-13', 9, 9, NULL, 'KEA-X-058', '2025-11-17 15:13:10'),
(396, 'Pengelolaan keuangan Sederhana', 'Dwi harti,DKK', '978-623-180-629-8', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 7, 7, NULL, 'PEN-XI-059', '2025-11-17 15:13:10'),
(397, 'Teknologi Perkantoran Fase F', 'Sri Endang Rahayu,DKK', '978-623-180-633-8', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 3, 3, NULL, 'TEK-XI-060', '2025-11-17 15:13:10'),
(398, 'Pengelolaan Kearsipan Fase F', 'Sri Rahayu,Sri Muljani', '978-623-180-641-3', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 3, 3, NULL, 'PEN-XI-061', '2025-11-17 15:13:10'),
(399, 'Administrasi Keuangan', 'Drs.Agus. Syarif', '978-979-778-262-7', NULL, 'HUP', '2017', NULL, 'XI', 'K-13', 16, 16, NULL, 'ADM-XI-062', '2025-11-17 15:13:10'),
(400, 'Otomatisasi Tata Kelola Humas dan Keprotokolan', 'Nurul Bekti Praamudhita', '978-602-399-208-9', NULL, 'Mediatama', '2018', NULL, 'XI', 'K-13', 3, 3, NULL, 'OTO-XI-063', '2025-11-17 15:13:10'),
(401, 'otomatisasi Tata Kelola  Kepegawaian', 'Nurul Bekti Praamudhita', '978-602-399-206-5', NULL, 'Mediatama', '2018', NULL, 'XI', 'K-13', 1, 1, NULL, 'OTO-XI-064', '2025-11-17 15:13:10'),
(402, 'Otomatisasi Tata Kelola Keuangan', 'Nurul Bekti Praamudhita', '978-602-339-250-8', NULL, 'Mediatama', '2019', NULL, 'XI', 'K-13', 2, 2, NULL, 'OTO-XI-065', '2025-11-17 15:13:10'),
(403, 'Otomatisasi Tata Kelola Humas dan Keprotokolan 1', 'Tezar Qoyim', '978-602-445-172-1', NULL, 'Yudhitira', '2018', NULL, 'XI', 'K-13', 9, 9, NULL, 'OTO-XI-067', '2025-11-17 15:13:10'),
(404, 'Otomatisasi Tata Kelola Sarana dan Prasarana 1', 'Dwi Kurniawan', '978-602-299-176-9', NULL, 'yudhitira', '2018', NULL, 'XI', 'K-13', 4, 4, NULL, 'OTO-XI-068', '2025-11-17 15:13:10'),
(405, 'Otomatisasi Tata Kelola  Keuangan  1', 'Anis Muftias', '978-602-445-330-5', NULL, 'yudhitira', '2018', NULL, 'XI', 'K-13', 5, 5, NULL, 'OTO-XI-069', '2025-11-17 15:13:10'),
(406, 'Administrasi Humas & keprotokolan', 'Dra.Aan .Hariyanah ,DKK', '978-979-778-263-4', NULL, 'HUP', '2017', NULL, 'XI', 'K-13', 18, 18, NULL, 'ADM-XI-070', '2025-11-17 15:13:10'),
(407, 'Administrasi Kepegawain', 'Drs.Uu Supardi', '978-979-778-299-3', NULL, 'HUP', '2017', NULL, 'XI', 'K-13', 2, 2, NULL, 'ADM-XI-071', '2025-11-17 15:13:10'),
(408, 'Administrasi Sarana Dan Prasarana', 'Dra.Tati Sutarni', '978-979-778-298-6', NULL, 'HUP', '2017', NULL, 'XI', 'K-13', 13, 13, NULL, 'ADM-XI-072', '2025-11-17 15:13:10'),
(409, 'Administrasi Humas & keprotokolan', 'Dr.Suranto,D.T.,M.M', '978-979-29-6258-1', NULL, 'Andi Offset', '2016', NULL, 'XI', 'K-13', 2, 2, NULL, 'ADM-XI-073', '2025-11-17 15:13:10'),
(410, 'Teknologi Perkantoran Keahlian Manajemen Perkantoran', 'Agus Mulyono', '978-602-444-348-1', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 2, 2, NULL, 'TEK-X-074', '2025-11-17 15:13:10'),
(411, 'Otomatisasi Tata Kelola Keuangan', 'Dwi harti,DKK', '978-602-486-628-9', NULL, 'Erlangga', '2019', NULL, 'XII', 'K-13', 11, 11, NULL, 'OTO-XII-075', '2025-11-17 15:13:10'),
(412, 'Otomatisasi Tata Kelola Humas dan Keprotokolan', 'Dwi harti,DKK', '978-602-486-353-1', NULL, 'Erlangga', '2019', NULL, 'XII', 'K-13', 10, 10, NULL, 'OTO-XII-076', '2025-11-17 15:13:10'),
(413, 'Pengelola Keuangan Sederhana', 'Dwi harti,DKK', '978-623-522-073-4', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 3, 3, NULL, 'PEN-XII-077', '2025-11-17 15:13:10'),
(414, 'Otimatisasi Tata Kelola Humas dan Keprotokolan', 'Dwi harti,DKK', '978-623-522-069-7', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 7, 7, NULL, 'OTI-XII-078', '2025-11-17 15:13:10'),
(415, 'Otomatisasi Tata Kelola Humas dan Keprotokolan C3', 'Nurul Bekti Praamudhita,DKK', '978-602-399-209-6', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 3, 3, NULL, 'OTO-XII-079', '2025-11-17 15:13:10'),
(416, 'Otomatisasi Tata Kelola Sarana dan Prasarana C3', 'Abigali.J.K', '978-602-399-211-9', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 1, 1, NULL, 'OTO-XII-081', '2025-11-17 15:13:10'),
(417, 'Otomatisasi Tata Kelola  Kepegawain', 'Nurul Bekti Pramudhita,DKK', '978-602-399-207-2', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 1, 1, NULL, 'OTO-XII-083', '2025-11-17 15:13:10'),
(418, 'Matematika', 'Arif Ediyanti,DKK', '978-623-266-675-7', NULL, 'Erlangga', '2022', NULL, 'X', 'Merdeka', 55, 55, NULL, 'MAT-X-084', '2025-11-17 15:13:10'),
(419, 'Matematika', 'Arif Ediyanti,DKK', '978-623-266-798-3', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 20, 20, NULL, 'MAT-XI-085', '2025-11-17 15:13:10'),
(420, 'Matematika', 'Arif Ediyanti,DKK', '978-623-266-791-4', NULL, 'Erlangga', '2022', NULL, 'XI', 'Merdeka', 10, 10, NULL, 'MAT-XI-086', '2025-11-17 15:13:10'),
(421, 'Matematika', 'Abdul Rahman As\'ari', '978-602-1127-114-5', NULL, 'Kemendikbud', '2018', NULL, 'XII', 'KTSP', 94, 94, NULL, 'MAT-XII-087', '2025-11-17 15:13:10'),
(422, 'Matematika', 'Abdul Rahman As\'ari', '978-602-282-982-3', NULL, 'Kemendikbud', '2017', NULL, 'XII', 'KTSP', 6, 6, NULL, 'MAT-XII-090', '2025-11-17 15:13:10'),
(423, 'Simulasi Komunikasi Digital', 'Patriyanto', '978-602-445-333-6', NULL, 'Yudistira', '2018', NULL, 'X', 'KTSP', 8, 8, NULL, 'SIM-X-091', '2025-11-17 15:13:10'),
(424, 'Pemrograman Dasar', 'Yuliana Ardianti', '978-602-444-310-8', NULL, 'Bumi Aksara', '2018', NULL, 'X', 'K-13', 1, 1, NULL, 'PEM-X-092', '2025-11-17 15:13:10'),
(425, 'Pemrograman Web', 'Andi Novianto', '978-602-434-175-6', NULL, 'Erlangga', '2017', NULL, 'X', 'K-13', 2, 2, NULL, 'PEM-X-093', '2025-11-17 15:13:10'),
(426, 'Komputer dan Jaringan Dasar', 'Rudy Setiawan', '978-602-399-261-4', NULL, 'Mediatama', '2017', NULL, 'X', 'K-13', 3, 3, NULL, 'KOM-X-094', '2025-11-17 15:13:10'),
(427, 'Pemrograman Beovientasi Objek', 'Rudy Setiawan,DKK', '978-602-399-290-4', NULL, 'Mediatama', '2018', NULL, 'XI', 'K-13', 8, 8, NULL, 'PEM-XI-095', '2025-11-17 15:13:10'),
(428, 'Basis Data', 'Noviana', '978-602-399-263-8', NULL, 'Mediatama', '2018', NULL, 'XI', 'K-13', 4, 4, NULL, 'BAS-XI-096', '2025-11-17 15:13:10'),
(429, 'Pemrograman web dan perangkat Bergerak', 'Linda Marwati', '978-602-399-292-8', NULL, 'Mediatama', '2013', NULL, 'XI', 'KTSP', 3, 3, NULL, 'PEM-XI-097', '2025-11-17 15:13:10'),
(430, 'Pemodelan Perangkat Lunak', 'Linda Marwati', '978-602-399-267-6', NULL, 'Mediatama', '2013', NULL, 'XI', 'KTSP', 3, 3, NULL, 'PEM-XI-098', '2025-11-17 15:13:10'),
(431, 'Pemrograman Web', 'Annas.N.A,DKK', '978-623-780-899-8', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 6, 6, NULL, 'PEM-XI-099', '2025-11-17 15:13:10'),
(432, 'Pemrograman Beovientasi Objek', 'Teguh Promono', '978-602-486-538-2', NULL, 'Erlangga', '2019', NULL, 'XII', 'Merdeka', 12, 12, NULL, 'PEM-XII-100', '2025-11-17 15:13:10'),
(433, 'Pemrograman web dan perangkat Bergerak', 'Linda Marwati', '978-602-399-293-5', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 7, 7, NULL, 'PEM-XII-101', '2025-11-17 15:13:10'),
(434, 'Pemrograman Beovientasi Objek', 'Linda Marwati', '978-602-399-291-1', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 3, 3, NULL, 'PEM-XII-102', '2025-11-17 15:13:10'),
(435, 'Basis Data', 'Noviana', '978-602-399-264-5', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 5, 5, NULL, 'BAS-XII-103', '2025-11-17 15:13:10'),
(436, 'Basis Data', 'Hendry Pandia', '978-623-522-028-4', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 13, 13, NULL, 'BAS-XII-104', '2025-11-17 15:13:10'),
(437, 'Produk Kreatif dan Kewirausahaan', 'Andi Novianto', '978-623-266-423-4', NULL, 'Erlangga', '2021', NULL, 'XII', 'Merdeka', 21, 21, NULL, 'PRO-XII-105', '2025-11-17 15:13:10'),
(438, 'Prakarya dan Kewirausahaan Semester 1', 'Hendriana Wardhaningsih Albeta,DKK', '978-602-427-153-1', NULL, 'Kemendikbud', '2017', NULL, 'X', 'KTSP', 1, 1, NULL, 'PRA-X-106', '2025-11-17 15:13:10'),
(439, 'Prakarya dan Kewirausahaan Semester 2', 'Hendriana Wardhaningsih Albeta,DKK', '978-602-427-155-8', NULL, 'Kemendikbud', '2017', NULL, 'X', 'KTSP', 1, 1, NULL, 'PRA-X-107', '2025-11-17 15:13:10'),
(440, 'Komunikasi Bisnis', 'Anis Muftias', '978-602-444-288-0', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 5, 5, NULL, 'KOM-X-108', '2025-11-17 15:13:10'),
(441, 'Ekonomi Bisnis', 'Ulfra Rahmah Ayu Ningsih', '978-602-399-192-7', NULL, 'Mediatama', '2018', NULL, 'X', 'K-13', 2, 2, NULL, 'EKO-X-109', '2025-11-17 15:13:10'),
(442, 'Administrasi Umum', 'Muhammad Lutfi Hakim', '978-602-399-247-8', NULL, 'Mediatama', '2017', NULL, 'X', 'K-13', 1, 1, NULL, 'ADM-X-110', '2025-11-17 15:13:10'),
(443, 'Pengelolaan Bisnis Retail', 'Fuji Nurhayati ,DKK', '978-623-180-650-5', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 7, 7, NULL, 'PEN-XI-111', '2025-11-17 15:13:10'),
(444, 'Digital Marketing', 'Devi Puspita Sari', '978-623-180-556-0', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 6, 6, NULL, 'DIG-XI-112', '2025-11-17 15:13:10'),
(445, 'Digital Branding', 'Devi Pusipta Sari', '978-623-180-026-8', NULL, 'Erlangga', '2023', NULL, 'XI', 'Merdeka', 6, 6, NULL, 'DIG-XI-113', '2025-11-17 15:13:10'),
(446, 'Marketing', 'Widaningsih', '978-623-180-414-3', NULL, 'Erlangga', '2024', NULL, 'XI', 'Merdeka', 7, 7, NULL, 'MAR-XI-114', '2025-11-17 15:13:10'),
(447, 'Administrasi Transaksi', 'Hendi Susanto ,DKK', '978-979-778-322-8', NULL, 'HUP', '2018', NULL, 'XI', 'K-13', 1, 1, NULL, 'ADM-XI-115', '2025-11-17 15:13:10'),
(448, 'Produk Kreatif dan Kewirausahaan', 'Wulan Ayodya', '978-623-266-249-0', NULL, 'Erlangga', '2020', NULL, 'XII', 'K-13', 21, 21, NULL, 'PRO-XII-118', '2025-11-17 15:13:10'),
(449, 'Pengelolaan Bisnis Retail', 'Hendi Susanto ,DKK', '978-979-778-359-4', NULL, 'HUP', '2019', NULL, 'XII', 'K-13', 1, 1, NULL, 'PEN-XII-119', '2025-11-17 15:13:10'),
(450, 'Bisnis Online', 'Linda Marwati , DKK', '978-602-399-195-2', NULL, 'Mediatama', '2018', NULL, 'XII', 'K-13', 1, 1, NULL, 'BIS-XII-120', '2025-11-17 15:13:10'),
(451, 'Bisnis Digital', 'Deva Putri Anggelma,DKK', '978-602-488-821-3', NULL, 'Mediatama', '2025', NULL, 'XII', 'Merdeka', 17, 17, NULL, 'BIS-XII-121', '2025-11-17 15:13:10'),
(452, 'Bisnis Branding', 'Devi Puspita Sari,DKK', '978-623-180-808-0', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 7, 7, NULL, 'BIS-XII-122', '2025-11-17 15:13:10'),
(453, 'Pengelolaan Bisnis Retail', 'Puji Nuryati,DKK', '978-623-522-032-1', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 7, 7, NULL, 'PEN-XII-123', '2025-11-17 15:13:10'),
(454, 'Digital Marketing', 'Devi Puspitasari', '978-623-180-949-0', NULL, 'Erlangga', '2024', NULL, 'XII', 'Merdeka', 7, 7, NULL, 'DIG-XII-124', '2025-11-17 15:13:10'),
(455, 'Penataan Produk', 'Fuji Nurhayati ,DKK', '978-602-486-194-0', NULL, 'Erlangga', '2019', NULL, 'XII', 'K-13', 12, 12, NULL, 'PEN-XII-125', '2025-11-17 15:13:10'),
(456, 'Administrasi Transaksi', 'Avni Laksmi Dara,DKK', '978-602-399-229-4', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 5, 5, NULL, 'ADM-XII-126', '2025-11-17 15:13:10'),
(457, 'Pengelolaan Bisnis Retail', 'Nurul Bekti Primudhirta', '978-602-399-217-7', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 1, 1, NULL, 'PEN-XII-127', '2025-11-17 15:13:10'),
(458, 'Bisnis Online', 'Linda Marwati,DKK', '978-602-399-196-9', NULL, 'Mediatama', '2019', NULL, 'XII', 'K-13', 1, 1, NULL, 'BIS-XII-128', '2025-11-17 15:13:10'),
(459, 'Rekayasa Perangkat Lunak Jilid 3', 'Aunur R.mulyanto', '978-979-060-007-2', NULL, 'BSE', '2018', NULL, 'UMUM', 'K-13', 53, 53, NULL, 'REK-UMUM-129', '2025-11-17 15:13:10'),
(460, 'Seni Budaya Jilid 2', 'Sri Hermawati Dwi Arini ,DKK', '978-979-060-011-9', NULL, 'BSE', '2010', NULL, 'UMUM', 'K-13', 187, 187, NULL, 'SEN-UMUM-130', '2025-11-17 15:13:10'),
(461, 'Modulku Bahasa Inggris', 'M.Haman Abdurrohman', '978-602-399-738-1', NULL, 'Mediatama', '2017', NULL, 'UMUM', 'K-13', 10, 10, NULL, 'MOD-UMUM-131', '2025-11-17 15:13:10'),
(462, 'Bahasa Inggris', 'Erinaa Setiajiana,DKK', '978-602-488-033-0', NULL, 'Mediatama', '2017', NULL, 'X', 'K-13', 2, 2, NULL, 'BAH-X-132', '2025-11-17 15:13:10'),
(463, 'Ekonomi Bisnis', 'Maksum H.M Gunadi', '978-602-445-309-1', NULL, 'yudhitira', '2017', NULL, 'X', 'K-13', 7, 7, NULL, 'EKO-X-133', '2025-11-17 15:13:10'),
(464, 'Pengantar Administrasi Perkantoran', 'Sri Endang Rahayu,DKK', '978-602-298-162-6', NULL, 'Erlangga', NULL, NULL, 'X', 'K-13', 20, 20, NULL, 'PEN-X-134', '2025-11-17 15:13:10'),
(465, 'Sejarah Indonesia', 'Sardiman Am,DKK', '978-602-427-122-0', NULL, 'Kemendikbud', '2017', NULL, 'XI', 'K-13', 71, 71, NULL, 'SEJ-XI-135', '2025-11-17 15:13:10'),
(466, 'SPM', 'Reny Ratnawati', '978-602-241-334-9', NULL, 'Erlangga', '2013', NULL, 'UMUM', 'K-13', 6, 6, NULL, 'SPM-UMUM-136', '2025-11-17 15:13:10'),
(467, 'Bahasa Inggris', 'Prof.Dr.Zulati Rohmah', '978-602-427-106-0', NULL, 'Kemendikbud', '2013', NULL, 'X', 'K-13', 6, 6, NULL, 'BAH-X-137', '2025-11-17 15:13:10'),
(468, 'Pendidikan Pancasila & kewarganegaraan', 'Yusnawan Lubis', '978-602-427-090-2', NULL, 'CV Arya Duta', '2017', NULL, 'XI', 'K-13', 120, 120, NULL, 'PEN-XI-138', '2025-11-17 15:13:10'),
(469, 'Kimia', 'Eko Suryanto', '978-602-399-179-2', NULL, 'Mediatama', '2018', NULL, 'X', 'K-13', 2, 2, NULL, 'KIM-X-139', '2025-11-17 15:13:10'),
(470, 'IPA', 'Dewi Safitri', '978-602-444-366-5', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 2, 2, NULL, 'IPA-X-140', '2025-11-17 15:13:10'),
(471, 'Fisika', 'yuni supriyanti', '978-602-444-276-7', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 3, 3, NULL, 'FIS-X-141', '2025-11-17 15:13:10'),
(472, 'Sejarah Indonesia', 'Adi Gunanto', '978-602-444-318-4', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 3, 3, NULL, 'SEJ-X-144', '2025-11-17 15:13:10'),
(473, 'Pengantar Ekonomi & Bisnis', 'Alam.S', '978-602-298-048-3', NULL, 'Erlangga', NULL, NULL, 'X', 'UMUM', 61, 61, NULL, 'PEN-X-145', '2025-11-17 15:13:10'),
(474, 'Ekonomi', 'Sri NurcMulyani', '978-979-068-197-7', NULL, 'BSE', '2010', NULL, 'X', 'K-13', 7, 7, NULL, 'EKO-X-146', '2025-11-17 15:13:10'),
(475, 'Ekonomi', 'Rudianto', '978-979-750-141-9', NULL, '2006', '2015', NULL, 'X', 'K-13', 1, 1, NULL, 'EKO-X-147', '2025-11-17 15:13:10'),
(476, 'Fisika', 'Naila Himiyana syifa', '978-602-399-185-3', NULL, 'Mediatama', '2020', NULL, 'X', 'K-13', 1, 1, NULL, 'FIS-X-148', '2025-11-17 15:13:10'),
(477, 'Ilmu Pengetahuan Alam', 'Alam.S', '978-979-015-778-1', NULL, 'Erlangga', '2008', NULL, 'UMUM', 'KTSP', 2, 2, NULL, 'ILM-UMUM-149', '2025-11-17 15:13:10'),
(478, 'Sanitasi Higiene dan Keselamatan Kerja', 'Asep Parantika', '978-602-434-970-7', NULL, 'Erlangga', '2018', NULL, 'UMUM', 'K-13', 1, 1, NULL, 'SAN-UMUM-150', '2025-11-17 15:13:10'),
(479, 'English', 'Priyono Darmanto', '978-602-444-361-0', NULL, 'Bumi Aksara', '2017', NULL, 'XI', 'K-13', 1, 1, NULL, 'ENG-XI-151', '2025-11-17 15:13:10'),
(480, 'Kimia', 'Felmi Febrianti', '978-602-444362-2', NULL, 'Bumi Aksara', '2017', NULL, 'X', 'K-13', 1, 1, NULL, 'KIM-X-152', '2025-11-17 15:13:10'),
(481, 'Modulku Bahasa Indonesia', 'Sekar Galuh Endah P.L,Sarwa indah Ika.W', '978-602-399-100-1', NULL, 'Mediatama', '2017', NULL, 'XII', 'K-13', 9, 9, NULL, 'MOD-XII-153', '2025-11-17 15:13:10'),
(482, 'Bahasa Indonesia', 'Ahmad Iskak', '978-970-015-753-8', NULL, 'Erlangga', '2016', NULL, 'X', 'K-13', 13, 13, NULL, 'BAH-X-154', '2025-11-17 15:13:10'),
(483, 'AKM  Nasional', 'Indah Wuki,DKK', '978-623-257-173-0', NULL, 'yudhitira', '2021', NULL, 'UMUM', 'Merdeka', 11, 11, NULL, 'AKM-UMUM-155', '2025-11-17 15:13:10'),
(484, 'Modulku  Matematika', 'Suparmin.DKK', '978-602-399-818-0', NULL, 'Mediatama', '2017', NULL, 'XII', 'K-13', 10, 10, NULL, 'MOD-XII-156', '2025-11-17 15:13:10'),
(485, 'Asesmen Bahasa  Indonesia', '', '978-623-257-180-8', NULL, 'yudhitira', '2021', NULL, 'XI', 'K-13', 12, 12, NULL, 'ASE-XI-157', '2025-11-17 15:13:10'),
(486, 'Beroganisasi', 'Rasyib Zaelani', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'BER-UMUM-158', '2025-11-17 15:13:10'),
(487, 'Mengenal Sablon', 'Rasyib Zaelani', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'MEN-UMUM-159', '2025-11-17 15:13:10'),
(488, 'Pola Hidup Rassulullah', 'helmi Hardiansyah,DKK', NULL, NULL, 'pt warna mukti grafika', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'POL-UMUM-160', '2025-11-17 15:13:10'),
(489, 'Keterampilan Fungsional Kerumahtanggaan', 'abdul hamid', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'KET-UMUM-161', '2025-11-17 15:13:10'),
(490, 'Mengelola Sampah Rumah Tangga', 'M.Sudama', NULL, NULL, 'Djatnika', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'MEN-UMUM-162', '2025-11-17 15:13:10'),
(491, 'Bermatapencaharian', 'abdul hamid', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'BER-UMUM-163', '2025-11-17 15:13:10'),
(492, 'Pramuka Menumbuhkan Semangat', 'Fajar Nur\'aini', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'PRA-UMUM-164', '2025-11-17 15:13:10'),
(494, 'Jurnalistik Untuk Guru', 'Drs.Gunawan ,M.pd,DKK', '979-979-750-490-7', NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'JUR-UMUM-166', '2025-11-17 15:13:10'),
(495, 'Seni Merangkai Janur', 'Annayanti Budiningsih', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'SEN-UMUM-167', '2025-11-17 15:13:10'),
(496, 'Wawasan Kepramukaaan', 'Sarkonah ,DKK', NULL, NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'WAW-UMUM-168', '2025-11-17 15:13:10'),
(497, 'Kedasyatan Berpikir Positif', 'A.n Ubaediy', NULL, NULL, 'vision03', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'KED-UMUM-169', '2025-11-17 15:13:10'),
(498, 'Ubah Sikap Raih Kesuksesan', 'A.n Ubaediy', NULL, NULL, 'vision03', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'UBA-UMUM-170', '2025-11-17 15:13:10'),
(499, 'Khutbah Idul Fitri', 'M.Masyud ALI', '978-979-093-548-0', NULL, 'Cv bina Pustkaka', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'KHU-UMUM-171', '2025-11-17 15:13:10'),
(500, 'Keajaiban Sedekah', 'Imam Bukhori', NULL, NULL, 'Vision03', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'KEA-UMUM-172', '2025-11-17 15:13:10'),
(501, 'Etika Bermedia Sosial', 'Maya Rohmayati', '978-979-094-401-5', NULL, 'CV Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'ETI-UMUM-173', '2025-11-17 15:13:10'),
(502, 'Manusia Cerminan Sifat Ilahi', 'M.Lutfi Ubaidillah', '978-623-532-909-3', NULL, 'pt warna mukti grafika', NULL, NULL, 'UMUM', 'UMUM', 10, 10, NULL, 'MAN-UMUM-174', '2025-11-17 15:13:10'),
(507, 'Aku cinta Pramuka Panffuan Penegak Bantara', 'Sarkonah ,DKK', '978-979-094-778-8', NULL, 'Arya Duta', NULL, NULL, 'UMUM', 'UMUM', 5, 5, NULL, 'AKU-UMUM-165-NEW', '2025-11-19 06:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_books`
--

CREATE TABLE `homepage_books` (
  `id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `kode_member` varchar(50) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('siswa','guru') NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nuptk` varchar(20) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `registrasi` date DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tenggat_waktu` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') DEFAULT 'borrowed',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'admin',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `role`, `last_login`, `created_at`) VALUES
(1, 'mikael', '$2y$10$UMhWDZtOBezvFsZ9jxGb9OIdVTBg1XuTUvVf4ir7qoIyKwN2zejY2', 'Mikael Immanuel Christianto', 'admin', '2025-11-19 14:58:42', '2025-11-13 02:43:03'),
(2, 'rendi', '$2y$10$ErhdLHqneyFYQbkCgCGiKOakmps7gTy5ixNjFJ8F4dFYgk7tJOFPC', 'rendi', 'admin', NULL, '2025-11-19 07:46:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD UNIQUE KEY `issn` (`issn`),
  ADD KEY `fk_buku_kategori` (`kategori_id`);

--
-- Indexes for table `homepage_books`
--
ALTER TABLE `homepage_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_member` (`kode_member`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `nuptk` (`nuptk`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `buku_id` (`buku_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;

--
-- AUTO_INCREMENT for table `homepage_books`
--
ALTER TABLE `homepage_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `homepage_books`
--
ALTER TABLE `homepage_books`
  ADD CONSTRAINT `homepage_books_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`),
  ADD CONSTRAINT `peminjaman_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
