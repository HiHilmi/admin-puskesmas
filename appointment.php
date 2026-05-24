<?php 
include 'koneksi.php'; 
if(isset($_POST['simpan'])) {
    $id_pasien = $_POST['id_pasien']; $id_jadwal = $_POST['id_jadwal']; $tgl_appointment = $_POST['tgl_appointment']; $status = 'Menunggu';
    mysqli_query($conn, "INSERT INTO appointment (id_pasien, id_jadwal, tgl_appointment, status) VALUES ('$id_pasien', '$id_jadwal', '$tgl_appointment', '$status')");
    header("Location: appointment.php");
}
if(isset($_POST['edit'])) {
    $id = $_POST['id']; $id_jadwal = $_POST['id_jadwal']; $tgl_appointment = $_POST['tgl_appointment']; $status = $_POST['status'];
    mysqli_query($conn, "UPDATE appointment SET id_jadwal='$id_jadwal', tgl_appointment='$tgl_appointment', status='$status' WHERE id='$id'");
    header("Location: appointment.php");
}
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus']; mysqli_query($conn, "DELETE FROM appointment WHERE id = '$id'"); header("Location: appointment.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Janji Temu - Puskesmas</title>
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
            <a href="appointment.php" class="active"><i class="fas fa-calendar-plus me-2"></i> Janji Temu</a>
            <a href="rekam_medis.php"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Janji Temu (Appointment)</h2>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="fas fa-calendar-plus me-2"></i> Buat Janji</button>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelAppt" class="table table-hover table-custom w-100">
                        <thead><tr><th>Tanggal</th><th>Pasien</th><th>Dokter</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT a.id, a.tgl_appointment, a.status, a.id_jadwal, p.nama_pasien, p.no_rm, d.nama_dokter, s.nama_spesialisasi, j.hari, j.jam_mulai, j.jam_selesai 
                                    FROM appointment a JOIN pasien p ON a.id_pasien = p.id JOIN jadwal_dokter j ON a.id_jadwal = j.id JOIN dokter d ON j.id_dokter = d.id JOIN spesialisasi s ON d.id_spesialisasi = s.id ORDER BY a.id DESC";
                            $q = mysqli_query($conn, $sql); 
                            while($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td><span class="fw-bold text-dark"><?= date('d M Y', strtotime($row['tgl_appointment'])) ?></span></td>
                                <td><div class="fw-bold"><?= $row['nama_pasien'] ?></div><small class="text-muted"><?= $row['no_rm'] ?></small></td>
                                <td><div class="fw-bold text-primary"><?= $row['nama_dokter'] ?></div><small class="text-info"><?= $row['nama_spesialisasi'] ?></small></td>
                                <td><?= ucfirst(strtolower($row['hari'])) ?><br><small class="text-muted"><i class="far fa-clock"></i> <?= date('H:i', strtotime($row['jam_mulai'])) ?></small></td>
                                <td>
                                    <?php if($row['status'] == 'Menunggu'): ?><span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                    <?php elseif($row['status'] == 'Selesai'): ?><span class="badge bg-success rounded-pill px-3">Selesai</span>
                                    <?php else: ?><span class="badge bg-danger rounded-pill px-3">Batal</span><?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus janji ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                              <div class="modal-dialog"><div class="modal-content border-0 shadow">
                                  <div class="modal-header bg-warning border-0"><h5 class="modal-title fw-bold">Edit / Reschedule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                  <form method="POST"><div class="modal-body">
                                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                      <label class="form-label small text-muted">Pasien</label><input type="text" class="form-control mb-3" value="<?= $row['nama_pasien'] ?>" readonly>
                                      <label class="form-label small text-muted">Ubah Jadwal</label>
                                      <select name="id_jadwal" class="form-select mb-3" required>
                                          <?php $jadwals = mysqli_query($conn, "SELECT j.id, d.nama_dokter, s.nama_spesialisasi, j.hari, j.jam_mulai FROM jadwal_dokter j JOIN dokter d ON j.id_dokter = d.id JOIN spesialisasi s ON d.id_spesialisasi = s.id");
                                          while($j = mysqli_fetch_assoc($jadwals)) { $sel = ($j['id'] == $row['id_jadwal']) ? 'selected' : ''; echo "<option value='{$j['id']}' $sel>{$j['nama_dokter']} - {$j['hari']} {$j['jam_mulai']}</option>"; } ?>
                                      </select>
                                      <label class="form-label small text-muted">Tanggal</label><input type="date" name="tgl_appointment" class="form-control mb-3" value="<?= $row['tgl_appointment'] ?>" required>
                                      <label class="form-label small text-muted">Status</label>
                                      <select name="status" class="form-select mb-3">
                                          <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                          <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                          <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : '' ?>>Batal</option>
                                      </select>
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
      <div class="modal-header bg-primary text-white border-0"><h5 class="modal-title fw-bold">Buat Janji Temu</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST"><div class="modal-body">
          <select name="id_pasien" class="form-select mb-3" required>
              <option value="">-- Pilih Pasien --</option>
              <?php $pasiens = mysqli_query($conn, "SELECT id, nama_pasien FROM pasien"); while($p = mysqli_fetch_assoc($pasiens)) { echo "<option value='{$p['id']}'>{$p['nama_pasien']}</option>"; } ?>
          </select>
          <select name="id_jadwal" class="form-select mb-3" required>
              <option value="">-- Pilih Dokter/Jadwal --</option>
              <?php $jadwals = mysqli_query($conn, "SELECT j.id, d.nama_dokter, j.hari, j.jam_mulai FROM jadwal_dokter j JOIN dokter d ON j.id_dokter = d.id");
              while($j = mysqli_fetch_assoc($jadwals)) { echo "<option value='{$j['id']}'>Dr. {$j['nama_dokter']} - {$j['hari']} [{$j['jam_mulai']}]</option>"; } ?>
          </select>
          <input type="date" name="tgl_appointment" class="form-control mb-3" required>
      </div><div class="modal-footer border-0"><button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan</button></div></form>
  </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script> $(document).ready(function() { $('#tabelAppt').DataTable({ language: { search: "Cari Data:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_", paginate: { previous: "Prev", next: "Next" } } }); }); </script>
</body>
</html>