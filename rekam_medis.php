<?php 
include 'koneksi.php'; 
if(isset($_POST['simpan'])) {
    $id_appointment = $_POST['id_appointment']; $keluhan = $_POST['keluhan']; $diagnosa = $_POST['diagnosa'];
    
    // Insert rekam medis
    mysqli_query($conn, "INSERT INTO rekam_medis (id_appointment, keluhan, diagnosa) VALUES ('$id_appointment', '$keluhan', '$diagnosa')");
    $id_rm_baru = mysqli_insert_id($conn);
    
    // Auto buat tagihan
    mysqli_query($conn, "INSERT INTO pembayaran (id_rekam_medis, total_biaya, status_bayar) VALUES ('$id_rm_baru', 100000, 'Belum Lunas')");
    
    // Update appointment jadi selesai
    mysqli_query($conn, "UPDATE appointment SET status = 'Selesai' WHERE id = '$id_appointment'");
    header("Location: rekam_medis.php");
}
if(isset($_POST['edit'])) {
    $id = $_POST['id']; $keluhan = $_POST['keluhan']; $diagnosa = $_POST['diagnosa'];
    mysqli_query($conn, "UPDATE rekam_medis SET keluhan='$keluhan', diagnosa='$diagnosa' WHERE id='$id'");
    header("Location: rekam_medis.php");
}
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus']; mysqli_query($conn, "DELETE FROM rekam_medis WHERE id = '$id'"); header("Location: rekam_medis.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Rekam Medis - Puskesmas</title>
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
            <a href="rekam_medis.php" class="active"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Data Rekam Medis</h2>
                <button class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="fas fa-stethoscope me-2"></i> Catat Rekam Medis</button>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelRM" class="table table-hover table-custom w-100">
                        <thead><tr><th>Tanggal</th><th>Pasien</th><th>Keluhan</th><th>Diagnosa</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT rm.id, rm.tgl_periksa, rm.keluhan, rm.diagnosa, p.no_rm, p.nama_pasien 
                                    FROM rekam_medis rm JOIN appointment a ON rm.id_appointment = a.id JOIN pasien p ON a.id_pasien = p.id ORDER BY rm.id DESC";
                            $q = mysqli_query($conn, $sql); 
                            while($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td><span class="text-muted small"><i class="far fa-calendar-alt"></i> <?= date('d M Y H:i', strtotime($row['tgl_periksa'])) ?></span></td>
                                <td><div class="fw-bold text-dark"><?= $row['nama_pasien'] ?></div><span class="badge bg-secondary"><?= $row['no_rm'] ?></span></td>
                                <td><?= $row['keluhan'] ?></td>
                                <td class="fw-bold text-danger"><?= $row['diagnosa'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus rekam medis ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                              <div class="modal-dialog"><div class="modal-content border-0 shadow">
                                  <div class="modal-header bg-warning border-0"><h5 class="modal-title fw-bold">Edit Diagnosa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                  <form method="POST"><div class="modal-body">
                                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                      <label class="form-label small text-muted">Keluhan</label><textarea name="keluhan" class="form-control mb-3" required><?= $row['keluhan'] ?></textarea>
                                      <label class="form-label small text-muted">Diagnosa</label><textarea name="diagnosa" class="form-control mb-3" required><?= $row['diagnosa'] ?></textarea>
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
      <div class="modal-header bg-success text-white border-0"><h5 class="modal-title fw-bold">Pemeriksaan Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST"><div class="modal-body">
          <label class="form-label small text-muted">Pilih Antrian Pasien</label>
          <select name="id_appointment" class="form-select mb-3" required>
              <?php $appts = mysqli_query($conn, "SELECT a.id, p.nama_pasien FROM appointment a JOIN pasien p ON a.id_pasien = p.id WHERE a.status = 'Menunggu'");
              if(mysqli_num_rows($appts) > 0){ while($a = mysqli_fetch_assoc($appts)) { echo "<option value='{$a['id']}'>{$a['nama_pasien']}</option>"; } } else { echo "<option value=''>-- Tidak ada antrian --</option>"; } ?>
          </select>
          <label class="form-label small text-muted">Keluhan</label><textarea name="keluhan" class="form-control mb-3" required></textarea>
          <label class="form-label small text-muted">Diagnosa Dokter</label><textarea name="diagnosa" class="form-control mb-3" required></textarea>
      </div><div class="modal-footer border-0"><button type="submit" name="simpan" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Simpan & Buat Tagihan</button></div></form>
  </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script> $(document).ready(function() { $('#tabelRM').DataTable({ language: { search: "Cari:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_", paginate: { previous: "Prev", next: "Next" } } }); }); </script>
</body>
</html>