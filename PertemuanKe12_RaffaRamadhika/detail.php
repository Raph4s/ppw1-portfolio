<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }
$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { header('Location: index.php'); exit(); }
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head><title>Detail Mahasiswa</title>
<style>
  body { font-family: Arial; background: #f0f2f5; margin: 0; }
  .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
  .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
  .foto-besar { width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 4px solid #007bff; margin-bottom: 20px; }
  .no-foto { width: 200px; height: 200px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #888; margin: 0 auto 20px; }
  table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 20px; }
  th, td { padding: 12px; border-bottom: 1px solid #eee; }
  th { color: #555; font-weight: bold; width: 40%; }
  .btn { padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px; }
  .btn-secondary { background: #6c757d; color: white; }
  .btn-warning { background: #ffc107; color: #212529; }
  .actions { margin-top: 20px; display: flex; gap: 10px; justify-content: center; }
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Detail Mahasiswa</h2>
    <?php if ($row['foto']): ?>
      <img src="uploads/mahasiswa/<?= htmlspecialchars($row['foto']) ?>" class="foto-besar" alt="Foto">
    <?php else: ?>
      <div class="no-foto">Tidak ada foto</div>
    <?php endif; ?>

    <table>
      <tr><th>NIM</th><td><?= htmlspecialchars($row['nim']) ?></td></tr>
      <tr><th>Nama</th><td><?= htmlspecialchars($row['nama']) ?></td></tr>
      <tr><th>Jurusan</th><td><?= htmlspecialchars($row['jurusan']) ?></td></tr>
      <tr><th>Email</th><td><?= htmlspecialchars($row['email']) ?></td></tr>
      <tr><th>Alamat</th><td><?= htmlspecialchars($row['alamat'] ?: '-') ?></td></tr>
      <tr><th>Tanggal Daftar</th><td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td></tr>
    </table>

    <div class="actions">
      <a href="index.php" class="btn btn-secondary">← Kembali</a>
      <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
    </div>
  </div>
</div>
</body>
</html>