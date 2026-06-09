<?php
include_once("config.php");
requireLogin();

$limit = 5;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET["search"]) ? mysqli_real_escape_string($conn, $_GET["search"]) : "";
$where = "";
if (!empty($search)) {
    $where = "WHERE nim LIKE '%$search%' OR nama LIKE '%$search%' OR jurusan LIKE '%$search%' OR email LIKE '%$search%'";
}

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa $where");
$total_data = mysqli_fetch_assoc($count_result)["total"];
$total_pages = ceil($total_data / $limit);

$result = mysqli_query($conn, "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html>
<head><title>Data Mahasiswa</title>
<style>
  body { font-family: Arial; margin: 0; background: #f0f2f5; }
  .navbar { background: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
  .navbar a { color: white; text-decoration: none; }
  .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
  .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
  .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
  input[type="text"] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 250px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
  th { background: #343a40; color: white; }
  tr:hover { background: #f5f5f5; }
  .photo { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
  .no-photo { width: 50px; height: 50px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #888; }
  .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; display: inline-block; border: none; cursor: pointer; }
  .btn-primary { background: #007bff; color: white; }
  .btn-warning { background: #ffc107; color: #212529; }
  .btn-danger { background: #dc3545; color: white; }
  .btn-info { background: #17a2b8; color: white; }
  .btn-secondary { background: #6c757d; color: white; }
  .pagination { margin-top: 20px; display: flex; gap: 5px; }
  .pagination a { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #007bff; }
  .pagination a.current { background: #007bff; color: white; border-color: #007bff; }
  .alert-success { padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px; }
  .alert-danger  { padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
</style>
</head>
<body>
<div class="navbar">
  <strong>Sistem Mahasiswa</strong>
  <div>Halo, <?= htmlspecialchars($_SESSION['full_name']) ?> | <a href="logout.php">Logout</a></div>
</div>
<div class="container">
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="top-bar">
      <h2 style="margin:0">Data Mahasiswa</h2>
      <a href="tambah.php" class="btn btn-primary">+ Tambah</a>
    </div>
    <form method="GET" style="margin-bottom:15px">
      <input type="text" name="search" placeholder="Cari NIM, nama, jurusan..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary">Cari</button>
      <?php if ($search): ?><a href="index.php" class="btn btn-secondary">Reset</a><?php endif; ?>
    </form>

    <table>
      <thead><tr><th>Foto</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>Email</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td>
          <?php if ($row['foto']): ?>
            <img src="uploads/mahasiswa/<?= htmlspecialchars($row['foto']) ?>" class="photo" alt="Foto">
          <?php else: ?>
            <div class="no-photo">N/A</div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['nim']) ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['jurusan']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info">Detail</a>
          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
          <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
      <?php if ($total_data == 0): ?>
      <tr><td colspan="6" style="text-align:center;color:#888">Tidak ada data ditemukan.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>

    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'current' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  </div>
</div>
</body>
</html>