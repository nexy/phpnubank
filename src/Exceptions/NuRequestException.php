<?php

namespace Nubank\Exceptions;
use GuzzleHttp\Psr7\Response;

class NuRequestException extends \Exception
{
  private $response;

  public function __construct(Response $response)
  {
    $this->response = $response;

    parent::__construct(
      "The request made failed with HTTP status code {$response->getStatusCode()}",
      $response->getStatusCode()
    );
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function getStatusCode()
  {
    return $this->response->getStatusCode();
  }
}