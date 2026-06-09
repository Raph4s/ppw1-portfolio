<?php
  $namaBulan  = date("F");     // Nama bulan dalam bahasa Inggris
  $totalHari  = date("t");     // Jumlah total hari di bulan ini
  $hariIni    = date("j");     // Tanggal hari (tanpa nol)
  $tahun      = date("Y");     // Tahun 4 digit
  $sisaHari   = $totalHari - $hariIni; // sisa hari

  // Array nama bulan dalam Bahasa Indonesia
  $bulanID = ["January"=>"Januari","February"=>"Februari",
    "March"=>"Maret","April"=>"April","May"=>"Mei",
    "June"=>"Juni","July"=>"Juli","August"=>"Agustus",
    "September"=>"September","October"=>"Oktober",
    "November"=>"November","December"=>"Desember"];
  $namaBulanID = $bulanID[$namaBulan];
?>

<div class='info-box'>
  <p>Bulan sekarang: <strong><?= $namaBulanID ?></strong></p>
  <p>Tahun: <strong><?= $tahun ?></strong></p>
  <p>Hari ini: tanggal <?= $hariIni ?></p>
  <p>Total hari di bulan ini: <?= $totalHari ?> hari</p>
  <p>Sisa hari di bulan ini: <strong><?= $sisaHari ?> hari</strong></p>
</div>
