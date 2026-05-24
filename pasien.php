<?php include 'koneksi.php'; 
// Proses CRUD tetap sama persis seperti sebelumnya
if(isset($_POST['simpan'])) {
    $no_rm = $_POST['no_rm']; $nama = $_POST['nama_pasien'];
    $tgl = $_POST['tgl_lahir']; $telp = $_POST['telp']; $alamat = $_POST['alamat'];
    mysqli_query($conn, "INSERT INTO pasien (no_rm, nama_pasien, tgl_lahir, telp, alamat) VALUES ('$no_rm', '$nama', '$tgl', '$telp', '$alamat')");
    header("Location: pasien.php");
}
if(isset($_POST['edit'])) {
    $id = $_POST['id']; $nama = $_POST['nama_pasien']; $tgl = $_POST['tgl_lahir'];
    $telp = $_POST['telp']; $alamat = $_POST['alamat'];
    mysqli_query($conn, "UPDATE pasien SET nama_pasien='$nama', tgl_lahir='$tgl', telp='$telp', alamat='$alamat' WHERE id='$id'");
    header("Location: pasien.php");
}
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pasien WHERE id = '$id'");
    header("Location: pasien.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%); color: white; padding-top: 30px; position: fixed; box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
        .sidebar h4 { font-weight: 700; margin-bottom: 30px; }
        .sidebar a { color: #d1d8e0; text-decoration: none; display: block; padding: 12px 20px; margin: 8px 15px; border-radius: 10px; transition: all 0.3s ease; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.15); color: #ffffff; transform: translateX(5px); }
        .main-content { margin-left: 16.666667%; padding: 40px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.04); }
        /* Style tambahan untuk Datatables agar cantik */
        .table-custom th { background-color: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="text-center"><i class="fas fa-hospital-user fa-lg mb-2"></i><br>Puskesmas</h4>
            <a href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="pasien.php" class="active"><i class="fas fa-users me-2"></i> Data Pasien</a>
            <a href="jadwal.php"><i class="fas fa-calendar-check me-2"></i> Jadwal Dokter</a>
            <a href="appointment.php"><i class="fas fa-calendar-plus me-2"></i> Janji Temu</a>
            <a href="rekam_medis.php"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Manajemen Data Pasien</h2>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="fas fa-plus me-2"></i> Tambah Pasien Baru
                </button>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelPasien" class="table table-hover table-custom w-100">
                        <thead><tr><th>No RM</th><th>Nama Pasien</th><th>Tgl Lahir</th><th>No Telp</th><th>Alamat</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM pasien ORDER BY id DESC");
                            while($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border"><?= $row['no_rm'] ?></span></td>
                                <td class="fw-bold"><?= $row['nama_pasien'] ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_lahir'])) ?></td>
                                <td><?= $row['telp'] ?></td>
                                <td><?= $row['alamat'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus pasien ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                              <div class="modal-dialog"><div class="modal-content border-0 shadow">
                                  <div class="modal-header bg-warning text-dark border-0"><h5 class="modal-title fw-bold">Edit Pasien</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                  <form method="POST"><div class="modal-body">
                                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                      <div class="mb-2"><label class="form-label text-muted small">No RM</label><input type="text" class="form-control" value="<?= $row['no_rm'] ?>" readonly></div>
                                      <div class="mb-2"><label class="form-label text-muted small">Nama Pasien</label><input type="text" name="nama_pasien" class="form-control" value="<?= $row['nama_pasien'] ?>" required></div>
                                      <div class="mb-2"><label class="form-label text-muted small">Tgl Lahir</label><input type="date" name="tgl_lahir" class="form-control" value="<?= $row['tgl_lahir'] ?>" required></div>
                                      <div class="mb-2"><label class="form-label text-muted small">No Telp</label><input type="text" name="telp" class="form-control" value="<?= $row['telp'] ?>"></div>
                                      <div class="mb-2"><label class="form-label text-muted small">Alamat</label><textarea name="alamat" class="form-control"><?= $row['alamat'] ?></textarea></div>
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

<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white border-0"><h5 class="modal-title fw-bold">Tambah Pasien</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST"><div class="modal-body">
          <input type="text" name="no_rm" class="form-control mb-3" required placeholder="No RM (ex: RM003)">
          <input type="text" name="nama_pasien" class="form-control mb-3" required placeholder="Nama Lengkap">
          <input type="date" name="tgl_lahir" class="form-control mb-3" required>
          <input type="text" name="telp" class="form-control mb-3" placeholder="No Telepon">
          <textarea name="alamat" class="form-control mb-3" placeholder="Alamat"></textarea>
      </div><div class="modal-footer border-0"><button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan</button></div></form>
  </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelPasien').DataTable({
            language: {
                search: "Cari Pasien:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pasien",
                paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
            }
        });
    });
</script>
</body>
</html>