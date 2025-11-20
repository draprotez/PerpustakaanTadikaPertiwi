<?php
//memberViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/memberModels.php';
include '../header.php';

$search = $_GET['search'] ?? ''; 
$limit = 10; 
$totalResults = countAllMembers($conn, $search);
$totalPages = ceil($totalResults / $limit);
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
} elseif ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}
$offset = ($page - 1) * $limit;
$member_list = getAllMembers($conn, $search, $limit, $offset);
$searchParam = $search ? '&search=' . htmlspecialchars($search) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Anggota</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background-color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; width: 400px; max-height: 80vh; overflow-y: auto; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 3px; }
        .button-group { margin-top: 20px; text-align: right; }
        button { padding: 8px 15px; cursor: pointer; border: none; border-radius: 3px; margin-left: 5px; }
        button[type="submit"] { background-color: #4CAF50; color: white; }
        button[type="button"] { background-color: #f44336; color: white; }
        .btn-tambah { background-color: #008CBA; color: white; padding: 10px 15px; margin-bottom: 15px; }
        .btn-logout { background-color: red; color: white; padding: 8px 15px; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; }
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; }
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; }
        .siswa-fields, .guru-fields { display: none; }
    </style>
</head>
<body class="ml-[320px]">

    <?php include 'partials/sidebar.php'; ?>

    <p class="font-semibold text-xl py-5">Kelola Data Anggota (Siswa & Guru)</p>

    <button class="btn-tambah" onclick="openForm('createForm')">Tambah Anggota Baru</button>

    <hr>
    <form action="memberViews.php" method="GET">
        <label for="search">Cari (Nama, Kode, Email, NISN, NUPTK):</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
        <a href="memberViews.php">Hapus Filter</a>
    </form>
    <hr>
    
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><b><?php echo htmlspecialchars($_GET['success']); ?></b></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><b><?php echo htmlspecialchars($_GET['error']); ?></b></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Member</th>
                <th>Nama</th>
                <th>Username (Email)</th>
                <th>Tipe</th>
                <th>Nomor Induk</th> <th>Kelas/Mapel</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($member_list)): ?>
                <tr>
                    <td colspan="9" align="center">
                        <?php if ($search): ?>
                            Data anggota "<?php echo htmlspecialchars($search); ?>" tidak ditemukan.
                        <?php else: ?>
                            Belum ada data anggota.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($member_list as $member): ?>
                    <tr>
                        <td><?php echo $member['id']; ?></td>
                        <td><?php echo htmlspecialchars($member['kode_member']); ?></td>
                        <td><?php echo htmlspecialchars($member['name']); ?></td>
                        <td><?php echo htmlspecialchars($member['username']); ?></td>
                        <td>
                            <span style="padding: 3px 8px; border-radius: 4px; color: white; background-color: <?php echo ($member['type'] == 'siswa') ? '#28a745' : '#007bff'; ?>">
                                <?php echo ucfirst($member['type']); ?>
                            </span>
                        </td>
                        
                        <td>
                            <?php 
                            if ($member['type'] == 'siswa') {
                                // Tampilkan NISN / NIS
                                $nisn = $member['nisn'] ? $member['nisn'] : '-';
                                $nis = $member['nis'] ? $member['nis'] : '-';
                                echo "NISN: " . htmlspecialchars($nisn) . "<br>";
                                echo "NIS: " . htmlspecialchars($nis);
                            } else {
                                // Tampilkan NUPTK / NIP
                                $nuptk = $member['nuptk'] ? $member['nuptk'] : '-';
                                $nip = $member['nip'] ? $member['nip'] : '-';
                                echo "NUPTK: " . htmlspecialchars($nuptk) . "<br>";
                                echo "NIP: " . htmlspecialchars($nip);
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($member['type'] == 'siswa') {
                                echo htmlspecialchars($member['kelas']);
                            } else {
                                echo htmlspecialchars($member['keterangan']); 
                            }
                            ?>
                        </td>
                        <td>
                            <span style="color: <?php echo ($member['status'] == 'active') ? 'green' : 'red'; ?>; font-weight: bold;">
                                <?php echo ucfirst($member['status']); ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" 
                                    onclick="openEditMemberForm(this)"
                                    data-id="<?php echo $member['id']; ?>">
                                Edit
                            </button>
                            <button type="button" 
                                    onclick="openDeleteConfirm(this)"
                                    data-url="../controller/memberController.php?action=delete&id=<?php echo $member['id']; ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $searchParam; ?>"
               class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                &laquo; Previous
            </a>
            <span class="current">
                Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?>
            </span>
            <a href="?page=<?php echo $page + 1; ?><?php echo $searchParam; ?>"
               class="<?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                Next &raquo;
            </a>
        <?php endif; ?>
    </div>
    <br>
    
    <div id="createForm" class="modal">
        <div class="modal-content">
            <h2>Form Tambah Anggota Baru</h2>
            <form action="../controller/memberController.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Username (Email):</label>
                    <input type="email" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password Baru:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Tipe Anggota:</label>
                    <select name="type" onchange="toggleFields('create', this.value)" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>
                
                <div class="siswa-fields" id="create-siswa-fields">
                    <div class="form-group">
                        <label>NISN:</label>
                        <input type="text" name="nisn">
                    </div>
                    <div class="form-group">
                        <label>NIS:</label>
                        <input type="text" name="nis">
                    </div>
                    <div class="form-group">
                        <label>Kelas (Siswa):</label>
                        <input type="text" name="kelas">
                    </div>
                </div>

                <div class="guru-fields" id="create-guru-fields">
                    <div class="form-group">
                        <label>NUPTK:</label>
                        <input type="text" name="nuptk">
                    </div>
                    <div class="form-group">
                        <label>NIP:</label>
                        <input type="text" name="nip">
                    </div>
                    <div class="form-group">
                        <label>Mata Pelajaran (Keterangan):</label>
                        <input type="text" name="keterangan">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Akun:</label>
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="button-group">
                    <button type="submit">Simpan</button> 
                    <button type="button" onclick="closeForm('createForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="editForm" class="modal">
        <div class="modal-content">
            <h2>Form Edit Anggota</h2>
            <form action="../controller/memberController.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="action" value="update">
                
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label>Username (Email):</label>
                    <input type="email" id="edit_username" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password Baru:</label>
                    <input type="password" name="password" placeholder="(Kosongkan jika tidak diubah)">
                </div>
                <div class="form-group">
                    <label>Tipe Anggota:</label>
                    <select name="type" id="edit_type" onchange="toggleFields('edit', this.value)" required>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>
                
                <div class="siswa-fields" id="edit-siswa-fields">
                    <div class="form-group">
                        <label>NISN:</label>
                        <input type="text" id="edit_nisn" name="nisn">
                    </div>
                    <div class="form-group">
                        <label>NIS:</label>
                        <input type="text" id="edit_nis" name="nis">
                    </div>
                    <div class="form-group">
                        <label>Kelas (Siswa):</label>
                        <input type="text" id="edit_kelas_siswa" name="kelas">
                    </div>
                </div>

                <div class="guru-fields" id="edit-guru-fields">
                    <div class="form-group">
                        <label>NUPTK:</label>
                        <input type="text" id="edit_nuptk" name="nuptk">
                    </div>
                    <div class="form-group">
                        <label>NIP:</label>
                        <input type="text" id="edit_nip" name="nip">
                    </div>
                    <div class="form-group">
                        <label>Mata Pelajaran (Keterangan):</label>
                        <input type="text" id="edit_keterangan" name="keterangan">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Akun:</label>
                    <select name="status" id="edit_status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="button-group">
                    <button type="submit">Update</button> 
                    <button type="button" onclick="closeForm('editForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <p>Yakin ingin hapus anggota ini?</p>
            <div class="button-group">
                <button type="button" onclick="confirmDelete()">Ya, Hapus</button>
                <button type="button" onclick="closeForm('deleteConfirmModal')">Batal</button>
            </div>
            <input type="hidden" id="deleteUrlInput">
        </div>
    </div>

    <script>
        function openForm(modalId) {
            document.getElementById(modalId).style.display = 'block'; 
        }

        function closeForm(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function toggleFields(formType, value) {
            const siswaFields = document.getElementById(formType + '-siswa-fields');
            const guruFields = document.getElementById(formType + '-guru-fields');
            
            if (value === 'siswa') {
                siswaFields.style.display = 'block';
                guruFields.style.display = 'none';
            } else if (value === 'guru') {
                siswaFields.style.display = 'none';
                guruFields.style.display = 'block';
            } else {
                siswaFields.style.display = 'none';
                guruFields.style.display = 'none';
            }
        }

        function openEditMemberForm(buttonElement) {
            const id = buttonElement.getAttribute('data-id');
            
            fetch(`getMemberDetails.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_username').value = data.username;
                        document.getElementById('edit_type').value = data.type;
                        document.getElementById('edit_status').value = data.status;
                        
                        document.getElementById('edit_nisn').value = data.nisn || '';
                        document.getElementById('edit_nis').value = data.nis || '';
                        
                        document.getElementById('edit_nuptk').value = data.nuptk || '';
                        document.getElementById('edit_nip').value = data.nip || '';
                        
                        if (data.type === 'siswa') {
                            document.getElementById('edit_kelas_siswa').value = data.kelas || '';
                            document.getElementById('edit_keterangan').value = '';
                        } else {
                            document.getElementById('edit_kelas_siswa').value = '';
                            document.getElementById('edit_keterangan').value = data.keterangan || '';
                        }

                        toggleFields('edit', data.type);
                        
                        openForm('editForm');
                    }
                })
                .catch(error => {
                    alert('Gagal mengambil data anggota: ' + error);
                });
        }

        function openDeleteConfirm(buttonElement) {
            const deleteUrl = buttonElement.getAttribute('data-url');
            document.getElementById('deleteUrlInput').value = deleteUrl;
            openForm('deleteConfirmModal');
        }

        function confirmDelete() {
            const deleteUrl = document.getElementById('deleteUrlInput').value;
            if (deleteUrl) {
                window.location.href = deleteUrl;
            } else {
                alert('Error: URL Hapus tidak ditemukan!');
            }
        }

        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>