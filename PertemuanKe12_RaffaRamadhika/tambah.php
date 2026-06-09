<?php
include_once("config.php");
requireLogin();

$errors = [];
$success = "";

if (isset($_POST['submit'])) {
    $nim     = mysqli_real_escape_string($conn, trim($_POST['nim']));
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $foto_filename = null;

    // Validasi wajib
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

    // Cek NIM duplikat
    if (empty($errors)) {
        $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
        if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah terdaftar';
    }

    // Upload foto (opsional)
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) $foto_filename = $upload['filename'];
        else $errors[] = $upload['message'];
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto)
                VALUES ('$nim','$nama','$jurusan','$email','$alamat',$foto_sql)";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Data berhasil ditambahkan!';
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
            if ($foto_filename) deleteFile($foto_filename);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Mahasiswa</title>
<style>
  body { font-family: Arial; background: #f0f2f5; margin: 0; }
  .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
  .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
  h2 { margin-top: 0; }
  .form-group { margin-bottom: 15px; }
  label { display: block; margin-bottom: 5px; font-weight: bold; }
  input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
  textarea { height: 80px; resize: vertical; }
  .btn { padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; }
  .btn-primary { background: #007bff; color: white; }
  .btn-secondary { background: #6c757d; color: white; }
  .alert-danger { padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
  .required { color: red; }
  small { color: #888; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Tambah Mahasiswa</h2>
    <?php if ($errors): ?>
      <div class="alert-danger"><?php foreach($errors as $e) echo "• $e<br>"; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" name="foto" accept="image/*">
        <small>Format: JPG, PNG, GIF | Maks: 5MB (opsional)</small>
      </div>
      <div class="form-group">
        <label>NIM <span class="required">*</span></label>
        <input type="text" name="nim" value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
        <small>8-12 digit angka</small>
      </div>
      <div class="form-group">
        <label>Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Jurusan <span class="required">*</span></label>
        <input type="text" name="jurusan" value="<?= htmlspecialchars($_POST['jurusan'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
</body>
</html>