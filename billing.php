<?php 
include 'koneksi.php'; 
// Proses Bayar (UPDATE)
if(isset($_GET['bayar_id'])) {
    $id = $_GET['bayar_id'];
    mysqli_query($conn, "UPDATE pembayaran SET status_bayar = 'Lunas', tgl_bayar = NOW() WHERE id = '$id'");
    header("Location: billing.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Billing Kasir - Puskesmas</title>
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
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php" class="active"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Tagihan & Pembayaran Kasir</h2>
            </div>
            
            <div class="card p-4">
                <div class="card-body table-responsive">
                    <table id="tabelBilling" class="table table-hover table-custom w-100">
                        <thead><tr><th>Invoice ID</th><th>Nama Pasien</th><th>Tanggal Lunas</th><th>Total Biaya</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT b.id, b.total_biaya, b.status_bayar, b.tgl_bayar, p.nama_pasien 
                                    FROM pembayaran b JOIN rekam_medis rm ON b.id_rekam_medis = rm.id JOIN appointment a ON rm.id_appointment = a.id JOIN pasien p ON a.id_pasien = p.id ORDER BY b.id DESC";
                            $q = mysqli_query($conn, $sql); 
                            while($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border px-2 py-1">#INV-00<?= $row['id'] ?></span></td>
                                <td class="fw-bold"><?= $row['nama_pasien'] ?></td>
                                <td><span class="text-muted small"><?= $row['tgl_bayar'] ? date('d M Y H:i', strtotime($row['tgl_bayar'])) : '-' ?></span></td>
                                <td class="fw-bold text-primary">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if($row['status_bayar'] == 'Lunas'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3"><i class="fas fa-check-circle"></i> Lunas</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3"><i class="fas fa-times-circle"></i> Belum Lunas</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['status_bayar'] == 'Belum Lunas'): ?>
                                        <a href="?bayar_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" onclick="return confirm('Proses pembayaran untuk pasien ini?')"><i class="fas fa-money-bill-wave me-1"></i> Bayar</a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light rounded-pill px-3" disabled><i class="fas fa-print me-1"></i> Cetak</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script> $(document).ready(function() { $('#tabelBilling').DataTable({ language: { search: "Cari Invoice/Pasien:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_", paginate: { previous: "Prev", next: "Next" } } }); }); </script>
</body>
</html>