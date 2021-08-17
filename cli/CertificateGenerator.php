<?php
namespace NuCli;

use Nubank\Requests\Discovery;
use Nubank\Exceptions\NuException;
use Nubank\Exceptions\NuRequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class CertificateGenerator
{
    private $login;
    private $password;
    private $key1;
    private $key2;
    private $deviceId;

    private $sentTo;

    private $url;
    private $client;

    public function __construct($login, $password, $deviceId, $encryptedCode=null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->deviceId = $deviceId;
        $this->encryptedCode = $encryptedCode;

        $this->key1 = $this->generateKey();
        $this->key2 = $this->generateKey();

        $this->client = new \GuzzleHttp\Client();
        // $this->client = new \GuzzleHttp\Client(['debug'=>true]);
        
        $discovery = new Discovery($this->client);
        
        $this->url = $discovery->getAppUrl('gen_certificate');
    }

    public function requestCode()
    {
        $response = $this->client->post(
            $this->url,
            [
                'json' => $this->getPayload(),
                'on_headers' => function (ResponseInterface $response) {
                    $is401 = ($response->getStatusCode() == 401);
                    $isAuthenticated = $response->hasHeader('WWW-Authenticate');

                    if ( !$is401 || !$isAuthenticated) {
                        throw new NuException('Authentication code request failed.');
                    }

                    $parsed = $this->parseAuthenticateHeaders($response->getHeader('WWW-Authenticate'));
                    $this->encryptedCode = $parsed['device-authorization_encrypted-code'];

                    $this->sentTo = $parsed['sent-to'];
                }
            ]
        );
    } 

    public function exchangeCerts($code)
    {
        if (is_null($this->encryptedCode)) {
            throw new NuException("No encrypted code found. Did you call 'request_code' before exchanging certs ?");
        }

        $payload = $this->getPayload();
        $payload['code'] = $code;
        $payload['encrypted-code'] = $this->encryptedCode;

        $response = $this->client->post($this->url, ['json' => $payload]);

        if ($response->getStatusCode() !== 200) {
            throw new NuRequestException($response);
        }

        $data = json_decode($response->getBody(), true);

        return (object)[
            'cert1' => $this->genCert($this->key1, $data['certificate'])
        ];
    } 

    private function getPayload()
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
            'public_key' => $this->getPublicKey($this->key1),
            'public_key_crypto' => $this->getPublicKey($this->key2),
            'model' => "PhpNubank Client",
            'device_id' => $this->deviceId
        ];
    }

    private function genCert($key, $cert)
    {
        $_key = openssl_pkey_export($key, $keyout, '');
        $path = realpath(__DIR__ . "/..") . "/cert/cert.p12";
        
        openssl_pkcs12_export_to_file($cert, $path, $keyout, '');
    }

    private function generateKey()
    {
        return openssl_pkey_new(array(
            'private_key_bits' => 2048,      // Size of Key.
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));
    }

    private function getPublicKey($key)
    {
        $publicKeyPem = openssl_pkey_get_details($key)['key'];
        $pkey = openssl_pkey_get_public($publicKeyPem);
        return openssl_pkey_get_details($pkey)['key'];
    }

    private function parseAuthenticateHeaders($headerContent)
    {
        $chunks = explode(",", current($headerContent));

        $parsed = [];
        foreach($chunks as $chunk) {
            $ex = explode("=", $chunk);
            $key = str_replace(" ", "_", trim($ex[0]));
            $value = str_replace('"', '', $ex[1]);
            $parsed[$key] = $value;
        }

        return $parsed;
    }

    public function getSentTo()
    {
        return $this->sentTo;
    }

    public function getDeviceId()
    {
        return $this->deviceId;
    }
}