<?php 
include 'koneksi.php'; 
if(isset($_POST['simpan'])) {
    $id_dokter = $_POST['id_dokter']; $hari = $_POST['hari']; $jam_mulai = $_POST['jam_mulai']; $jam_selesai = $_POST['jam_selesai'];
    mysqli_query($conn, "INSERT INTO jadwal_dokter (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')");
    header("Location: jadwal.php");
}
if(isset($_POST['edit'])) {
    $id = $_POST['id']; $hari = $_POST['hari']; $jam_mulai = $_POST['jam_mulai']; $jam_selesai = $_POST['jam_selesai'];
    mysqli_query($conn, "UPDATE jadwal_dokter SET hari='$hari', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai' WHERE id='$id'");
    header("Location: jadwal.php");
}
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus']; mysqli_query($conn, "DELETE FROM jadwal_dokter WHERE id = '$id'"); header("Location: jadwal.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Jadwal Dokter - Puskesmas</title>
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
            <a href="jadwal.php" class="active"><i class="fas fa-calendar-check me-2"></i> Jadwal Dokter</a>
            <a href="appointment.php"><i class="fas fa-calendar-plus me-2"></i> Janji Temu</a>
            <a href="rekam_medis.php"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Jadwal Praktek Dokter</h2>
                <button class="btn btn-info text-white rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="fas fa-plus me-2"></i> Tambah Jadwal</button>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelJadwal" class="table table-hover table-custom w-100">
                        <thead><tr><th>Nama Dokter</th><th>Spesialisasi</th><th>Hari Praktek</th><th>Jam</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT j.id, j.hari, j.jam_mulai, j.jam_selesai, d.nama_dokter, s.nama_spesialisasi 
                                    FROM jadwal_dokter j JOIN dokter d ON j.id_dokter = d.id JOIN spesialisasi s ON d.id_spesialisasi = s.id 
                                    ORDER BY FIELD(LOWER(j.hari), 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'), j.jam_mulai ASC";
                            $q = mysqli_query($conn, $sql); 
                            while($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= $row['nama_dokter'] ?></td>
                                <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill"><?= $row['nama_spesialisasi'] ?></span></td>
                                <td class="fw-bold text-primary"><?= ucfirst(strtolower($row['hari'])) ?></td>
                                <td><i class="far fa-clock text-muted"></i> <?= date('H:i', strtotime($row['jam_mulai'])) ?> - <?= date('H:i', strtotime($row['jam_selesai'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus jadwal ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                              <div class="modal-dialog"><div class="modal-content border-0 shadow">
                                  <div class="modal-header bg-warning border-0"><h5 class="modal-title fw-bold">Edit Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                  <form method="POST"><div class="modal-body">
                                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                      <label class="form-label small text-muted">Hari</label><input type="text" name="hari" class="form-control mb-3" value="<?= $row['hari'] ?>" required>
                                      <label class="form-label small text-muted">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control mb-3" value="<?= $row['jam_mulai'] ?>" required>
                                      <label class="form-label small text-muted">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control mb-3" value="<?= $row['jam_selesai'] ?>" required>
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
      <div class="modal-header bg-info text-white border-0"><h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST"><div class="modal-body">
          <select name="id_dokter" class="form-select mb-3" required>
              <option value="">-- Pilih Dokter --</option>
              <?php $dokters = mysqli_query($conn, "SELECT * FROM dokter"); while($d = mysqli_fetch_assoc($dokters)) { echo "<option value='{$d['id']}'>{$d['nama_dokter']}</option>"; } ?>
          </select>
          <input type="text" name="hari" class="form-control mb-3" placeholder="Contoh: Rabu" required>
          <input type="time" name="jam_mulai" class="form-control mb-3" required>
          <input type="time" name="jam_selesai" class="form-control mb-3" required>
      </div><div class="modal-footer border-0"><button type="submit" name="simpan" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm">Simpan</button></div></form>
  </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script> $(document).ready(function() { $('#tabelJadwal').DataTable({ ordering: false, language: { search: "Cari:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_", paginate: { previous: "Prev", next: "Next" } } }); }); </script>
</body>
</html>