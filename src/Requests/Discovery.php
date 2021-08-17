<?php

namespace Nubank\Requests;

use Nubank\Exceptions\NuException;
use Nubank\Requests\BaseRequest;

use GuzzleHttp\Client as GuzzleClient;

class Discovery extends BaseRequest
{
  const DISCOVER_URL = 'https://prod-s0-webapp-proxy.nubank.com.br/api/discovery';
  const DISCOVER_APP_URL = 'https://prod-s0-webapp-proxy.nubank.com.br/api/app/discovery';

  private $proxyListUrl;
  private $proxyListAppUrl;

  public function __construct(GuzzleClient $guzzle)
  {
    parent::__construct($guzzle);

    $this->updateProxyUrls();
  }

  public function getUrl($name)
  {
    return $this->_getUrl($name, 'main');
  }

  public function getAppUrl($name)
  {
    return $this->_getUrl($name, 'app');
  }

  private function updateProxyUrls()
  {
    // $response = $this->get(self::DISCOVER_URL);
    $this->proxyListUrl = $this->get(self::DISCOVER_URL);

    // $response = $this->get(self::DISCOVER_APP_URL);
    $this->proxyListAppUrl = $this->get(self::DISCOVER_APP_URL);
  }

  private function _getUrl($name, $listName)
  {
    $list = $this->proxyListUrl;
    if ($listName == 'app') {
      $list = $this->proxyListAppUrl;
    }

    if (!isset($list[$name])) {
      throw new NuException("There is no URL discovered for {$name}");
    }

    return $list[$name];
  }
}