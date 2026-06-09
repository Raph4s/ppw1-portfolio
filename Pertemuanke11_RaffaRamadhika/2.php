<?php
function hitungIMT($berat, $tinggi) {
  // Tinggi cm ke meter
  $tinggiMeter = $tinggi / 100;
  $imt = $berat / ($tinggiMeter * $tinggiMeter);

  if ($imt < 18.5) {
    return ["nilai" => round($imt, 2), "kategori" => "Kurus"];
  } elseif ($imt < 25) {
    return ["nilai" => round($imt, 2), "kategori" => "Normal"];
  } elseif ($imt < 30) {
    return ["nilai" => round($imt, 2), "kategori" => "Gemuk"];
  } else {
    return ["nilai" => round($imt, 2), "kategori" => "Obesitas"];
  }
}

// Contoh pemanggilan fungsi
$hasil1 = hitungIMT(50, 170);  // Berat 50kg, Tinggi 170cm
$hasil2 = hitungIMT(65, 168);  // Berat 65kg, Tinggi 168cm
$hasil3 = hitungIMT(80, 165);  // Berat 80kg, Tinggi 165cm
$hasil4 = hitungIMT(100, 165); // Berat 100kg, Tinggi 165cm
?>

<!-- tabel -->
<tr><td>50KG</td><td>---</td><td>170CM</td>
    <td><?= $hasil1["nilai"] ?></td>
    <td><?= $hasil1["kategori"] ?></td></tr>
