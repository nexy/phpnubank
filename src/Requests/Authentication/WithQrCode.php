<?php

namespace Nubank\Requests\Authentication;

use Ramsey\Uuid\Uuid;
use chillerlan\QRCode\{QRCode, QROptions};

class WithQrCode extends AuthRequest
{
  public function passwordAuth($cpf, $password)
  {
    $payload = [
      "grant_type" => "password",
      "login" => $cpf,
      "password" => $password,
      "client_id" => "legacy_client_id",
      "client_secret" => "legacy_client_secret"
    ];

    //error_log("mandando para login");
    $response = $this->post($this->loginUrl, $payload);

    return $response;
  }

  public function getLiftData($uuid)
  {
    $payload = [
      "qr_code_id" => $uuid,
      "type" => "login-webapp",
    ];

    //error_log("mandando para lift data");
    return $this->post($this->liftUrl, $payload);
  }

  static public function getQrData()
  {
    $uuid = Uuid::uuid4();

    $data = [
      'content' => $uuid,
      'qr' => (new QRCode)->render($uuid),
    ];
    
    return (object)$data;
  }
}