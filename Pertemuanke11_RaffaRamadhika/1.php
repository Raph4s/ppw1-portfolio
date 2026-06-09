<?php
  // Deklarasi variabel profil
  $nama       = "Raffa Ramadhika";
  $nim        = "25/560643/SV/26459";
  $prodi      = "TRPL";
  $asal_kota  = "Yogyakarta";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Mahasiswa</title>
  <style>
    table { border-collapse: collapse; width: 50%; margin: 20px auto; }
    th, td { border: 1px solid #ccc; padding: 10px 16px; }
    th { background-color: #2E75B6; color: white; }
    tr:nth-child(even) { background-color: #f2f7ff; }
  </style>
</head>
<body>
  <h2 style='text-align:center'>Profil Mahasiswa</h2>
  <table>
    <tr><th>Atribut</th><th>Informasi</th></tr>
    <tr><td>Nama</td><td><?= $nama ?></td></tr>
    <tr><td>NIM</td><td><?= $nim ?></td></tr>
    <tr><td>Program Studi</td><td><?= $prodi ?></td></tr>
    <tr><td>Asal Kota</td><td><?= $asal_kota ?></td></tr>
  </table>
</body></html>
