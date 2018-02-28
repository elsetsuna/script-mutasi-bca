<?php

error_reporting( E_ALL );
require( 'IbParser.php' );
$parser = new IbParser();

$token = 'THIS YOUR TOKEN';

//hardcoded the token so no need to check from headers
//foreach (getallheaders() as $name => $value) {
//  if ( $name == 'X-ACCESS-TOKEN' )
//    $token = $value;
//}

//noidea what this is for enable it make the access always forbidden... so i disable it and work like charm
//if(!$token || $_SERVER['REQUEST_METHOD'] != 'POST'){
//  header('Content-Type: application/json');
//  echo json_encode([
//    'result' => false,
//    'err' => 'Access forbidden'
//  ]);
//  exit;
//}

$base64 = base64_decode($token);
$akun   = unserialize($base64);

$bank   = $akun['bank'];
$user   = $akun['username'];
$pass   = $akun['password'];
$res = [];

$balance = $parser->getBalance( $bank, $user, $pass );
if ( !$balance ):
  $res['result'] = false;
  $res['err'] = 'Gagal mengambil transaksi';
else:
  $res['result'] = true;
  $res['saldo'] = number_format($balance,0,'','');
endif;

header('Content-Type: application/json');
echo json_encode($res);
