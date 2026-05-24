<?php 
include 'koneksi.php'; 
if(isset($_POST['simpan'])) {
    $nama = $_POST['nama_obat']; $stok = $_POST['stok']; $harga = $_POST['harga'];
    mysqli_query($conn, "INSERT INTO obat (nama_obat, stok, harga) VALUES ('$nama', '$stok', '$harga')");
    header("Location: obat.php");
}
if(isset($_POST['edit'])) {
    $id = $_POST['id']; $nama = $_POST['nama_obat']; $stok = $_POST['stok']; $harga = $_POST['harga'];
    mysqli_query($conn, "UPDATE obat SET nama_obat='$nama', stok='$stok', harga='$harga' WHERE id='$id'");
    header("Location: obat.php");
}
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus']; mysqli_query($conn, "DELETE FROM obat WHERE id = '$id'"); header("Location: obat.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Obat - Puskesmas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%); color: white; padding-top: 30px; position: fixed; box-shadow: 4px 0 15px rgba(0,0,0,0.1); width: 16.666667%;}
        .sidebar h4 { font-weight: 700; margin-bottom: 30px; }
        .sidebar a { color: #d1d8e0; text-decoration: none; display: block; padding: 12px 20px; margin: 8px 15px; border-radius: 10px; transition: all 0.3s ease; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.15); color: #ffffff; transform: translateX(5px); }
        .main-content { margin-left: 16.666667%; padding: 40px; width: 83.333333%;}
        .card { border: none; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.04); }
        .table-custom th { background-color: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="d-flex">
        <div class="sidebar">
            <h4 class="text-center"><i class="fas fa-hospital-user fa-lg mb-2"></i><br>Puskesmas</h4>
            <a href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="pasien.php"><i class="fas fa-users me-2"></i> Data Pasien</a>
            <a href="jadwal.php"><i class="fas fa-calendar-check me-2"></i> Jadwal Dokter</a>
            <a href="appointment.php"><i class="fas fa-calendar-plus me-2"></i> Janji Temu</a>
            <a href="rekam_medis.php"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php" class="active"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>

        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Inventory Obat</h2>
                <button class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-2"></i> Tambah Obat
                </button>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelObat" class="table table-hover table-custom w-100">
                        <thead><tr><th>Nama Obat</th><th>Stok</th><th>Harga (Rp)</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php $q = mysqli_query($conn, "SELECT * FROM obat ORDER BY id DESC"); while($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= $row['nama_obat'] ?></td>
                                <td><span class="badge rounded-pill px-3 <?= $row['stok'] < 20 ? 'bg-danger' : 'bg-success' ?>"><?= $row['stok'] ?> Kapsul/Strip</span></td>
                                <td class="fw-medium">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus obat ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog"><div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-warning border-0"><h5 class="modal-title fw-bold">Edit Obat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form method="POST"><div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <label class="form-label small text-muted">Nama Obat</label><input type="text" name="nama_obat" class="form-control mb-3" value="<?= $row['nama_obat'] ?>" required>
                                        <label class="form-label small text-muted">Stok</label><input type="number" name="stok" class="form-control mb-3" value="<?= $row['stok'] ?>" required>
                                        <label class="form-label small text-muted">Harga Jual</label><input type="number" name="harga" class="form-control mb-3" value="<?= $row['harga'] ?>" required>
                                    </div><div class="modal-footer border-0"><button type="submit" name="edit" class="btn btn-warning rounded-pill px-4 fw-bold">Update</button></div></form>
                                </div></div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content border-0 shadow">
        <div class="modal-header bg-success text-white border-0"><h5 class="modal-title fw-bold">Tambah Obat Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <form method="POST"><div class="modal-body">
            <input type="text" name="nama_obat" class="form-control mb-3" required placeholder="Nama Obat">
            <input type="number" name="stok" class="form-control mb-3" required placeholder="Stok Awal">
            <input type="number" name="harga" class="form-control mb-3" required placeholder="Harga (Rp)">
        </div><div class="modal-footer border-0"><button type="submit" name="simpan" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Simpan</button></div></form>
    </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script> $(document).ready(function() { $('#tabelObat').DataTable({ language: { search: "Cari Obat:", lengthMenu: "Tampil _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ obat", paginate: { previous: "Prev", next: "Next" } } }); }); </script>
</body>
</html>