<?php

session_start();
if(isset($_SESSION['admin'])) {
  $sessi = explode('#', $_SESSION['admin']);
  $sessiId = $sessi[0];
  $sessiSt = $sessi[1];

  if($sessiSt == 1) {
    //header('location: ?url=adminantrian');
  } else {
    header('location: ?url=rekammedis');
  }

  include_once 'Model/AntriModel.php';
  $antriModel = new AntriModel();

  include_once 'Model/PasienModel.php';
  $pasienModel = new PasienModel();

  $sekarang = date('Y-m-d');
  $sekarang = explode('-',$sekarang);
  $tanggal = $sekarang[2];
  $bulan = $sekarang[1];
  $tahun = $sekarang[0];

  $getAntriByHariIni = $antriModel->getAntriByHariIni($tanggal, $bulan, $tahun);

  $getPasienOkToday = $antriModel->getPasienOkToday($tanggal, $bulan, $tahun, 2, 2, 2);

  foreach ($getAntriByHariIni as $pasienId) {
    $getDataById = $pasienModel->getDataById($pasienId['id_pasien']);
    foreach ($getDataById as $value) {
      $nama[] = $value;
    }
  }

  if(isset($_GET['datang'])) {
    if(empty($_GET['datang'])) {
      header('location: ?url=adminantrian');
    } else {
      $id_antrian = $_GET['datang'];
      $skrng = date('Y-m-d h:i:s');
      $ubahDatang = $antriModel->ubahDatang(2, $skrng, $id_antrian);
      if($ubahDatang > 0) {
        header('location: ?url=adminantrian&msg=1');
      } else {
        $pesan = 'Error Query ubahDatang';
      }
    }
  }

  include_once 'View/Adminantrian.php';

} else {
  header('location: ?url=admin');
}
?>
