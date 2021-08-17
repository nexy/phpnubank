<?php

namespace Nubank;

use Nubank\Requests\Discovery;
use Nubank\Requests\Boleto\Create as CreateBoleto;
use Nubank\Requests\Card\Feed as CardFeed;
use Nubank\Requests\Card\Bills as CardBills;
use Nubank\Requests\Card\BillDetails as CardBillDetails;
use Nubank\Requests\Authentication\WithQrCode;
use Nubank\Requests\Authentication\WithCertificate;
use Nubank\Requests\Authentication\AuthRequest;
use Nubank\Requests\Authentication\RevokeToken;
use Nubank\Requests\Pix\AvailableKeys as PixAvailableKeys;
use Nubank\Requests\Pix\QrCode as PixQrCode;
use Nubank\Requests\Account\Feed as AccountFeed;
use Nubank\Requests\Account\Balance as AccountBalance;
use Nubank\Requests\Account\InvestmentsDetails as AccountInvestmentsDetails;
use Nubank\Requests\Account\InvestmentsYields as AccountInvestmentsYields;
use Nubank\Requests\Account\MoneyRequest as AccountMoneyRequest;
use Nubank\Services\MagicAttributes;
use Nubank\Utils\PixConstants;
use Nubank\EventTypes\PaymentEventTypes;
use GuzzleHttp\Client as GuzzleClient;

class Nubank
{
  use MagicAttributes;

  private $authRequest;
  private $client;
  private $discovery;
  private $token;

  public function __construct(GuzzleClient $client)
  {
    $this->client = $client;

    $this->discovery = new Discovery($this->client);
  }

  public function getClient()
  {
    return $this->client;
  }

  public function setAccessToken($token)
  {
    $this->token = $token;
    //$this->client->addHeader('Authorization', "Bearer {$token}");
  }

  public function authenticateWithQrCode($cpf, $password, $uuid)
  {
    $req = new WithQrCode($this->client);
    $req->setLoginUrls(
      $this->discovery->getUrl('login'),
      $this->discovery->getAppUrl('lift')
    );

    $authData = $req->passwordAuth($cpf, $password);

    $req->setHeader('Authorization', "Bearer {$authData['access_token']}");
    // dd($authData);

    $response = $req->getLiftData($uuid);

    $this->setAuthRequest($req);
    $this->saveAuthData($response);
    $this->setAccessToken($authData['access_token']);
  }

  private function saveAuthData($authData)
  {
    $links = $authData['_links'];

    $feed_url_keys = ['events', 'magnitude'];
    $bills_url_keys = ['bills_summary'];
    $customer_url_keys = ['customer'];

    $this->attribs['feed_url'] = $this->findUrl($feed_url_keys, $links);
    $this->attribs['bills_url'] = $this->findUrl($bills_url_keys, $links);
    $this->attribs['customer_url'] = $this->findUrl($customer_url_keys, $links);
    $this->attribs['query_url'] = $links['ghostflame']['href'];
    $this->attribs['revoke_token_url'] = $links['revoke_token']['href'];
  }

  private function findUrl($knownKeys, $list)
  {
    $currentValue = $list;

    foreach ($knownKeys as $value) {
      if (!isset($currentValue[$value])) {
        $currentValue = null;
        break;
      }

      $currentValue = $currentValue[$value];
    }

    return $currentValue;
  }

  private function setAuthRequest(AuthRequest $request)
  {
    $this->authRequest = $request;
  }

  public function getAuthRequest()
  {
    return $this->authRequest;
  }

  public function getCardFeed()
  {
    $req = new CardFeed($this->client);

    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->get($this->feed_url);
    
    return $req;
  }

  public function getCardStatements()
  {
    $feed = $this->getCardFeed();
    
    return array_filter($feed->events, function($item) {
      return $item['category'] == 'transaction';
    });
  }

  public function getBills()
  {
    /*
    if ($this->bills_url == '') {
      throw new NuMissingCreditCardException('Missing url');
    }
    */

    $req = new CardBills($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->get($this->bills_url);
    
    return $req;
  }

  public function getBillDetails($url)
  {
    $req = new CardBillDetails($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->get($url);
    
    return $req;
  }

  public function authenticateWithCert($cpf, $password, $certPath)
  {
    $req = new WithCertificate($this->client);
    $req->setCert($certPath);
    
    $url = $this->discovery->getAppUrl('token');

    $payload = [
      'grant_type' => 'password',
      'client_id' => 'legacy_client_id',
      'client_secret' => 'legacy_client_secret',
      'login' => $cpf,
      'password' => $password
    ];

    $response = $req->post($url, $payload);

    $this->saveAuthData($response);
    $this->setAuthRequest($req);
    $this->setAccessToken($response['access_token']);

    return $response['refresh_token'];
  }

  public function authenticateWithRefreshToken($refreshToken, $certPath) {}

  public function revokeToken()
  {
    $req = new RevokeToken($this->client);

    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->post($this->revoke_token_url, []);

    $req->removeHeader('Authorization');

    $this->setAuthRequest($req);
  }
  
  public function getAccountFeed()
  {
    $req = new AccountFeed($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'account_feed');

    return $req->data['viewer']['savingsAccount']['feed'];
  }

  public function getAccountStatements()
  {
    $feed = $this->getAccountFeed();

    $statements = array_map('pixTransaction', $feed['statements']);
    
    return array_filter($statements, function($item) {
      return in_array($item['__typename'], PaymentEventTypes::all());
    });
  }
  
  public function getAccountBalance()
  {
    $req = new AccountBalance($this->client);

    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'account_balance');

    return $req->data['viewer']['savingsAccount']['currentSavingsBalance']['netAmount'];
  }

  public function getAccountInvestimentsDetails()
  {
    $req = new AccountInvestmentsDetails($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'account_balance');

    return $req->data['viewer']['savingsAccount']['redeemableDeposits'];
  }

  public function getAccountInvestimentsYields($date=null)
  {
    if (is_null($date)) {
      $date = \Carbon\Carbon::now();
    }

    $payload = [
      'asOf' => $date->endOfMonth()->format("Y-m-d")
    ];

    $req = new AccountInvestmentsYields($this->client);

    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'account_investments_yield', $payload);

    return $req->label;
  }

  public function createBoleto($amount)
  {
    $req = new CreateBoleto($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->getBarCode($this->query_url, $amount);

    return $req->barcode;
  }

  public function createMoneyRequest($amount)
  {
    $req = new AccountFeed($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'account_feed');

    $account_id = $req->data['viewer']['savingsAccount']['id'];

    $payload = [
      'input' => [
        'amount' => $amount,
        'savingsAccountId' => $account_id,
      ]
    ];

    $request = new AccountMoneyRequest($this->client);
    $request->query($this->query_url, 'create_money_request', $payload);

    return $request->data['createMoneyRequest']['moneyRequest']['url'];
  }

  public function getAvailablePixKeys()
  {
    $req = new PixAvailableKeys($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->query($this->query_url, 'get_pix_keys');

    return $req;
  }

  public function createAvailablePixPaymentQrCode($accountId, $amount, $pixKey)
  {
    $req = new PixQrCode($this->client);
    
    $req->setConfig($this->authRequest->getConfig());
    $req->setHeader('Authorization', "Bearer {$this->token}");

    $req->getQrData($this->query_url, $accountId, $amount, $pixKey);

    return $req;
  }
}