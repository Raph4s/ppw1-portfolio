<?php
include_once("config.php");
if (isLoggedIn()) { header('Location: index.php'); exit(); }

$error = "";
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title>
<style>
  body { font-family: Arial; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
  .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
  h2 { text-align: center; color: #333; }
  .form-group { margin-bottom: 15px; }
  label { display: block; margin-bottom: 5px; font-weight: bold; }
  input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
  .btn { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
  .btn:hover { background: #0056b3; }
  .alert { padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
  .text-center { text-align: center; margin-top: 15px; }
</style>
</head>
<body>
<div class="card">
  <h2>Login</h2>
  <?php if ($error): ?><div class="alert"><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label>Username / Email</label>
      <input type="text" name="username" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" name="login" class="btn">Login</button>
  </form>
  <div class="text-center"><a href="register.php">Belum punya akun? Register</a></div>
</div>
</body>
</html>