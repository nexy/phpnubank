<?php

namespace Nubank\Requests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Nubank\Exceptions\NuException;
use Nubank\Exceptions\NuRequestException;

abstract class BaseRequest
{
  protected $client;
  protected $cert;
  protected $headers = [];

  public function __construct(GuzzleClient $guzzle)
  {
    $this->client = $guzzle;
    $this->headers = [
      'Content-Type' => 'application/json',
      'X-Correlation-Id' => 'WEB-APP.jO4x1',
      'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
      'Origin' => 'https://conta.nubank.com.br',
      'Referer' => 'https://conta.nubank.com.br/',
    ];
  }

  public function getCert()
  {
    return $this->cert;
  }

  public function setCert($certPath)
  {
    $this->cert = $certPath;

    return $this;
  }

  public function setHeader($name, $value)
  {
    $this->headers[ $name ] = $value;

    return $this;
  }

  public function getHeader($name)
  {
    if (!isset($this->headers[ $name ])) {
      throw new NuException("Header {$name} not found");
    }

    return $this->headers[ $name ];
  }

  public function removeHeader($name)
  {
    if (isset($this->headers[ $name ])) {
      unset($this->headers[ $name ]);
    }
  }

  public function handleResponse(Response $response)
  {
    if ($response->getStatusCode() !== 200) {
      throw new NuRequestException($response);
    }

    return json_decode($response->getBody(), true);
  }

  private function rawget($url)
  {
    $data = [ 'headers' => $this->headers, ];

    if (!empty($this->cert)) {
      $data['cert'] = [$this->cert, ''];
    }

    return $this->client->request(
      'GET',
      $url,
      $data
    );
  }

  public function get($url)
  {
    return $this->handleResponse($this->rawget($url));
  }

  public function post($url, $body)
  {
    $data = [ 'headers' => $this->headers, 'json' => $body ];

    if (!empty($this->cert)) {
      $data['cert'] = [$this->cert, ''];
    }

    $response = $this->client->request(
      'POST',
      $url,
      $data
    );
    return $this->handleResponse($response);
  }

  public function getConfig()
  {
    $data = [ 'headers' => $this->headers ];

    if (!empty($this->cert)) {
      $data['cert'] = $this->cert;
    }

    return $data;
  }

  public function setConfig($config)
  {
    if (isset($config['headers'])) {
      $this->headers = $config['headers'];
    }

    if (isset($config['cert'])) {
      $this->cert = $config['cert'];
    }
  }
}