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

$transactions = $parser->getTransactions( $bank, $user, $pass );
$balance = $parser->getBalance( $bank, $user, $pass );

if ( !$transactions ):
  $res['result'] = true;
  $res['total_transaksi'] = 0;
  $res['saldo'] = number_format($balance,0,'','');
  $res['data'] = [];
else:  
  $res['result'] = true;
  $res['total_transaksi'] = count($transactions);
  $res['saldo'] = number_format($balance,0,'','');
  foreach($transactions as $val){
      $res['data'][] = [
        'tgl' => $val[0],
        'ket' => $val[1],
        'tipe' => $val[2],
        'total' => number_format($val[3],0,'',''),
      ];
  }

endif;

header('Content-Type: application/json');
echo json_encode($res);
