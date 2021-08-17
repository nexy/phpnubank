<?php
require __DIR__ . "/../vendor/autoload.php";
require 'functions.php';

use Nubank\Exceptions\NuException;
use GuzzleHttp\Exception\ClientException;

$deviceId = generate_random_id();
$credentials = require __DIR__ . "/../examples/qrcode/credentials.php";
//echo $deviceId, $credentials['cpf'], $credentials['password'], "\n";

$gen = new NuCli\CertificateGenerator(
    $credentials['cpf'],
    $credentials['password'],
    $deviceId
);

log_message('Requesting e-mail code');

try {
    $gen->requestCode();
} catch (ClientException $e) {
    //dd($e->getMessage());
    $email = $gen->getSentTo();
    log_message( "Email sent to {$email} ");
} catch (NuException $e) {
    log_message('Failed to request code. Check your credentials!');
    //log_message($e->getMessage());
    return 0;
}

log_message( "[>] Type the code received by email: " );
$code = rtrim(fgets(STDIN));

try {
    $certs = $gen->exchangeCerts($code);
    //save_cert($certs->cert1, 'cert.p12');
    save_device_id($gen->getDeviceId());

    log_message("Certificates generated successfully. (cert.pem)");
    log_message("Warning, keep these certificates safe (Do not share or version in git)");

} catch (NuException $e) {
    log_message($e->getMessage());
    return 0;
}
