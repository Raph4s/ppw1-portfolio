<?php
include_once("config.php");
if (isLoggedIn()) { header('Location: index.php'); exit(); }

$errors = [];
$success = "";
if (isset($_POST['register'])) {
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if (empty($username))  $errors[] = 'Username tidak boleh kosong';
    if (empty($email))     $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';
    if (empty($full_name)) $errors[] = 'Nama lengkap tidak boleh kosong';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok';

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) $errors[] = 'Username atau email sudah terdaftar';

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, full_name, password) VALUES ('$username','$email','$full_name','$hashed')";
        if (mysqli_query($conn, $sql)) $success = 'Registrasi berhasil! Silakan login.';
        else $errors[] = 'Error: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title>
<style>
  body { font-family: Arial; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
  .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
  h2 { text-align: center; color: #333; }
  .form-group { margin-bottom: 15px; }
  label { display: block; margin-bottom: 5px; font-weight: bold; }
  input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
  .btn { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
  .alert-danger { padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
  .alert-success { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px; }
  .text-center { text-align: center; margin-top: 15px; }
</style>
</head>
<body>
<div class="card">
  <h2>Register</h2>
  <?php if ($errors): ?>
    <div class="alert-danger"><?php foreach($errors as $e) echo "• $e<br>"; ?></div>
  <?php endif; ?>
  <?php if ($success): ?><div class="alert-success"><?= $success ?> <a href="login.php">Login di sini</a></div><?php endif; ?>
  <form method="POST">
    <div class="form-group"><label>Username</label><input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
    <div class="form-group"><label>Nama Lengkap</label><input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"></div>
    <div class="form-group"><label>Password</label><input type="password" name="password"></div>
    <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="confirm_password"></div>
    <button type="submit" name="register" class="btn">Register</button>
  </form>
  <div class="text-center"><a href="login.php">Sudah punya akun? Login</a></div>
</div>
</body>
</html>