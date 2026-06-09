<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }
$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { header('Location: index.php'); exit(); }
$row = mysqli_fetch_assoc($result);
$current_foto = $row['foto'];

$errors = [];
if (isset($_POST['update'])) {
    $nim     = mysqli_real_escape_string($conn, trim($_POST['nim']));
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $foto_filename = $current_foto;

    // Validasi NIM (Tugas 2)
    if (empty($nim)) {
        $errors[] = 'NIM tidak boleh kosong';
    } elseif (!preg_match('/^[0-9]{8,12}$/', $nim)) {
        if (!is_numeric($nim)) $errors[] = 'NIM hanya boleh berisi angka';
        else $errors[] = 'NIM harus 8 sampai 12 digit angka';
    }
    if (empty($nama))    $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan)) $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email))   $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';

    // Cek NIM duplikat (kecuali milik sendiri)
    if (empty($errors)) {
        $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim' AND id != $id");
        if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah digunakan mahasiswa lain';
    }

    // Upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto) deleteFile($current_foto);
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    // Hapus foto jika dicentang
    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
        if ($current_foto) deleteFile($current_foto);
        $foto_filename = null;
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan',
                email='$email', alamat='$alamat', foto=$foto_sql WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Data berhasil diperbarui!';
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Mahasiswa</title>
<style>
  body { font-family: Arial; background: #f0f2f5; margin: 0; }
  .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
  .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
  h2 { margin-top: 0; }
  .form-group { margin-bottom: 15px; }
  label { display: block; margin-bottom: 5px; font-weight: bold; }
  input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
  textarea { height: 80px; resize: vertical; }
  .btn { padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; }
  .btn-primary { background: #007bff; color: white; }
  .btn-secondary { background: #6c757d; color: white; }
  .alert-danger { padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
  .required { color: red; }
  .foto-preview { margin-bottom: 10px; }
  .foto-preview img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
  small { color: #888; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Edit Mahasiswa</h2>
    <?php if ($errors): ?>
      <div class="alert-danger"><?php foreach($errors as $e) echo "• $e<br>"; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Foto Profil</label>
        <?php if ($row['foto']): ?>
          <div class="foto-preview">
            <img src="uploads/mahasiswa/<?= htmlspecialchars($row['foto']) ?>" alt="Foto"><br>
            <label><input type="checkbox" name="hapus_foto" value="1"> Hapus foto ini</label>
          </div>
        <?php endif; ?>
        <input type="file" name="foto" accept="image/*">
        <small>Pilih file baru untuk mengganti foto lama</small>
      </div>
      <div class="form-group">
        <label>NIM <span class="required">*</span></label>
        <input type="text" name="nim" value="<?= htmlspecialchars(isset($_POST['nim']) ? $_POST['nim'] : $row['nim']) ?>">
        <small>8-12 digit angka</small>
      </div>
      <div class="form-group">
        <label>Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="nama" value="<?= htmlspecialchars(isset($_POST['nama']) ? $_POST['nama'] : $row['nama']) ?>">
      </div>
      <div class="form-group">
        <label>Jurusan <span class="required">*</span></label>
        <input type="text" name="jurusan" value="<?= htmlspecialchars(isset($_POST['jurusan']) ? $_POST['jurusan'] : $row['jurusan']) ?>">
      </div>
      <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input type="email" name="email" value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : $row['email']) ?>">
      </div>
      <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat"><?= htmlspecialchars(isset($_POST['alamat']) ? $_POST['alamat'] : $row['alamat']) ?></textarea>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
</body>
</html>