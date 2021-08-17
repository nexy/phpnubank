<?php
require "../../vendor/autoload.php";

use Nubank\Nubank;


$credentials = require __DIR__ . "/credentials.php";

//$client = new GuzzleHttp\Client(['debug' => true]);
$client = new GuzzleHttp\Client();

session_start();

$nu = new Nubank($client);

$nu->authenticateWithCert(
  $credentials['cpf'],
  $credentials['password'],
  realpath(__DIR__ . "/../../cert/cert.p12")
);

try {
  $saldo = $nu->getAccountBalance();
  $investimentos = $nu->getAccountInvestimentsYields();
  $feed = $nu->getAccountFeed();
  $pix = $nu->getAvailablePixKeys();

  if (isset($_GET['chave_pix']) && isset($_GET['montante'])) {
    $chaveAtual = $_GET['chave_pix'];
    $chpix = array_filter($pix->keys, function($item) use ($chaveAtual) {
      return $item['id'] == $chaveAtual;
    });

    $qrcode = $nu->createAvailablePixPaymentQrCode(
      $pix->account_id,
      (float)$_GET['montante'],
      current($chpix)
    );
  }

require __DIR__ . "/dashboard.php";
} catch (\Exception $e) {
  echo "<h3>{$e->getMessage()}</h3><pre>{$e->getTraceAsString()}</p>";

  file_put_contents(__DIR__ . "/../../log/error_log", $e->getTraceAsString());
}
