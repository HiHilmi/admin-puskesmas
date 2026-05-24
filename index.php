<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Puskesmas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f0f2f5; 
        }
        /* Sidebar Modern Gradient */
        .sidebar { 
            height: 100vh; 
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white; 
            padding-top: 30px;
            position: fixed;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar h4 { font-weight: 700; letter-spacing: 1px; margin-bottom: 30px; }
        .sidebar a { 
            color: #d1d8e0; text-decoration: none; display: block; 
            padding: 12px 20px; margin: 8px 15px; border-radius: 10px;
            transition: all 0.3s ease; font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255,255,255,0.15); color: #ffffff; 
            transform: translateX(5px);
        }
        /* Main Content Offset karena sidebar fixed */
        .main-content { margin-left: 16.666667%; padding: 40px; }
        
        /* Card Modern (Soft UI) */
        .card { 
            border: none; border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.04); transition: transform 0.3s;
        }
        .card:hover { transform: translateY(-5px); }
        .stat-card { border-radius: 20px; color: white; overflow: hidden; }
        .bg-gradient-primary { background: linear-gradient(45deg, #4e54c8, #8f94fb); }
        .bg-gradient-success { background: linear-gradient(45deg, #11998e, #38ef7d); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 sidebar">
            <h4 class="text-center"><i class="fas fa-hospital-user fa-lg mb-2"></i><br>Puskesmas</h4>
            <a href="index.php" class="active"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="pasien.php"><i class="fas fa-users me-2"></i> Data Pasien</a>
            <a href="jadwal.php"><i class="fas fa-calendar-check me-2"></i> Jadwal Dokter</a>
            <a href="appointment.php"><i class="fas fa-calendar-plus me-2"></i> Janji Temu</a>
            <a href="rekam_medis.php"><i class="fas fa-notes-medical me-2"></i> Rekam Medis</a>
            <a href="obat.php"><i class="fas fa-pills me-2"></i> Data Obat</a>
            <a href="billing.php"><i class="fas fa-file-invoice-dollar me-2"></i> Billing Kasir</a>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Dashboard Overview</h2>
                    <p class="text-muted">Selamat datang kembali, Admin!</p>
                </div>
            </div>

            <div class="row">
                <?php 
                $q_pasien = mysqli_query($conn, "SELECT COUNT(id) as total FROM pasien");
                $total_pasien = mysqli_fetch_assoc($q_pasien)['total'];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card stat-card bg-gradient-primary p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75">Total Pasien Terdaftar</p>
                                <h1 class="fw-bold mb-0"><?= $total_pasien ?></h1>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <?php 
                $q_obat = mysqli_query($conn, "SELECT SUM(stok) as total_stok FROM obat");
                $total_stok = mysqli_fetch_assoc($q_obat)['total_stok'] ?? 0;
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card stat-card bg-gradient-success p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75">Total Stok Obat</p>
                                <h1 class="fw-bold mb-0"><?= $total_stok ?></h1>
                            </div>
                            <i class="fas fa-box-open fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-history text-primary"></i> Laporan Appointment Terakhir (Dari View)</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-secondary">Tgl Appointment</th>
                                    <th class="text-secondary">Pasien (RM)</th>
                                    <th class="text-secondary">Dokter & Spesialis</th>
                                    <th class="text-secondary">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q_view = mysqli_query($conn, "SELECT * FROM view_laporan_appointment ORDER BY tgl_appointment DESC LIMIT 5");
                                while($row = mysqli_fetch_assoc($q_view)) {
                                    echo "<tr>
                                        <td><span class='fw-medium text-dark'>{$row['tgl_appointment']}</span></td>
                                        <td>
                                            <div class='fw-bold'>{$row['nama_pasien']}</div>
                                            <small class='text-muted'>{$row['no_rm']}</small>
                                        </td>
                                        <td>
                                            <div>{$row['nama_dokter']}</div>
                                            <small class='text-primary'>{$row['nama_spesialisasi']}</small>
                                        </td>
                                        <td><span class='badge rounded-pill bg-warning text-dark px-3 py-2'>{$row['status']}</span></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>